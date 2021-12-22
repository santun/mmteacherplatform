@extends('frontend.layouts.default')
@section('title', $post->name)

@section('header')
<div class="section mb-0 pb-0">
    <section class="section pt-7 pb-7 bg-gray overflow-hidden">
        <div class="container">

            @include('frontend.partials.notifications')


            <div class="row  justify-content-md-center">
                <div class="col-md-6">
                    <h1>{{ $post->name }} <small>({{ '@'.$post->username }})</small></h1>
                    <div>{{ __('Education College') }}: {{ $post->college->title ?? '' }}</div>
                    <div>{{ __('Subjects') }}:
                    @if ($subjects = $post->subjects)
                        @foreach($subjects as $subject)
                        {{ $subject->title }}@if (!$loop->last), @endif
                        @endforeach
                    @endif
                    </div>
                    <div>{{ __('User Type') }}: {{ Illuminate\Support\Str::title(str_replace('_', ' ', $post->user_type)) }}</div>
                    <div>{{ __('Member Since') }}: {{ $post->created_at->format('M d, Y') }}</div>
                </div>

                <div class="col-md-3">
                    @if($profileThumbnail = optional($post->getMedia('profile')->first())->getUrl('medium'))
                    <div class="form-group col-xs-12">
                        <img class="rounded" src="{{ $profileThumbnail }}">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('content')
<main class="main-content">

    <section class="section pt-7 pb-7 bg-white overflow-hidden">
        <div class="container">

            @include('frontend.partials.notifications')

            <h2>{{ __('Resources') }}</h2>
            <div class="row gap-y gap-2">
                @foreach ($resources as $resource)
                <div class="col-md-4">
                @include('frontend.resource.partials.card', ['post' => $resource])
                </div>
                @endforeach

            </div>
            <div>
                {{ $resources->links() }}
            </div>
        </div>
    </section>
</main>
@endsection
