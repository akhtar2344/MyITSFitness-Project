<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentListController extends Controller
{
    /**
     * Navigate to students page and display a listing of all students.
     */
    public function navigateToStudents()
    {
        $students = Student::orderBy('name', 'asc')->get();
        return view('lecturer.index', compact('students'));
    }
}
