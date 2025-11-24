<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('userAccount', 'submissions')->paginate(15);
        return view('student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:user_account,id',
            'nrp' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'program' => 'required|string|max:100',
        ]);

        $student = Student::create($validated);

        return redirect()->route('students.show', $student->id)
                        ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with('userAccount', 'submissions', 'comments')->findOrFail($id);
        return view('student.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('student.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'nrp' => 'sometimes|required|string|max:50',
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100',
            'program' => 'sometimes|required|string|max:100',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student->id)
                        ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'Student deleted successfully.');
    }
}
