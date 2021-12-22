@extends('frontend.layouts.default')
@section('title', __('Approval Requests'))

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
                    <h1>{{ __('Approval Requests') }}</h1>
                    @if (isset($posts) && ! $posts->isEmpty())
                    <div class="row">

                        @foreach ($posts as $post)
                        <div class="col-sm-4">
                        @if ($post->approval_status != App\Models\Resource::APPROVAL_STATUS_PENDING)
                        <div class="card border border-secondary mb-4" style="opacity: 0.7">
                            <img class="card-img-top" src="{{ $post->resource->getThumbnailPath() }}" alt="{{ $post->resource->title ?? '' }}">
                            <div class="card-body">
                                <h5 class="card-title text-muted">
                                    <a class="text-muted" href="{{ route('resource.show', $post->resource->slug) }}">{{ $post->resource->title ?? '' }}
                                    </a>
                                    </h5>
                                <div class="card-text text-muted">{{ __('From') }}:
                                    <a class="text-muted" href="{{ route('profile.show', $post->user->username) }}">{{ $post->user->name ?? '' }}</a>
                                    <div>{{ __('Submitted At') }}: {{ $post->created_at }}</div>
                                </div>
                            </div>
                            <div class="card-body text-muted">
                                {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }} at {{ $post->updated_at }}
                            </div>
                        </div>
                        @else
                        <div class="card border border-primary mb-4">
                            <img class="card-img-top" src="{{ $post->resource->getThumbnailPath() }}" alt="{{ $post->resource->title ?? '' }}">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('resource.show', $post->resource->slug) }}">{{ $post->resource->title ?? '' }}
                                                            </a>
                                </h5>
                                <div class="card-text text-muted">{{ __('From') }}:
                                    <a href="{{ route('profile.show', $post->user->username) }}">{{ $post->user->name ?? '' }}</a></div>
                            </div>
                            <div class="card-body text-muted">
                                <a href="{{ route('member.approval-request.update-status', [$post->id, 'approve']) }}" class="card-link mr-3 text-success">
                                {{ __('Approve') }}
                                </a>
                                <a href="{{ route('member.approval-request.update-status', [$post->id, 'reject']) }}" class="card-link text-danger">
                                {{ __('Reject') }}
                                </a>
                            </div>
                        </div>
                        @endif
                        </div>
                        @endforeach
                    </div>
                    <div>
                        {{ $posts->links() }}
                    </div>
                        @else
                        <div class="text-info">{{ __('There are no favourite resources.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
