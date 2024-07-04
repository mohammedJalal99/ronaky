<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mother extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    public function student():HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function maleStudent():HasMany
    {
        return $this->hasMany(Student::class)->where('gender','male');
    }
    public function femaleStudent():HasMany
    {
        return $this->hasMany(Student::class)->where('gender','female');
    }
    public function getStudentCountAttribute(): int{
        return $this->student->count();
    }
    public function getMaleStudentCountAttribute(): int{
        return $this->maleStudent->count();
    }
    public function getFemaleStudentCountAttribute(): int{
        return $this->femaleStudent->count();
    }
}
