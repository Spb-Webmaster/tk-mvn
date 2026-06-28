@props([
    'title' => '',
    'body'  => '',
])

<section class="home-about" id="about">
    <div class="about-inner">
        <div class="about-photo">
            <a href="https://tkmvn-spb.ru/youtube">
                <img src="{{ asset('storage/images/home/04_4.jpg') }}" alt="Василий Никольский">
            </a>
        </div>
        <div class="about-content">
            <div class="section-eyebrow eyebrow">О тренере</div>
            @if(!empty($title))
                <h2 class="section-title">{!! $title !!}</h2>
            @endif
            @if(!empty($body))
                <div class="about-body">{!! $body !!}</div>
            @endif
            <div class="about-facts">
                <div class="about-fact">
                    <div class="about-fact-num">500+</div>
                    <div class="about-fact-label">проведённых тренингов</div>
                </div>
                <div class="about-fact">
                    <div class="about-fact-num">80+</div>
                    <div class="about-fact-label">компаний в портфеле</div>
                </div>
                <div class="about-fact">
                    <div class="about-fact-num">16</div>
                    <div class="about-fact-label">лет на рынке</div>
                </div>
                <div class="about-fact">
                    <div class="about-fact-num">12</div>
                    <div class="about-fact-label">человек в группе</div>
                </div>
            </div>
            <button class="btn-primary open-fancybox" data-form="zapros">Оставить запрос</button>
        </div>
    </div>
</section>
