@props([
    'price' => null,
    'note'  => '',
])

@if($price)
<div class="sb-card">
    <div class="sb-ttl">Стоимость участия</div>
    <div class="price-rows">
        <div class="pr">
            <span class="pr-type">Участие</span>
            <span class="pr-val">{{ price($price) }} <span class="pr-cur">{{ config('site.currency', '₽') }}</span></span>
        </div>
    </div>
    @if($note)
    <div class="price-note">{{ $note }}</div>
    @endif
    <a href="#reg" class="btn-f">Записаться</a>
</div>
@endif
