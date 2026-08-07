@if($trainings->isNotEmpty())
<div class="sb-card">
    <div class="sb-nav-ttl pad_b19">Программа «Программа подготовки переговорщиков для бизнеса»</div>
    <ul class="sb-nav">
        @foreach($trainings as $training)
            @php
                $catSlug = $training->categories->first()?->slug ?? 'master-kommunikatsij';
            @endphp
            <li>
                <a href="{{ route('training.show', [$catSlug, $training->slug]) }}">
                    {{ $training->title }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endif
