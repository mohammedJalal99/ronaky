<?php

namespace Database\Seeders\Settings;

use App\Models\Settings\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create([
           'name'=>"دۆلاری ئەمریکی" ,
            'symbol'=>'$',
            'decimal_places'=>2,
            'rate'=>1
        ]);
        Currency::create([
            'name'=>"دیناری عێراقی" ,
            'symbol'=>'IQD',
            'decimal_places'=>0,
            'rate'=>1475
        ]);
    }
}
