<nav class="site-nav">
  <div class="nav-inner">
    <a href="{{ url('/') }}" class="nav-logo">
      <img src="{{ Storage::url('/images/logo.svg') }}" alt="МВН"
           onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
           style="width:173px; height:75px;">
      <div class="nav-logo-text" style="display:none">Мастерская<span>Василия Никольского</span></div>
    </a>

    <ul class="nav-links">
      @foreach($navItems as $item)
        @php
          $href = $item['href'];
          $isActive = is_array($item['pattern'])
            ? request()->routeIs(...$item['pattern'])
            : ($item['pattern'] && request()->routeIs($item['pattern']));
        @endphp
        @if(!empty($item['hasDropdown']))
          <li class="nav-item nav-item--dropdown{{ $isActive ? ' is-active' : '' }}">
            <a href="{{ $href }}">
              {{ $item['label'] }}
              <svg class="nav-chevron" width="10" height="7" viewBox="0 0 10 7" fill="none">
                <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
            <div class="nav-dropdown">
              @if(!empty($item['dropdownItems']))
                @foreach($item['dropdownItems'] as $dropItem)
                  <a href="{{ $dropItem['href'] }}"
                     class="nav-dropdown__link{{ request()->routeIs($dropItem['pattern']) ? ' nav-dropdown__link--active' : '' }}">
                    {{ $dropItem['label'] }}
                  </a>
                @endforeach
              @else
                @foreach($trainingCategories as $category)
                  @php
                    $categoryActive =
                      (request()->routeIs('training.category.show') && request()->route('slug') === $category->slug) ||
                      (request()->routeIs('training.show') && request()->route('categorySlug') === $category->slug);
                  @endphp
                  <a href="{{ route('training.category.show', $category->slug) }}"
                     class="nav-dropdown__link{{ $categoryActive ? ' nav-dropdown__link--active' : '' }}">
                    {{ $category->title }}
                  </a>
                @endforeach
              @endif
            </div>
          </li>
        @else
          <li class="{{ $isActive ? 'is-active' : '' }}">
            <a href="{{ $href }}">{{ $item['label'] }}</a>
          </li>
        @endif
      @endforeach
    </ul>

    @if($phone)<a href="tel:{{ preg_replace('/\D/', '', $phone) }}" class="nav-phone">{{ $phone }}</a>@endif
    <button class="btn-nav open-fancybox" data-form="zapros">Оставить запрос</button>
    <button class="nav-burger" aria-label="Открыть меню" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<div class="nav-drawer-overlay"></div>
<aside class="nav-drawer" aria-label="Мобильное меню">

  @if($nearestTraining)
    @php $nearestCategory = $nearestTraining->categories->first(); @endphp
    <div class="nav-drawer-event">
      <div class="nav-drawer-event__lbl">Ближайшее мероприятие</div>
      <div class="nav-drawer-event__title">{{ $nearestTraining->title }}</div>
      <div class="nav-drawer-event__meta">
        <span class="nav-drawer-event__date">{{ $nearestTraining->ev_day_month }}</span>
        @if($nearestTraining->ev_location)
          <span class="nav-drawer-event__sep">·</span>
          <span>{{ $nearestTraining->ev_location }}</span>
        @endif
      </div>
      @if($nearestCategory)
        <a href="{{ route('training.show', [$nearestCategory->slug, $nearestTraining->slug]) }}#reg"
           class="nav-drawer-event__btn">Записаться</a>
      @endif
    </div>
  @endif

  <nav class="nav-drawer-links">
    @foreach($navItems as $item)
      @php
        $href = $item['href'];
        $isActive = is_array($item['pattern'])
          ? request()->routeIs(...$item['pattern'])
          : ($item['pattern'] && request()->routeIs($item['pattern']));
      @endphp
      <a href="{{ $href }}" class="{{ $isActive ? 'is-active' : '' }}">
        {{ $item['label'] }}
      </a>
      @if(!empty($item['hasDropdown']))
        @if(!empty($item['dropdownItems']))
          @foreach($item['dropdownItems'] as $dropItem)
            <a href="{{ $dropItem['href'] }}"
               class="nav-drawer__sub{{ request()->routeIs($dropItem['pattern']) ? ' is-active' : '' }}">
              {{ $dropItem['label'] }}
            </a>
          @endforeach
        @else
          @foreach($trainingCategories as $category)
            @php
              $categoryActive =
                (request()->routeIs('training.category.show') && request()->route('slug') === $category->slug) ||
                (request()->routeIs('training.show') && request()->route('categorySlug') === $category->slug);
            @endphp
            <a href="{{ route('training.category.show', $category->slug) }}"
               class="nav-drawer__sub{{ $categoryActive ? ' is-active' : '' }}">
              {{ $category->title }}
            </a>
          @endforeach
        @endif
      @endif
    @endforeach
  </nav>

  <div class="nav-drawer-footer">
    @if($phone)<a href="tel:{{ preg_replace('/\D/', '', $phone) }}" class="nav-drawer-phone">{{ $phone }}</a>@endif
    <button class="btn-primary nav-drawer-cta open-fancybox" data-form="zapros">Оставить запрос</button>
  </div>
</aside>
