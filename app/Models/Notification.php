<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasUuids;

    protected $table = 'notification';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'recipient_user_id',
        'type',
        'title',
        'message',
        'is_read',
        'related_submission_id',
        'created_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function recipientUser()
    {
        return $this->belongsTo(UserAccount::class, 'recipient_user_id');
    }

    public function relatedSubmission()
    {
        return $this->belongsTo(Submission::class, 'related_submission_id');
    }
}
