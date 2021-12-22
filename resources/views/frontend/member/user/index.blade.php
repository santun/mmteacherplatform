@extends('frontend.layouts.default')
@section('title', __('Manage Users'))

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
                    <h1>{{ __('Manage Users') }}</h1>
                    <div class="card">
                        <header class="card-header">
                            <form action="{{ route('member.user.index') }}" method="get">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="lookup lookup-right d-none d-lg-block">
                                        <input name="search" class="form-control" placeholder="Search" type="text" value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    {!! Form::select('approved', ['' => '-Approval Status -'] + $approvalStatus, request('approved'),
                                    ['class' => 'form-control']) !!}
                                </div>

                                <div class="col-md-4">
                                    <button class="btn btn-primary">{{ __('Search') }}</button>
                                    <a href="{{ route('member.user.index') }}" class="btn">{{ __('Reset') }}</a>
                                </div>
                            </div>
                            </form>
                        </header>
                    </div>
                    <div class="row">

                        <div class="container">

                            @if (isset($posts) && ! $posts->isEmpty())
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-12">
                                    <ul class="list-group">
                                    @foreach ($posts as $post)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                            <div class="flex-column">
                                                <a title="{{ __('Click here to view Profile') }}" class="text-muted" href="{{ route('profile.show', $post->username) }}">
                                                    <h4>{{ $post->name }} <small>({{ '@'.$post->username }})</small></h4>
                                                </a>

                                                <p><small>
                                                    {{ __('Registered') }} <span title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</span>
                                                    {{ __('from') }} {{ $post->college->title ?? '' }}
                                                    </small></p>
                                                    <div>
                                                        {{ __('Email') }} : {{ $post->email }}
                                                        <br>
                                                        {{ __('Mobile No.') }} : {{ $post->mobile_no }}
                                                        <br>
                                                        {{ __('Ministry Staff') }} : {{ $post->getStaffType() }}
                                                        <br>
                                                        {{ __('User Type') }} : {{ $post->getType() }}
                                                        <br>
                                                        {{ __('Subjects') }} :
                                                        @if ($subjects = $post->subjects)
                                                            @foreach($subjects as $subject)
                                                            {{ $subject->title }}@if (!$loop->last), @endif
                                                            @endforeach
                                                        @endif
                                                        <br>
                                                        {{ __('Years')}} :

                                                        @if ($years = $post->getYears())
                                                        @foreach ($years as $year)
                                                            {{ $year->title }}@if (!$loop->last), @endif
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                @if ($post->approved == App\User::APPROVAL_STATUS_APPROVED)
                                                <span class="badge badge-success badge-pill">
                                                    {{ $post->getApprovalStatus() }}
                                                </span>
                                                <small>
                                                </small>
                                                <div class="mt-4">
                                                    <a href="{{ route('member.user.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                                    {{ __('Undo') }}
                                                    </a>
                                                </div>
                                                @elseif ($post->approved == App\User::APPROVAL_STATUS_BLOCKED)
                                                <span class="badge badge-danger badge-pill">
                                                    {{ $post->getApprovalStatus() }}
                                                </span>
                                                <small>
                                                </small>
                                                <div class="mt-4">
                                                    <a href="{{ route('member.user.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                                    {{ __('Undo') }}
                                                    </a>
                                                </div>
                                                @else
                                                <span class="badge badge-secondary badge-pill">
                                                    {{ $post->getApprovalStatus() }}
                                                </span>
                                                <div class="mt-4">
                                                <a href="{{ route('member.user.update-status', [$post->id, 'approve']) }}" class="btn btn-outline-success">
                                                {{ __('Approve') }}
                                                </a>
                                                <a href="{{ route('member.user.update-status', [$post->id, 'block']) }}" class="btn btn-outline-danger">
                                                {{ __('Block') }}
                                                </a>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="image-parent text-center">
                                                <img class="img-fluid" src="{{ asset($post->getThumbnailPath()) }}" alt="{{ $post->name }}" >
                                                <br>
                                                <a href="{{ route('member.user.edit', $post->id) }}" class="mt-3 btn btn-primary">{{ __('Edit') }}</a>
                                            </div>
                                        </div>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div>
                                {{ $posts->links() }}
                            </div>
                            @else
                            <div class="text-info">{{ __('There are no approval requests.') }}</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection
