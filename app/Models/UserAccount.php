<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserAccount extends Model
{
    use HasUuids;

    protected $table = 'user_account';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'password_hash',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'recipient_user_id');
    }
}
