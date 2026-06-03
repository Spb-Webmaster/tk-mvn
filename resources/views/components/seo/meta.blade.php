@props([
    'title'       => '',
    'description' => '',
    'keywords'    => '',
])
@if(isset($seo_title))
     @php
         $title = $seo_title;
     @endphp
@endif
@if(isset($seo_description))
    @php
        $description = $seo_description;
    @endphp
@endif
@if(isset($seo_keywords))
    @php
        $title = $seo_title;
    @endphp
@endif
@section('title', $title ? html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null)
@section('description', $description ? html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null)
@section('keywords', $keywords ? html_entity_decode($keywords, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null)
