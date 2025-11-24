<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Lecturer extends Model
{
    use HasUuids;

    protected $table = 'lecturer';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'email',
        'department',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    public function revisionRequests()
    {
        return $this->hasMany(RevisionRequest::class, 'lecturer_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'lecturer_id');
    }
}
