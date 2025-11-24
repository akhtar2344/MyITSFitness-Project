<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with('submission', 'student', 'lecturer')->paginate(15);
        return view('comment.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|uuid|exists:submission,id',
            'student_id' => 'nullable|uuid|exists:student,id',
            'lecturer_id' => 'nullable|uuid|exists:lecturer,id',
            'text' => 'required|string',
            'is_private' => 'nullable|boolean',
        ]);

        $comment = Comment::create($validated);

        return redirect()->route('comments.show', $comment->id)
                        ->with('success', 'Comment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::with('submission', 'student', 'lecturer')->findOrFail($id);
        return view('comment.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $comment = Comment::findOrFail($id);
        return view('comment.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'text' => 'sometimes|required|string',
            'is_private' => 'nullable|boolean',
        ]);

        $comment->update($validated);

        return redirect()->route('comments.show', $comment->id)
                        ->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('comments.index')
                        ->with('success', 'Comment deleted successfully.');
    }
}
