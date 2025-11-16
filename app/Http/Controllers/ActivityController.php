<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Submission\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        return Activity::paginate(10);
    }

    public function show(string $id)
    {
        return Activity::findOrFail($id);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'             => 'required|string',
            'date'             => 'required|date',
            'location'         => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $data['id'] = (string) Str::uuid();

        return Activity::create($data);
    }

    public function update(Request $req, string $id)
    {
        $act = Activity::findOrFail($id);

        $data = $req->validate([
            'name'             => 'sometimes|required|string',
            'date'             => 'sometimes|required|date',
            'location'         => 'sometimes|required|string',
            'duration_minutes' => 'sometimes|required|integer|min:1',
        ]);

        $act->update($data);
        return $act;
    }

    public function destroy(string $id)
    {
        Activity::findOrFail($id)->delete();
        return response()->noContent();
    }
}
