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
                        <li class="breadcrumb-item active">My Courses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-5">
        <form action="{{ route('courses.my-courses') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <select name="sort_by" id="sort_by" class="form-control">
                        <option value="">~ Sorted By ~</option>
                        <option value="title">Title</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="course_category" id="course_category" class="form-control">
                        <option value="">~ Select Category ~</option>
                        @foreach($courseCategories as $courseCategory)
                            <option value="{{ $courseCategory->id }}"
                                {{ $request->course_category == $courseCategory->id ? 'selected' : '' }}
                            >{{ $courseCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="progress" id="progress" class="form-control">
                        <option value="">~ Progeress ~</option>
                        <option value="not_started"
                            {{ $request->progress == 'not_started' ? 'selected' : '' }}
                        >Not Started</option>
                        <option value="completed"
                            {{ $request->progress == 'completed' ? 'selected' : '' }}
                        >Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex">
                        <input type="submit" class="btn btn-primary" value="Search">
                        <a href="{{ route('courses.my-courses') }}" class="btn btn-warning ml-1">Reset</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="row mt-5">
            @foreach($courses as $course)
                <div class="col-md-4 mt-3">
                    <div class="card border p-2">
                        <img src="{{ get_course_cover_image($course) }}" alt="1">
                        <h5 class="card-title mt-3">{{ $course->title }}</h5>
                        <p class="card-text">
                            {!! implode(' ', array_slice(explode(' ', $course->description), 0, 20)) !!}
                        </p>
                        <p class="card-text">
                            {{ $course->category->name }}
                        </p>
                        <p class="card-text">
                            {{ \App\Models\Course::LEVELS[$course->level_id] }}
                        </p>

                        <span class="tag">
                        @if($course->lectures->count() > 0)
                            {{ \App\Repositories\CourseRepository::checkProgress($course, $userLectures) }}%
                        @else
                            0%
                        @endif
                        </span>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $course->lectures->count() > 0 ? \App\Repositories\CourseRepository::checkProgress($course, $userLectures) : 0 }}%"
                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        @if($course->lectures->first())
                            <a href="{{ \App\Repositories\CourseRepository::goToLastLecture(auth()->user(), $course) }}"
                               class="btn btn-primary mt-3">
                                @if($progress = \App\Repositories\CourseRepository::checkProgress($course, $userLectures) == 0)
                                    Start Learning
                                @elseif(\App\Repositories\CourseRepository::checkProgress($course, $userLectures) > 0 && \App\Repositories\CourseRepository::checkProgress($course, $userLectures) < 100)
                                    Continue
                                @else
                                    Completed
                                @endif
                            </a>
                        @else
                            <a href="#" class="btn btn-danger mt-3">No Lectures Available!</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="text-center">
                    {{ $courses->appends([
                        'sort_by' => $request->sort_by,
                        'course_category' => $request->course_category,
                        'progress' => $request->progress,
                    ])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
