<?php

namespace App\Models\Student;

use App\Models\Finance\StudentPayment;
use App\Models\Settings\Currency;
use App\Models\Settings\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];



    public function driver():BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    public function father():BelongsTo
    {
        return  $this->belongsTo(Father::class);
    }

    public function mother():BelongsTo
    {
        return  $this->belongsTo(Mother::class);
    }

    public function stage():BelongsTo
    {
        return  $this->belongsTo(Stage::class);
    }

    public function currency():BelongsTo
    {
        return  $this->belongsTo(Currency::class);
    }

    public function studentNotes():HasMany
    {
        return $this->hasMany(StudentNote::class);
    }

    public function studentPayment():HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }
    public function getFullNameAttribute():string
    {
        return $this->name . ' ' . $this->father->name;
    }
    public function getDueAmountAttribute():float
    {
         return ($this->amount) - ($this->studentPayment()->get()->sum('dollar_amount'));
    }


}
