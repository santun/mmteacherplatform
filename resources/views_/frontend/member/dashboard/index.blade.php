@extends('frontend.layouts.default')
@section('title', __('Dashboard'))

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">
            <div class="row">

                <div class="col-md-3 mx-auto">
                    @include('frontend.member.partials.sidebar')
                </div>
                <div class="col-md-9 mx-auto">
                    <h1>{{ __('Dashboard') }}</h1>
                    <br>
                    <div class="row gap-1">
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isTeacherEducator())
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-gradient-primary border-0">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('Resources') }}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{ $totalResources }}</span>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="{{ route('member.resource.index') }}" class="text-nowrap text-white font-weight-600">{{ __('View') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-gradient-info border-0">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('Favourites') }}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{ $totalFavourites }}</span>

                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="{{ route('member.favourite.index') }}" class="text-nowrap text-white font-weight-600">{{ __('View') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-gradient-danger border-0">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('Notifications') }}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{ auth()->user()->notifications->count() }}</span>

                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="{{ route('member.notification.index') }}" class="text-nowrap text-white font-weight-600">{{ __('View') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-5">
                    <div class="card-body">
                    <h4 class="card-title">{{ __('Recent Notifications') }}</h4>
                    @foreach($notifications as $notification)
                    <div class="d-flex justify-content-between bd-highlight mb-3 border-bottom hover-shadow-2">
                        <div class="p-2">
                            <a class="text-muted" href="{{ route('member.notification.show', $notification->id) }}">
                            @if ($notification->read_at == null)
                            <strong>{{ $notification->data['title'] }}</strong>
                            @else
                            {{ $notification->data['title'] }}
                            @endif
                            </a>
                        </div>
                        <div class="p-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>

                    @endforeach
                    <a href="{{ route('member.notification.index') }}">{{ __('View more') }} <i class="ti-angle-right fs-10 ml-1"></i></a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
