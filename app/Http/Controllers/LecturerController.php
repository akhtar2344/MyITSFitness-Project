<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecturer;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturers = Lecturer::with('userAccount', 'revisionRequests', 'comments')->paginate(15);
        return view('lecturer.index', compact('lecturers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lecturer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:user_account,id',
            'employee_id' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'department' => 'required|string|max:100',
        ]);

        $lecturer = Lecturer::create($validated);

        return redirect()->route('lecturers.show', $lecturer->id)
                        ->with('success', 'Lecturer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lecturer = Lecturer::with('userAccount', 'revisionRequests', 'comments')->findOrFail($id);
        return view('lecturer.show', compact('lecturer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lecturer = Lecturer::findOrFail($id);
        return view('lecturer.edit', compact('lecturer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lecturer = Lecturer::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'sometimes|required|string|max:50',
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100',
            'department' => 'sometimes|required|string|max:100',
        ]);

        $lecturer->update($validated);

        return redirect()->route('lecturers.show', $lecturer->id)
                        ->with('success', 'Lecturer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $lecturer->delete();

        return redirect()->route('lecturers.index')
                        ->with('success', 'Lecturer deleted successfully.');
    }
}
