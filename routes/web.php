<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;

/* -------------------------------------------------------------
| STUDENT DASHBOARD | Taffy Nirarale Kamajaya - 5026221047
|--------------------------------------------------------------*/
Route::view('/student/dashboard', 'student.dashboard.dashboard')->name('student.dashboard');