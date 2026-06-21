@props(['events' => collect()])

@php
    $months = ['','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
@endphp

<section class="home-events home-section" id="events">
  <div class="container">
    <div class="section-head">
      <div class="s-eye">Ближайшие мероприятия</div>
      <h2 class="section-title">Открытые программы</h2>
      <p class="section-lead">Присоединяйтесь к тренингам в открытом формате — для частных специалистов и корпоративных участников.</p>
    </div>
    <div class="events-grid">
      @foreach($events as $event)
      <div class="event-card card-hover">
        <div class="event-date">
          <div class="event-day">{{ $event->ev_date_from->day }}</div>
          @if($event->ev_date_to && !$event->ev_date_from->isSameDay($event->ev_date_to) && $event->ev_date_from->month === $event->ev_date_to->month)
          <div class="event-sep">—</div>
          <div class="event-day2">{{ $event->ev_date_to->day }}</div>
          @endif
          <div class="event-month">{{ $months[$event->ev_date_from->month] }}</div>
          <div class="event-year">{{ $event->ev_date_from->year }}</div>
        </div>
        <div class="event-info">
          <div class="event-tag">{{ $event->categories->first()?->title ?? '' }}</div>
          <div class="event-title">{{ $event->title }}</div>
          <div class="event-desc">{{ strip_tags($event->short_desc) }}</div>
          <a href="{{ route('training.show', [$event->categories->first()?->slug, $event->slug]) }}" class="event-link">Подробнее →</a>
        </div>
      </div>
      @endforeach
    </div>
    <div class="events-promo">
      <div>
        <div class="events-promo-title">Программа «Мастер коммуникаций»</div>
        <div class="events-promo-desc">Системный курс переговорной практики. Набор открыт.</div>
      </div>
      <button type="button" class="btn-primary open-fancybox" data-form="zapros" style="flex-shrink:0;">Узнать подробнее</button>
    </div>
  </div>
</section>
