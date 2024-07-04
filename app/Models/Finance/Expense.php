<?php

namespace App\Models\Finance;

use App\HasCurrency;
use App\Models\Settings\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasCurrency;
    protected $guarded = [];

    public function expenseType():BelongsTo{
        return $this->belongsTo(ExpenseType::class);
    }

    public function getTotalDollarAttribute():float{
        return $this->amount /( $this->currency_rate>0 ? $this->currency_rate:1);
    }


}
