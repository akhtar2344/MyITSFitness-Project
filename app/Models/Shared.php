<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shared extends Model
{
    use HasFactory;

    protected $table = 'shared';

    protected $fillable = [
        'owner_id',        // FK -> people.id
        'submission_id',   // FK -> submissions.id
        'type',            // proof | attachment | other
        'path',
        'mime',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(People::class, 'owner_id');
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
