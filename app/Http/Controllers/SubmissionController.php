<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Submission\Submission;

class SubmissionController extends Controller
{
    public function index()
    {
        return Submission::with(['student','activity'])->paginate(10);
    }

    public function show(string $id)
    {
        return Submission::with(['student','activity'])->findOrFail($id);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'student_id'       => 'required|uuid|exists:people.student,id',
            'activity_id'      => 'required|uuid|exists:submission.activity,id',
            'duration_minutes' => 'required|integer|min:1',
            'status'           => 'nullable|in:Pending,Accepted,Rejected,NeedRevision',
            'note'             => 'nullable|string',
        ]);

        $data['id'] = (string) Str::uuid();

        $sub = Submission::create($data);

        return $sub->load(['student','activity']);
    }

    public function update(Request $req, string $id)
    {
        $sub = Submission::findOrFail($id);

        $data = $req->validate([
            'duration_minutes' => 'sometimes|required|integer|min:1',
            'status'           => 'sometimes|nullable|in:Pending,Accepted,Rejected,NeedRevision',
            'note'             => 'sometimes|nullable|string',
        ]);

        $sub->update($data);

        return $sub->load(['student','activity']);
    }

    public function destroy(string $id)
    {
        Submission::findOrFail($id)->delete();
        return response()->noContent();
    }
}
