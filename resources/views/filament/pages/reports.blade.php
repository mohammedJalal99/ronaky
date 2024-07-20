<x-filament-panels::page>
           <div class="grid grid-cols-1 lg:grid-cols-3">
               @foreach($this->getForms() as $form)
                   {{ $this->{$form} }}
               @endforeach
           </div>

</x-filament-panels::page>
