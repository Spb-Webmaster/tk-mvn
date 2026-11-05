{{-- Цифры задаются в админке: Константы → Статистика (мероприятия).
     Значений по умолчанию здесь нет намеренно: иначе на страницах, где
     данные забыли передать, показывались бы зашитые в коде числа,
     расходящиеся с админкой. --}}
@props([
    'items' => [],
    'class' => '',
])

@php
    $items = collect($items)->filter(fn($i) => trim($i['num'] ?? '') !== '')->values();
@endphp

@if($items->isNotEmpty())
<div class="page-header-stats">
    @foreach($items as $item)
        <div>
            <div class="ph-stat-num">{{ $item['num'] }}</div>
            @if(!empty($item['label']))
            <div class="ph-stat-label {{ $class }}">{{ $item['label'] }}</div>
            @endif
        </div>
    @endforeach
</div>
@endif
