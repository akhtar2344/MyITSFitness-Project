<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Submission extends Model
{
    use HasUuids;

    protected $table = 'submission';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'activity_id',
        'name', // Activity category name
        'status',
        'duration_minutes',
        'location',
        'date',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'submission_id');
    }

    public function fileAttachments()
    {
        return $this->hasMany(FileAttachment::class, 'submission_id');
    }

    public function revisionRequests()
    {
        return $this->hasMany(RevisionRequest::class, 'submission_id');
    }

    // FEATURE: Remove unused status history relationship as table is being dropped
    // public function statusHistories()
    // {
    //     return $this->hasMany(StatusHistory::class, 'submission_id');
    // }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_submission_id');
    }
}

