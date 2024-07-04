<?php

namespace App;

use App\Models\Settings\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCurrency
{

    protected static function bootHasCurrency()
    {

        self::creating(function ($model) {
                $model->currency_rate = Currency::find($model->currency_id)?->rate??1;
        });
        self::updating(function ($model) {
            $model->currency_rate = Currency::find($model->currency_id)?->rate??1;
        });
    }
    public function currency():BelongsTo{
        return $this->belongsTo(Currency::class);
    }
}
