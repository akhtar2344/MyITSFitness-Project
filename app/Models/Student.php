<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Student extends Model
{
    use HasUuids;

    protected $table = 'student';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nrp',
        'name',
        'email',
        'program',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'student_id');
    }
}
