@extends('frontend.layouts.default')
@section('title', __('Course'))

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    @include('frontend.member.partials.sidebar')
                </div>
                <div class="col-md-10 mx-auto">
                    <h1>Take Course Users for <strong>'{{$course->title}}'</strong>.</h1>
                    <div class="card">
                        <div class="card-body">                            
                        {{-- @if(count($course->courseLearners) != 0)
                            {{$course->courseLearners}}
                        @endif --}}
                        <a href="{{route('member.course.index')}}" class="pull-right" style="text-decoration: underline;">@lang('Back To Courses')</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-vcenter dataTable no-footer" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="60">@lang('No#')</th>
                                        <th>@lang('User Name')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('User Type')</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                        @forelse($course->courseLearners as $key => $learner)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$learner->name}}</td>
                                                <td>{{$learner->email}}</td>
                                                <td>{{$learner->user_type}}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                   
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('css')
    @parent
        <style>
        </style>
    @endsection
