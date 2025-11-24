<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FileAttachment extends Model
{
    use HasUuids;

    protected $table = 'file_attachment';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'submission_id',
        'file_name',
        'file_type',
        'url',
        'size_mb',
    ];

    protected $casts = [
        'size_mb' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }
}
