<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-copy">
      MBH group · Мастерская Василия Никольского<br>
      © 2010–2026 · Санкт-Петербург
    </div>
    <div class="footer-links">
      <a href="{{ route('schedule') }}">Расписание</a>
      <a href="{{ route('response') }}">Отзывы</a>
      <a href="{{ route('photo') }}">Фото</a>
      <a href="{{ route('video') }}">Видео</a>
      <a href="{{ route('privacy') }}">Политика конфиденциальности</a>
    </div>
    @if(!empty($constants['contact_phone']))
    <a href="tel:{{ preg_replace('/\D/', '', $constants['contact_phone']) }}" class="footer-phone">{{ $constants['contact_phone'] }}</a>
    @endif
  </div>
</footer>
