<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function expenses():HasMany{
        return $this->hasMany(Expense::class);
    }

    public function getTotalAmountAttribute():float
    {
        return $this->expenses()->get()->sum('total_dollar');
    }


}
