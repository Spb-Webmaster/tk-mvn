@extends('layouts.layout')
<x-seo.meta :page="$page" />
@section('content')

<div class="content_page pad_b0_important">
    <div class="container">
        <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
    </div>

<x-modules.programs
    class="home-programs-no-padding-top-43"
    heading-tag="h1"
/>
</div>
@endsection
