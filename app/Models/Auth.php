<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    use HasFactory;

    protected $table = 'auths';

    protected $fillable = [
        'people_id',
        'email',
        'password',
        'role',           // student | lecturer | admin
        'last_login_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function people()
    {
        return $this->belongsTo(People::class);
    }

    public function scopeStudents($q) { return $q->where('role', 'student'); }
    public function scopeLecturers($q){ return $q->where('role', 'lecturer'); }
}
