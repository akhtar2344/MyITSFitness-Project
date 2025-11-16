<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $table = 'people';

    protected $fillable = [
        'nrp',
        'name',
        'email',
        'phone',
        'program',
        'avatar_path',
        'type',          // student | lecturer
    ];

    public function auth()
    {
        return $this->hasOne(Auth::class);
    }

    public function submissions()        // sebagai student
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function reviews()            // sebagai lecturer
    {
        return $this->hasMany(Submission::class, 'lecturer_id');
    }

    public function shared()
    {
        return $this->hasMany(Shared::class, 'owner_id');
    }

    public function notifies()
    {
        return $this->hasMany(Notify::class, 'author_id');
    }
}
