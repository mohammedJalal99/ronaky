<?php

namespace App;

use Filament\Forms\Components\Select;
use Filament\Forms\Form;

trait HasCreateAnother
{
    //

    public static  function selectField($name,$resource):Select{
        if($resource::canCreate()){
            return Select::make($name)
                    ->createOptionForm(fn (Form $form)=>$resource::form($form))    ->searchable()
                ->preload();
        }
        return Select::make($name)    ->searchable()
            ->preload();
    }
}
