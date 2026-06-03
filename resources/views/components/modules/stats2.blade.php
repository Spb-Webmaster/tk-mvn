@props([

    'items' => [
    ['num' => '500+', 'label' => 'мероприятий'],
    ['num' => '80+',  'label' => 'компаний'],
    ['num' => '16',   'label' => 'лет опыта'],
],
'class' => ''
        ])

<div class="page-header-stats">
    @foreach($items as $item)
        <div>
            <div class="ph-stat-num">{{ $item['num'] }}</div>
            <div class="ph-stat-label {{ $class }}">{{ $item['label'] }}</div>
        </div>
    @endforeach
</div>
