<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANTS
    |--------------------------------------------------------------------------
    */
    public const STATUS_PENDING       = 'pending';
    public const STATUS_ACCEPTED      = 'accepted';
    public const STATUS_REJECTED      = 'rejected';
    public const STATUS_NEED_REVISION = 'need_revision';

    protected $table = 'submissions';

    protected $fillable = [
        'student_id',
        'lecturer_id',
        'activity',
        'location',
        'duration_minutes',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at'     => 'datetime',
        'reviewed_at'      => 'datetime',
        'duration_minutes' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(People::class, 'student_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(People::class, 'lecturer_id');
    }

    public function files()
    {
        return $this->hasMany(Shared::class);
    }

    public function notifies()
    {
        return $this->hasMany(Notify::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getDurationHumanAttribute(): string
    {
        $m = (int) ($this->duration_minutes ?? 0);
        if ($m <= 0) return '0m';

        $h = intdiv($m, 60);
        $r = $m % 60;

        if ($h && $r) return "{$h}h {$r}m";
        if ($h) return "{$h}h";
        return "{$r}m";
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($q)
    {
        return $q->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($q)
    {
        return $q->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeRejected($q)
    {
        return $q->where('status', self::STATUS_REJECTED);
    }

    public function scopeNeedRevision($q)
    {
        return $q->where('status', self::STATUS_NEED_REVISION);
    }
}
