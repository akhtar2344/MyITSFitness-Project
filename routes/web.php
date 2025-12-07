<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\LecturerDashboardController;
use App\Http\Controllers\SubmissionDetailController;
use App\Http\Controllers\Lecturer\SubmissionReviewController;
use App\Http\Controllers\Lecturer\StudentListController;
use App\Http\Controllers\Lecturer\StudentStatusController;
use App\Http\Controllers\Student\SubmissionManagementController;
use App\Http\Controllers\Student\SubmissionDetailController as StudentSubmissionDetailController;
use App\Http\Controllers\Student\StatusPageController;
use App\Models\Comment;

/* =============================================================
   FEATURE: Authentication routes with unified login system
   ============================================================= */
Route::get('/login', [AuthController::class, 'openLoginPage'])->name('login');
Route::post('/login', [AuthController::class, 'loginReady'])->name('login.process');
// FEATURE: MyITS SSO integration placeholder route
Route::get('/login/myits', [AuthController::class, 'loginWithMyITS'])->name('login.myits');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* =============================================================
   FEATURE: Student dashboard with role-based access
   ============================================================= */
Route::get('/student/dashboard', [StudentDashboardController::class, 'openHomePage'])->name('student.dashboard');

/*
|--------------------------------------------------------------------------
| FEATURE: Student route group with prefix and middleware organization
|--------------------------------------------------------------------------
*/

// FEATURE: Route group with prefix for organized student routes
Route::prefix('student')->name('student.')->group(function () {
    // FEATURE: Static view routes for submission forms
    Route::view('/submit', 'student.submit')->name('submit');

    // FEATURE: Dynamic status page with real submission data
    Route::get('/status', [StatusPageController::class, 'openStatusPage'])->name('status');

    // FEATURE: Dynamic submission detail routes with parameter binding
    Route::get('/submissions/{submission}/view', [StudentSubmissionDetailController::class, 'openDetailPage'])->name('submissions.show');

    // FEATURE: Static activity detail routes for UI demonstration
    Route::view('/activity/harry/pending', 'student.activity.show-pending')->name('activity.show.pending');
    Route::view('/activity/harry/accepted', 'student.activity.show-accepted')->name('activity.show.accepted');
    Route::view('/activity/harry/need-revision', 'student.activity.show-needrevision')->name('activity.show.needrevision');

    // ===== Edit & Re-submit form =====
    Route::view('/activity/harry/edit', 'student.activity.edit-resubmit')->name('activity.edit');

    // ===== Resubmit handler (dummy) =====
    Route::post('/activity/harry/resubmit', function () {
        return redirect()
            ->route('student.activity.show.pending')
            ->with('resubmitted', true);
    })->name('activity.resubmit');

    // Optional previews
    Route::get('/show', function () {
        $student = ['name' => 'Benedict', 'program' => 'Biologi', 'status' => 'Accepted'];
        $id = '5026231006';
        return view('student.show', compact('student', 'id'));
    })->name('show.test');

    // FEATURE: Dynamic submission management routes with form handling
    Route::get('/submissions/edit', [SubmissionManagementController::class, 'openSubmissionPage'])->name('submissions.edit');
    Route::post('/submissions', [SubmissionManagementController::class, 'store'])->name('submissions.store');
    Route::post('/submissions/{submission}/cancel', [SubmissionManagementController::class, 'cancel'])->name('submissions.cancel');
    Route::get('/submissions/{submission}/resubmit', [SubmissionManagementController::class, 'beginRevision'])->name('submissions.resubmit');
    Route::post('/submissions/{submission}/resubmit', [SubmissionManagementController::class, 'submitRevision'])->name('submissions.resubmitStore');
    
    // FEATURE: Comment system routes with CRUD operations
    Route::post('/submissions/{submission}/comment', [SubmissionManagementController::class, 'SendCommentRequset'])->name('submissions.comment');
    Route::delete('/comments/{comment}', [SubmissionManagementController::class, 'deleteComment'])->where('comment', '[a-f0-9\\-]+')->name('comments.delete');
    
    Route::get('/{id}', [ShowController::class, 'show'])->name('show');
});

/* -------------------------------------------------------------
| FEATURE: Lecturer route group with role-based functionality
|--------------------------------------------------------------*/
Route::prefix('lecturer')->name('lecturer.')->group(function () {
    // FEATURE: Lecturer home page with student list display
    Route::get('/', [StudentListController::class, 'navigateToStudents'])->name('students');

    // FEATURE: Lecturer dashboard with statistics and data visualization
    Route::get('/dashboard', [LecturerDashboardController::class, 'viewDashboard'])->name('dashboard');

    // FEATURE: Student management routes for lecturer interface
    Route::get('/students', [StudentListController::class, 'navigateToStudents'])->name('students.index');

    // FEATURE: Submission review route with grading functionality
    Route::get('/submissions/{submission}', [SubmissionDetailController::class, 'openSubmissionDetailPage'])->name('submissions.show');

    // FEATURE: Student status tracking by NRP parameter
    Route::get('/status/{nrp}', [StudentStatusController::class, 'show'])->name('status.account');

    // (Opsional lama) contoh /lecturer/show dummy — dibiarkan karena path beda
    Route::get('/show', function () {
        $submission = [
            'id'           => '5026231006',
            'student_name' => 'Benedict',
            'nrp'          => '5026231006',
            'submitted_at' => '2024-12-11',
            'activity'     => 'Running',
            'location'     => 'Pakuwon City',
            'duration'     => '2 Hour',
            'status'       => 'Accepted',
            'proof_url'    => asset('images/sample-proof.jpg'),
        ];
        $comments = [
            ['name' => 'Heru Susanto', 'avatar' => asset('images/lecturer.png'), 'text' => 'Good work!'],
        ];
        return view('lecturer.show', compact('submission', 'comments'));
    })->name('show');
});

/* -------------------------------------------------------------
| FEATURE: Lecturer submission review routes with grading actions
|--------------------------------------------------------------*/
Route::prefix('lecturer/reviews')->name('lecturer.reviews.')->group(function () {
    // FEATURE: Submission list and review interface routes
    Route::get('/', [SubmissionReviewController::class, 'openSubmissionPage'])->name('index');
    Route::get('/sublec', [SubmissionReviewController::class, 'openSubmissionPage'])->name('sublec');

    // FEATURE: Submission grading action routes (Accept/Reject/Request Revision)
    Route::post('/{submission}/accept', [SubmissionDetailController::class, 'accept'])->name('accept');
    Route::post('/{submission}/reject', [SubmissionDetailController::class, 'reject'])->name('reject');
    Route::post('/{submission}/request-revision', [SubmissionDetailController::class, 'requestRevision'])->name('requestRevision');
    
    // FEATURE: Comment submission route for lecturer feedback
    Route::post('/{submission}/comment', function ($submission) {
        return back()->with('ok', "Comment sent for {$submission}");
    })->name('comment');
});

/* ===== FEATURE: Clean routing architecture with unified authentication ===== */

