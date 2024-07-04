<?php

namespace App\Providers;

use App\Models\Settings\Currency;
use App\Policies\Settings\CurrencyPolicy;

class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{

    protected $policies = [
        Currency::class=>CurrencyPolicy::class
    ];


}
