@extends('frontend.layouts.default')
@section('title', __('Courses'))

@section('header')
    <div class="section mb-0 pb-0">
        <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
            <div class="container text-center">
                <h2>{{__('My Courses') }}</h2>
            </div>
        </header>
    </div>
@endsection

@section('breadcrumbs')
    <div class="bg-gray">
        <div class="container">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.my-courses') }}">{{ __('My Courses') }}</a></li>
                        <li class="breadcrumb-item active">{{ $course->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-5">{{ $currentLecture->lecture_title }}</h4>
                @if($currentLecture->getMedia('lecture_attached_file')->first()->mime_type == 'application/pdf' || $currentLecture->getMedia('lecture_attached_file')->first()->mime_type == 'application/vnd.oasis.opendocument.presentation' )
                    @include('frontend.courses.partials.pdf-lecture')
                @elseif($currentLecture->getMedia('lecture_attached_file')->first()->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || $currentLecture->getMedia('lecture_attached_file')->first()->mime_type == "application/vnd.ms-powerpoint")
                    @include('frontend.courses.partials.ppt-lecture')
                @elseif($currentLecture->getMedia('lecture_attached_file')->first()->mime_type == 'video/mp4')
                    @include('frontend.courses.partials.video-lecture')
                @elseif($currentLecture->getMedia('lecture_attached_file')->first()->mime_type == 'audio/mpeg')
                    @include('frontend.courses.partials.audio-lecture')
                @else
                    @include('frontend.courses.partials.ppt-lecture')
                @endif

                <div class="text-right mr-5 mt-3">
                    <a href="{{ isset($previousLecture) ? route('courses.learn-course', [$course, $previousLecture]) : '#' }}"
                       class="btn btn-outline-primary btn-sm p-3 mb-3 {{ isset($previousLecture) ? '' : 'disabled' }}"> &lt;Previous </a>
                    @if(count($currentLecture->quizzes) <= 0)
                        <a href="{{ isset($nextLecture) ? route('courses.learn-course', [$course, $nextLecture]) : '#' }}"
                           class="btn btn-outline-primary btn-sm p-3 mb-3 {{ isset($nextLecture) ? '' : 'disabled' }}">Next></a>
                    @else
                        <a href="{{ route('quiz.show', $currentLecture->quizzes->first()->id) }}" class="btn btn-outline-primary btn-sm p-3 mb-3">Go To Quiz></a>
                    @endif
                </div>
            </div>
            <div class="col-md-4 border border-gray p-5">
                @include('frontend.courses.partials.course-lecture')
            </div>
        </div>
        @if($currentLecture->description)
            <div class="row">
                <div class="col-md-12">
                    <h4>Description</h4>
                    <p>{!! $currentLecture->description !!}</p>
                </div>
            </div>
        @endif
        @if($currentLecture->resource_link)
            <div class="row">
                <div class="col-md-12">
                    <h4>Resource</h4>
                    <p><a href="{{$currentLecture->resource_link}}" target="_blank">{{$currentLecture->resource_link}}</a></p>
                </div>
            </div>
        @endif
        <div class="row mt-5 mb-5">
            @include('frontend.courses.assignments.index')
        </div>
    </div>
@endsection

@section('script')
    @parent
@endsection
