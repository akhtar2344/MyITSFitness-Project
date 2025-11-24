<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StatusHistory extends Model
{
    use HasUuids;

    protected $table = 'status_history';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'from_status',
        'to_status',
        'note',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    // Relationships
    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }
}
