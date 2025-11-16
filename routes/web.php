<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/* -------------------------------------------------------------
| LECTURER SECTION 
|--------------------------------------------------------------*/
Route::prefix('lecturer')->name('lecturer.')->group(function () {
    // Halaman "root" lecturer (kalau ada landing khusus)
    Route::view('/', 'lecturer.index')->name('index');

    // Dashboard lecturer // AKHTAR FATTAN WIDODO (5026231044)
    Route::view('/dashboard', 'lecturer.dashboard.dashboard')->name('dashboard');