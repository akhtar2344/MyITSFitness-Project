<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Activity extends Model
{
    use HasUuids;

    protected $table = 'activity';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'name', // Only category name, nothing else
    ];

    // Relationships
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'activity_id');
    }
}
