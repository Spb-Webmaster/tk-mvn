@props([
    'priceIndividual' => null,
    'priceLegal'      => null,
    'note'            => '',
])

@if($priceIndividual || $priceLegal)
<div class="sb-card">
    <div class="sb-ttl">Стоимость участия</div>
    <div class="price-rows">
        @if($priceIndividual)
        <div class="pr">
            <span class="pr-type">Физ. лицо</span>
            <span class="pr-val">{{ price($priceIndividual) }} <span class="pr-cur">{{ config('site.currency', '₽') }}</span></span>
        </div>
        @endif
        @if($priceLegal)
        <div class="pr">
            <span class="pr-type">Юр. лицо</span>
            <span class="pr-val">{{ price($priceLegal) }} <span class="pr-cur">{{ config('site.currency', '₽') }}</span></span>
        </div>
        @endif
    </div>
    @if($note)
    <div class="price-note">{{ $note }}</div>
    @endif
    <a href="#reg" class="btn-f">Записаться</a>
    <a href="#reg" class="btn-o" onclick="setTimeout(()=>swTab('yur'),60)">Для юр. лиц</a>
</div>
@endif
