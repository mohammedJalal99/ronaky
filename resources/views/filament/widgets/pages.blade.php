@php
    $columns = $this->getColumns();
@endphp
<x-filament-widgets::widget class="fi-wi-stats-overview">
    <div
        @if ($pollingInterval = $this->getPollingInterval())
            wire:poll.{{ $pollingInterval }}
        @endif
        @class([
            'fi-wi-stats-overview-stats-ctn grid gap-6',
            'md:grid-cols-1' => $columns === 1,
            'md:grid-cols-2' => $columns === 2,
            'md:grid-cols-3' => $columns === 3,
            'md:grid-cols-2 xl:grid-cols-4' => $columns === 4,
        ])
    >
        @foreach ($this->getCachedStats() as $key => $stat)
            <style>
                .custom-stat{{$key}} .fi-wi-stats-overview-stat{
                    background: #{{rand(0,7)}}{{rand(0,7)}}{{rand(0,7)}}d{{rand(4,8)}}0;
                    display: block;
                    width: 100%;
                }
                .custom-stat{{$key}} .fi-wi-stats-overview-stat *{
                    color: #fff !important;
                }
            </style>
            <div class="custom-stat{{$key}}">
                {{ $stat }}
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
