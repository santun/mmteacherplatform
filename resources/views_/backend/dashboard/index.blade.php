@extends('backend.layouts.default')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="flexbox flex-justified">
            <a href="{{ route('admin.resource.index') }}">
            <div class="card card-body">
                <h6>
                    <span class="text-uppercase">{{__('Resources')}}</span>
                </h6>
                <br>
                <p class="fs-28 fw-100">{{ App\Models\Resource::count() }}</p>
            </div>
            </a>
        </div>

        <div class="flexbox flex-justified">
            <a href="{{ route('admin.college.index') }}">
            <div class="card card-body">
                <h6>
                    <span class="text-uppercase">{{__('Education Colleges')}}</span>
                </h6>
                <br>
                <p class="fs-28 fw-100">{{ App\Models\College::count() }}</p>
            </div>
            </a>
        </div>

        <div class="flexbox flex-justified">
            <a href="{{ route('admin.page.index') }}">
            <div class="card card-body">
                <h6>
                  <span class="text-uppercase">{{__('Pages')}}</span>
                </h6>
                <br>
                <p class="fs-28 fw-100">{{ App\Models\Page::count() }}</p>
            </div>
            </a>
        </div>

        <div class="flexbox flex-justified">
            <a href="{{ route('admin.article.index') }}">
            <div class="card card-body">
                <h6>
                    <span class="text-uppercase">{{__('Articles')}}</span>
                </h6>
                <br>
                <p class="fs-28 fw-100">{{ App\Models\Article::count() }}</p>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <h5 class="card-title"><strong>Articles</strong></h5>

        <div class="media-list media-list-sm media-list-hover media-list-divided scrollable" style="height: 335px">
            @foreach ($articles as $item)
            <div class="media media-single">
                <!--<a href="{{ route('admin.article.edit', $item->id) }}">
                    @if ($img_url = $item->getThumbnailPath())
                    <img src="{{ $img_url }}" alt="{{ $item->title }}">
                    @else
                    -
                    @endif
                </a>-->

                <div class="media-body">
                  <h6>
                      <a href="{{ route('admin.article.edit', $item->id) }}">{{ $item->title }}</a>
                    </h6>
                </div>
              </div>
            @endforeach
        </div>

            <div class="text-center bt-1 border-light p-2">
            <a class="text-default text-uppercase d-block fs-10 fw-500 ls-1" href="{{ route('admin.article.index') }}">View All</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <h5 class="card-title"><strong>{{ __('Resource') }}</strong></h5>

        <div class="media-list media-list-sm media-list-hover media-list-divided scrollable" style="height: 335px">
                @foreach ($resources as $item)
                <div class="media media-single">
                    <a href="{{ route('admin.resource.edit', $item->id) }}">
                        {{ $item->title }}
                    </a>

                    <!--<div class="media-body">
                      <h6>
                          <a href="{{ route('admin.resource.edit', $item->id) }}">{{ $item->author ?? '' }} - {{ $item->arrival->name ?? '' }}</a>
                        </h6>
                    </div>-->
                  </div>
                @endforeach
        </div>

            <div class="text-center bt-1 border-light p-2">
            <a class="text-default text-uppercase d-block fs-10 fw-500 ls-1" href="{{ route('admin.resource.index') }}">View All</a>
            </div>
        </div>
    </div>
</div>
@endsection
