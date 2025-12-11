<?php
// app/Models/Classes.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    
    protected $fillable = [
        'name',
        'grade',
        'major',
        'teacher_id',
        'treasurer_id'
    ];

    protected $appends = ['full_name'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function treasurer()
    {
        return $this->belongsTo(User::class, 'treasurer_id');
    }

    public function students()
    {
        return $this->hasMany(User::class, 'class_id')->where('role', 'student');
    }

    public function activeStudents()
    {
        return $this->hasMany(User::class, 'class_id')
                    ->where('role', 'student')
                    ->where('is_active', true);
    }

    // Format: XII IPA 1
    public function getFullNameAttribute()
    {
        $parts = [];
        if ($this->grade) {
            $parts[] = $this->grade;
        }
        if ($this->major) {
            $parts[] = $this->major;
        }
        if ($this->name) {
            $parts[] = $this->name;
        }
        
        return implode(' ', $parts);
    }

    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

    public function getActiveStudentCountAttribute()
    {
        return $this->activeStudents()->count();
    }
}