@extends('frontend.layouts.default')
@section('title', __('Assignment'))
@section('header')
    <div class="section mb-0 pb-0">
        <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
            <div class="container text-center">
                <h2>{{__('Assignment') }}</h2>
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
{{--            <div class="row">--}}
{{--                <nav aria-label="breadcrumb">--}}
{{--                    <ol class="breadcrumb">--}}
{{--                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>--}}
{{--                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>--}}
{{--                        <li class="breadcrumb-item">{{ $course->title }}</li>--}}
{{--                    </ol>--}}
{{--                </nav>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <form action="{{ route('courses.submit-assignment', $assignment) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mt-3 mb-5">
                <div class="col-md-12">
                    <h4>{{ $assignment->title }}</h4>
                    <p class="heading lead mt-5">
                        Assignment Instruction
                    </p>
                    <p>
                        {!! $assignment->description !!}
                    </p>
                    <a href="{{ asset($assignmentMedia->getUrl()) }}" >Download Assignment</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <input type="file" name="assignment_file" id="assignment_file"><br>
                    {!! $errors->first('assignment_file', '<span class="help-block" style="color:red">:message</span>') !!}
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-md-12">
                    <div class="justify-content-center">
                        <input type="submit" class="btn btn-primary btn-sm" value="Submit Assignment">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
