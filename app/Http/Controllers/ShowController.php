<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowController extends Controller
{
    // Menampilkan halaman utama student
    public function index()
    {
        return view('student.index');
    }

    // Menampilkan halaman detail student (show.blade.php)
    public function show($id)
    {
        // Dummy data
        $students = [
            '5026231000' => ['name' => 'Harry Styles', 'program' => 'Sistem Informasi', 'status' => 'Pending'],
            '5026231001' => ['name' => 'T. Hiddleston', 'program' => 'Teknik Industri', 'status' => 'Pending'],
            '5026231002' => ['name' => 'A. Taylor', 'program' => 'Inovasi Digital', 'status' => 'Need Revision'],
            '5026231003' => ['name' => 'S. Ohtani', 'program' => 'Sistem Informasi', 'status' => 'Accepted'],
            '5026231004' => ['name' => 'S. Curry', 'program' => 'Design Interior', 'status' => 'Accepted'],
            '5026231005' => ['name' => 'K. Middleton', 'program' => 'Teknik Elektro', 'status' => 'Rejected'],
            '5026231006' => ['name' => 'Benedict', 'program' => 'Biologi', 'status' => 'Accepted'],
            '5026231007' => ['name' => 'V. Beckham', 'program' => 'Arsitektur', 'status' => 'Accepted'],
        ];

        // Ambil data berdasarkan ID (NRP)
        $student = $students[$id] ?? null;

        if (!$student) {
            abort(404, 'Student not found');
        }

        // Kirim ke view show.blade.php
        return view('student.show', compact('student', 'id'));
    }

    // Halaman edit submission (opsional)
    public function edit()
    {
        return view('student.submissions.edit');
    }
}