<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentNote extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
