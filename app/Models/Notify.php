<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    use HasFactory;

    protected $table = 'notifies';

    protected $fillable = [
        'submission_id',
        'author_id',
        'channel',     // private | public | system
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function author()
    {
        return $this->belongsTo(People::class, 'author_id');
    }

    public function scopePrivate($q) { return $q->where('channel', 'private'); }
    public function scopeUnread($q)  { return $q->where('is_read', false); }
}
