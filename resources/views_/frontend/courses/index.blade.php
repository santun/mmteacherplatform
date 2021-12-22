@extends('frontend.layouts.default')
@section('title', __('Courses'))
@section('header')
    <div class="section mb-0 pb-0">
        <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
            <div class="container text-center">
                <h2>E-Learning</h2>
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
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group mt-3 mb-5">
                    <h3>Keyword</h3>
                    <input type="text" class="form-control keyword" placeholder="Enter Keyword" id="keyword" name="keyword">
                </div>
                @include('frontend.courses.partials.levels')
                @include('frontend.courses.partials.course_categories')
            </div>
            @include('frontend.courses.main-component')
        </div>
    </div>
@endsection

@section('script')
    @parent
    @include('frontend.courses.partials.js')
@endsection
