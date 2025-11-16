<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;

/* -------------------------------------------------------------
| STUDENT DASHBOARD | Taffy Nirarale Kamajaya - 5026221047
|--------------------------------------------------------------*/
Route::view('/student/dashboard', 'student.dashboard.dashboard')->name('student.dashboard');

/*
|--------------------------------------------------------------------------
| Web Routes : CREATED BY FEZIH SUHAIMAH JINAN 5026231055
|--------------------------------------------------------------------------
*/

Route::prefix('student')->name('student.')->group(function () {
    // ===== Submit Page =====
    Route::view('/submit', 'student.submit')->name('submit');

    // ===== Activity Details (mock Harry) =====
    Route::view('/activity/harry/pending', 'student.activity.show-pending')->name('activity.show.pending');
    Route::view('/activity/harry/accepted', 'student.activity.show-accepted')->name('activity.show.accepted');
    Route::view('/activity/harry/need-revision', 'student.activity.show-need-revision')->name('activity.show.needrevision');

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

    Route::get('/submissions/edit', [ShowController::class, 'edit'])->name('submissions.edit');
    Route::get('/{id}', [ShowController::class, 'show'])->whereNumber('id')->name('show');
});

/* -------------------------------------------------------------
| LECTURER SECTION
|--------------------------------------------------------------*/
Route::prefix('lecturer')->name('lecturer.')->group(function () {
    // Halaman “root” lecturer (kalau ada landing khusus)
    Route::view('/', 'lecturer.index')->name('index');

    // Dashboard lecturer
    Route::view('/dashboard', 'lecturer.dashboard.dashboard')->name('dashboard');