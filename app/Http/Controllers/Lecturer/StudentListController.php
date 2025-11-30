<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentListController extends Controller
{
    /**
     * Display a listing of all students.
     */
    public function index()
    {
        $students = Student::orderBy('name', 'asc')->get();
        return view('lecturer.index', compact('students'));
    }
}
