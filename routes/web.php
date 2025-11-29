<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\LecturerDashboardController;
use App\Http\Controllers\SubmissionDetailController;
use App\Http\Controllers\Lecturer\SubmissionReviewController;
use App\Http\Controllers\Student\SubmissionManagementController;
use App\Http\Controllers\Student\SubmissionDetailController as StudentSubmissionDetailController;
use App\Http\Controllers\Student\StatusPageController;
use App\Models\Comment;

/* =============================================================
   AUTHENTICATION - Unified Login
   ============================================================= */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/login/myits', [AuthController::class, 'loginWithMyITS'])->name('login.myits');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* =============================================================
   STUDENT DASHBOARD
   ============================================================= */
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

/*
|--------------------------------------------------------------------------
| Web Routes : CREATED BY FEZIH SUHAIMAH JINAN 5026231055
|--------------------------------------------------------------------------
*/

Route::prefix('student')->name('student.')->group(function () {
    // ===== Submit Page =====
    Route::view('/submit', 'student.submit')->name('submit');

    // ===== Status Page (Dynamic - with real submissions) =====
    Route::get('/status', [StatusPageController::class, 'index'])->name('status');

    // ===== Submission Detail Page (Dynamic) =====
    Route::get('/submissions/{submission}/view', [StudentSubmissionDetailController::class, 'show'])->name('submissions.show');

    // ===== Activity Details (Mock Harry - static views) =====
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

    Route::get('/submissions/edit', [SubmissionManagementController::class, 'edit'])->name('submissions.edit');
    Route::post('/submissions', [SubmissionManagementController::class, 'store'])->name('submissions.store');
    Route::post('/submissions/{submission}/cancel', [SubmissionManagementController::class, 'cancel'])->name('submissions.cancel');
    Route::get('/submissions/{submission}/resubmit', [SubmissionManagementController::class, 'showResubmit'])->name('submissions.resubmit');
    Route::post('/submissions/{submission}/resubmit', [SubmissionManagementController::class, 'storeResubmit'])->name('submissions.resubmitStore');
    Route::post('/submissions/{submission}/comment', [SubmissionManagementController::class, 'storeComment'])->name('submissions.comment');
    Route::delete('/comments/{comment}', [SubmissionManagementController::class, 'deleteComment'])->where('comment', '[a-f0-9\\-]+')->name('comments.delete');
    Route::get('/{id}', [ShowController::class, 'show'])->name('show');
});

/* -------------------------------------------------------------
| LECTURER SECTION
|--------------------------------------------------------------*/
Route::prefix('lecturer')->name('lecturer.')->group(function () {
    // Halaman “root” lecturer (kalau ada landing khusus)
    Route::view('/', 'lecturer.index')->name('index');

    // Dashboard lecturer - now with real data from controller
    Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('dashboard');

    // ===== Students List & Detail ===== Ahmad Faiz Ramdhani
    // List students -> resources/views/lecturer/index.blade.php
    Route::get('/students', function () {
        return view('lecturer.index');
    })->name('students.index');

     // Detail submission -> resources/views/lecturer/show.blade.php
    Route::get('/submissions/{submission}', [SubmissionDetailController::class, 'show'])->name('submissions.show');

     /* ====== NEW: Lecturer Status Account (untuk halaman seperti figma) ====== */
    Route::get('/status/{id}', function (string $id) {
        return view('lecturer.status-account', compact('id'));
    })->name('status.account');
    /* ======================================================================= */

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
| LECTURER REVIEWS (List + Action) - Marvello Adipertama
|--------------------------------------------------------------*/
Route::prefix('lecturer/reviews')->name('lecturer.reviews.')->group(function () {
    Route::get('/', [SubmissionReviewController::class, 'index'])->name('index');
    Route::get('/sublec', [SubmissionReviewController::class, 'index'])->name('sublec');

    Route::post('/{submission}/accept', [SubmissionDetailController::class, 'accept'])->name('accept');
    Route::post('/{submission}/reject', [SubmissionDetailController::class, 'reject'])->name('reject');
    Route::post('/{submission}/request-revision', [SubmissionDetailController::class, 'requestRevision'])->name('requestRevision');
    Route::post('/{submission}/comment', function ($submission) {
        return back()->with('ok', "Comment sent for {$submission}");
    })->name('comment');
});

/* ===== REMOVED: Old authentication routes replaced with unified AuthController ===== */

