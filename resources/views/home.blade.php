@extends('layouts.layout')
<x-seo.meta
    title="{{ $home['metatitle'] }}"
    description="{{ $home['description'] }}"
    keywords="{{ $home['keywords'] }}"
/>
@section('content')

<div class="home-page">
2222222222222222222222222222222222
<!-- HERO -->
<x-modules.hero
    :eyebrow="$home['hero_eyebrow'] ?? ''"
    :title="$home['hero_title'] ?? ''"
    :subtitle="$home['hero_subtitle'] ?? ''"
    :desc="$home['hero_desc'] ?? ''"
/>

<!-- STATS -->
<x-modules.stats :items="$constants['stats'] ?? []" />

<!-- EVENTS -->
<x-modules.events :events="$events" />

<!-- PROGRAMS -->
<x-modules.programs
    :eyebrow="$home['programs_eyebrow'] ?? ''"
    :title="$home['programs_title'] ?? ''"
    :lead="$home['programs_lead'] ?? ''"
/>

<!-- ABOUT -->
<x-modules.about
    :title="$home['about_title'] ?? ''"
    :body="$home['about_body'] ?? ''"
/>

<!-- GALLERY -->
<x-modules.gallery />

<!-- CLIENTS -->
<x-modules.clients />

<!-- DESC -->
<x-modules.desc :content="$home['desc'] ?? ''" />

<!-- CTA / CONTACT -->
<x-modules.cta />

</div><!-- .home-page -->
@endsection
