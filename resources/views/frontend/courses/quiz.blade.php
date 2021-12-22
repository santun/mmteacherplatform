@extends('frontend.layouts.default')
@section('title', __('Courses'))

@section('css')
    @parent
    <style>
        
    </style>
@stop

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
                <div class="row"><h4 class="mb-5">{!! $currentQuiz->title !!}</h4></div>
                @if($currentQuiz->type == 'true_false')
                    @include('frontend.courses.partials.truefalse-question')
                @elseif($currentQuiz->type == 'multiple_choice')
                    @include('frontend.courses.partials.multiple-choice-question')
                @elseif($currentQuiz->type == 'matching')
                    @include('frontend.courses.partials.matching-question')
                @elseif($currentQuiz->type == 'blank')
                    @include('frontend.courses.partials.blank-question')
                @elseif($currentQuiz->type == 'short_question')
                    @include('frontend.courses.partials.short-question')
                @else
                    @include('frontend.courses.partials.rearrange-question')
                @endif

                <div class="text-right mr-5 mt-3">
                    <a
                        href="{{ isset($previousQuiz) ? route('quiz.show', $previousQuiz->id) : route('courses.learn-course', [$course, $currentLecture]) }}"
                        class="btn btn-outline-primary btn-sm p-3 mb-3"
                    >
                        &lt;Previous
                    </a>
                    @if(isset($nextQuiz))
                        <a
                            href="{{ route('quiz.show', $nextQuiz->id) }}"
                            class="btn btn-outline-primary btn-sm p-3 mb-3"
                        >
                            Next>
                        </a>
                    @else
                        @if(isset($nextLecture))
                            <a
                                href="{{ route('courses.learn-course',[$course, $nextLecture]) }}"
                                class="btn btn-outline-primary btn-sm p-3 mb-3"
                            >
                                Next>
                            </a>
                        @else
                            <a href="#" class="btn btn-outline-primary btn-sm p-3 mb-3 disabled">Next></a>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-md-4 border border-gray p-5">
                @include('frontend.courses.partials.course-lecture')
            </div>
        </div>
        <div class="row mt-5 mb-5">
            @include('frontend.courses.assignments.index')
        </div>
    </div>
@endsection
