<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileAttachment;

class FileAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fileAttachments = FileAttachment::with('submission')->paginate(15);
        return view('file-attachment.index', compact('fileAttachments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('file-attachment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|uuid|exists:submission,id',
            'file_name' => 'required|string|max:255',
            'file_type' => 'required|in:JPG,JPEG,PNG,PDF',
            'url' => 'required|string|max:500',
            'size_mb' => 'required|numeric|min:0|max:25',
        ]);

        $fileAttachment = FileAttachment::create($validated);

        return redirect()->route('file-attachments.show', $fileAttachment->id)
                        ->with('success', 'File attachment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fileAttachment = FileAttachment::with('submission')->findOrFail($id);
        return view('file-attachment.show', compact('fileAttachment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fileAttachment = FileAttachment::findOrFail($id);
        return view('file-attachment.edit', compact('fileAttachment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fileAttachment = FileAttachment::findOrFail($id);

        $validated = $request->validate([
            'file_name' => 'sometimes|required|string|max:255',
            'file_type' => 'sometimes|required|in:JPG,JPEG,PNG,PDF',
            'url' => 'sometimes|required|string|max:500',
            'size_mb' => 'sometimes|required|numeric|min:0|max:25',
        ]);

        $fileAttachment->update($validated);

        return redirect()->route('file-attachments.show', $fileAttachment->id)
                        ->with('success', 'File attachment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fileAttachment = FileAttachment::findOrFail($id);
        $fileAttachment->delete();

        return redirect()->route('file-attachments.index')
                        ->with('success', 'File attachment deleted successfully.');
    }
}
