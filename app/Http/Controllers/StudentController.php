<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\People\Student;

class StudentController extends Controller
{
    public function index()
    {
        return Student::with('userAccount')->paginate(10);
    }

    public function show(string $id)
    {
        return Student::with('userAccount')->findOrFail($id);
    }

    public function store(Request $req)
    {
        // sesuaikan dengan kolom wajib yang kamu punya
        $data = $req->validate([
            'user_id' => 'required|uuid|exists:auth.user_account,id',
            'nrp'     => 'required|string',
            'name'    => 'required|string',
            'email'   => 'required|string',
            'program' => 'required|string',
        ]);

        $data['id'] = (string) Str::uuid();

        return Student::create($data);
    }

    public function update(Request $req, string $id)
    {
        $stu = Student::findOrFail($id);

        $data = $req->validate([
            'nrp'     => 'sometimes|required|string',
            'name'    => 'sometimes|required|string',
            'email'   => 'sometimes|required|string',
            'program' => 'sometimes|required|string',
        ]);

        $stu->update($data);
        return $stu->load('userAccount');
    }

    public function destroy(string $id)
    {
        Student::findOrFail($id)->delete();
        return response()->noContent();
    }
}
