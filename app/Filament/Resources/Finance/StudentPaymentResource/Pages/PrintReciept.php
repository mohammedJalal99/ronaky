<?php

namespace App\Filament\Resources\Finance\StudentPaymentResource\Pages;

use App\Filament\Resources\Finance\StudentPaymentResource;
use Filament\Resources\Pages\Page;

class PrintReciept extends Page
{
    protected static string $resource = StudentPaymentResource::class;
    public $data;
    public function mount($record){
        $this->data = self::$resource::getModel()::findOrFail($record);
    }

    protected static string $view = 'filament.resources.finance.student-payment-resource.pages.print-reciept';
}
