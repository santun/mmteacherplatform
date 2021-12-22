@extends('frontend.layouts.default')
@section('title', __('Courses'))
@section('header')
    <div class="section mb-0 pb-0">
        <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
            <div class="container text-center">
                <h2>{{__('Courses') }}</h2>
            </div>
        </header>
    </div>
@endsection
@section('breadcrumbs')
    <div class="bg-gray">
        <div class="container">
            @if(\Illuminate\Support\Facades\Session::get('message'))
                <div class="alert alert-primary">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                    {{ \Illuminate\Support\Facades\Session::get('message') }}
                </div>
            @endif
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item">{{ $course->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="border p-2 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ get_course_cover_image($course) }}" alt="">
                </div>
                <div class="col-md-8">
                    <h5>{{ $course->title }}</h5>
                    <p>{!! $course->description !!}</p>
                    <p>{{ $course->category->name }}</p>
                    <p>{{ \App\Models\Course::LEVELS[$course->level_id] }}</p>
                    <div class="">
                        @if(auth()->check())
                            @if(\App\Repositories\CourseRepository::isAlreadyTakenCourse(auth()->user(), $course))
                                <a href="{{ \App\Repositories\CourseRepository::goToLastLecture(auth()->user(), $course) }}" class="btn btn-primary">Continue</a>
                            @else
                                <a href="{{ route('courses.take-course', $course) }}" class="btn btn-primary">Take Course</a>
                            @endif
                        @else
                            <a href="{{ route('courses.take-course', $course) }}" class="btn btn-primary">Take Course</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <h4>Course Content</h4>
                <div class="mt-4 b-3 p-5 mb-5">
                    @foreach($lectures as $lecture)
                        <div class="row">
                            <div class="col-md-6">
                                @if($lecture->getMedia('lecture_attached_file')->first()->mime_type == 'application/pdf')
                                    <i class="fa fa-file-pdf-o mr-2 mt-1"></i>
                                @elseif($lecture->getMedia('lecture_attached_file')->first()->mime_type == 'video/mp4')
                                    <i class="fa fa-play mr-2"></i>
                                @elseif($lecture->getMedia('lecture_attached_file')->first()->mime_type == 'audio/mpeg')
                                    <i class="fa fa-music mr-2"></i>
                                @elseif($lecture->getMedia('lecture_attached_file')->first()->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || $lecture->getMedia('lecture_attached_file')->first()->mime_type == "application/vnd.ms-powerpoint")
                                    <i class="fa fa-file-powerpoint-o mr-2"></i>
                                @else
                                    <i class="fa fa-file mr-2"></i>
                                @endif
                                {{ $lecture->lecture_title }}
                            </div>
                            @foreach($lecture->quizzes as $quiz)
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <i class="fa fa-question-circle mr-2"></i>
                                            {{ $quiz->title }}
                                        </div>
                                        <div class="col-md-6">
                                            {{ count($quiz->questions) }}
                                            {{ count($quiz->questions) > 1 ? ' Questions' : 'Question' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <h4 class="mt-5">Course Quizzes</h4>

                    @foreach($course->quizzes()->where('lecture_id', null)->get() as $quiz)
                        <div class="row">
                            <div class="col-md-6">
                                <i class="fa fa-question-circle mr-2"></i>
                                {{ $quiz->title }}
                            </div>
                            <div class="col-md-6">
                                {{ count($quiz->questions) }}
                                {{ count($quiz->questions) > 1 ? ' Questions' : 'Question' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
