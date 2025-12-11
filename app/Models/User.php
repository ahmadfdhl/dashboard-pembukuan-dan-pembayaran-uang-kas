<?php

namespace App\Models;
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nisn',
        'role',
        'class_id',
        'phone',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isTreasurer()
    {
        return $this->role === 'treasurer';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeTreasurers($query)
    {
        return $query->where('role', 'treasurer');
    }

    // Helper untuk mendapatkan role dalam bahasa Indonesia
    public function getRoleNameAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'teacher' => 'Guru',
            'treasurer' => 'Bendahara',
            'student' => 'Siswa'
        ];
        
        return $roles[$this->role] ?? $this->role;
    }
}