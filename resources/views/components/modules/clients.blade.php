@props(['clients' => [
    'Газпром Нефть',
    'Газпром Межрегионгаз',
    'Росстат',
    'Сколково',
    'Славнефть',
]])

<section class="home-clients" id="clients">
  <div class="clients-head">
    <div class="s-eye">Клиенты</div>
    <h2 class="section-title" style="text-align:center; max-width:400px; margin:0 auto 12px;">Работаем с лидерами рынка</h2>
    <p style="font-size:15px; color:var(--home-muted); text-align:center;">Корпорации, государственные структуры, промышленные предприятия</p>
  </div>
  <div class="clients-grid">
    @foreach($clients as $client)
    <div class="client-item"><div class="client-name">{{ $client }}</div></div>
    @endforeach
  </div>
</section>
