<?php

namespace App\Models\Finance;

use App\HasCurrency;
use App\Models\Settings\Currency;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasCurrency;
    protected $guarded = [];

    public function student():BelongsTo{
        return $this->belongsTo(Student::class);
    }

    public function getDollarAmountAttribute():float{
        return $this->amount / $this->currency_rate;
    }

}
