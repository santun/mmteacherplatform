@extends('frontend.layouts.default')

@section('title', __('Home'))

@section('header')
<div class="section mb-0 pb-0">
    <div class="elibrary-slider" data-provide="slider" data-fade="true" data-autoplay-speed="10000" data-slides-to-show="1" data-autoplay="1" data-arrows="true" data-slick='{"lazyLoad": "progressive"}'>
        @foreach($slides as $slide)
            @include('frontend.layouts.partials.slide', $slide)
        @endforeach
    </div>
</div>
@endsection

@section('content')
<main class="main-content">

    @if (session('status'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>{{ __('Success') }}</h4>
        {{ session('status') }}
        </div>
    @endif

    <section class="section pt-7 pb-0 bg-gray">
        <div class="container">
            <header class="section-header text-center">
                <h1>{{ __('News & Announcements') }}</h1>
            </header>

            <div class="row gap-y elibrary-slider" data-provide="slider" data-arrows="true" data-slick='{"slidesToShow": 3, "lazyLoad": "ondemand", "slidesToScroll": 4}'>
                @if (isset($articles))
                @foreach ($articles as $article)
                <div class="col">
                    @include('frontend.article.partials.card', ['post' => $article])
                </div>
                @endforeach
                @endif
            </div>

            <div class="text-center mt-7">
                <a class="btn btn-outline-primary px-7" href="{{ route('article.index') }}">{{ __('View all') }}</a>
            </div>
        </div>
    </section>

    <section class="section mt-0 bt-7 bg-gray">
        <div class="container">
            <header class="section-header text-center">
                <h2>{{ __('Featured Resources') }}</h2>
            </header>

            <div class="row gap-y">
                @if (isset($resources)) @foreach ($resources as $resource)
                <div class="col-lg-4">
                @include('frontend.resource.partials.card', ['post' => $resource])
                </div>
                @endforeach @endif
            </div>

            <div class="text-center mt-7">
                <a class="btn btn-outline-primary px-7" href="{{ route('elibrary.index') }}">{{ __('View all') }}</a>
            </div>
        </div>
    </section>
</main>
@endsection

@section('css')
@parent
    <style>
    @foreach($slides as $slide)
    .slide-{{ $slide->id }} {
        background-image: url({{ asset($slide->getFirstMedia('slides')->getUrl()) }})
    }
    @endforeach
    </style>
@endsection
