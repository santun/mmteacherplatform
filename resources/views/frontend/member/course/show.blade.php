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
                    <h1>{{ __('Course') }}</h1>
                    <div class="card">
                        <div class="card-body">
                            @php
                                $canEdit = App\Repositories\CoursePermissionRepository::canEdit($course);
                            @endphp
                            <nav>
                                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">@lang('Course Info')</a>
                                    <a class="nav-item nav-link" id="nav-lecture-tab" data-toggle="tab" href="#nav-lecture" role="tab" aria-controls="nav-lecture" aria-selected="false">@lang('Lectures')</a>
                                    <a class="nav-item nav-link" id="nav-quiz-tab" data-toggle="tab" href="#nav-quiz" role="tab" aria-controls="nav-quiz" aria-selected="false">@lang('Quizzes')</a>
                                    <a class="nav-item nav-link" id="nav-assignment-tab" data-toggle="tab" href="#nav-assignment" role="tab" aria-controls="nav-assignment" aria-selected="false">@lang('Assignments')</a>
                                  </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <div class="row">
                                        <div class="col-md-12 pt-4">
                                        <a href="{{route('courses.show', $course->slug)}}" class="pull-right" style="text-decoration: underline;">@lang('Go To Courses')</a>
                                            <table class="table no-footer" style="border: none;">
                                                <tr>
                                                    <td>@lang('Title')</td>
                                                    <td>{{$course->title}}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Cover Image')</td>
                                                    <td>
                                                        @if ($img_url = $course->getThumbnailPath())
                                                            @php
                                                                $images = $course->getMedia('course_cover_image');
                                                            @endphp
                                                            @foreach($images as $image)
                                                                <img src="{{ asset($image->getUrl('thumb')) }}">
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Resource File')</td>
                                                    <td>
                                                        @foreach($course->getMedia('course_resource_file') as $resource)
                                                            <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Course Category')</td>
                                                    <td>{{$course->category->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Course Level')</td>
                                                    <td>{{ ($course->level_id && array_key_exists($course->level_id, $levels))? $levels[$course->level_id] : '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Description')</td>
                                                    <td>{!! $course->description !!}</td>
                                                </tr>
                                                <tr>
                                                    <td>Url Link</td>
                                                    <td>
                                                        @if(!empty($course->url_link))
                                                            <a href="{{ $course->url_link }}"> {{ $course->url_link }} </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Downloadable Option</td>
                                                    <td>
                                                        {{ \App\Models\Course::DOWNLOADABLE_OPTIONS[$course->downloadable_option] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Is Published ?</td>
                                                    <td>
                                                        @if($course->is_published) Yes @else No @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Allow Edit ?</td>
                                                    <td>
                                                        @if($course->allow_edit) Yes @else No @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Is Locked ?</td>
                                                    <td>
                                                        @if($course->is_locked) Yes @else No @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Created At</td>
                                                    <td>
                                                        {{ $course->created_at ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Accessible Right</td>
                                                    <td>
                                                        @foreach($course->privacies as $privacy)
                                                            {{ ($privacy->user_type && array_key_exists($privacy->user_type, $userTypes))? $userTypes[$privacy->user_type] : '' }}
                                                            @if(!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="px-3">
                                                <a href="{{route('member.course.edit', $course->id)}}" class="btn btn-primary btn-sm">@lang('Edit')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!------------------- Lecture List Start -------------------------->
                                <div class="tab-pane fade" id="nav-lecture" role="tabpanel" aria-labelledby="nav-lecture-tab">
                                    @include('frontend.member.course.show_lecture_list')
                                </div>

                                <!------------------- Assignment List Start -------------------------->
                                <div class="tab-pane fade" id="nav-assignment" role="tabpanel" aria-labelledby="nav-assignment-tab">
                                    @include('frontend.member.course.show_assignment_list')
                                </div>

                                <!------------------- Quiz List Start -------------------------->
                                <div class="tab-pane fade" id="nav-quiz" role="tabpanel" aria-labelledby="nav-quiz-tab">
                                    @include('frontend.member.course.show_quiz_list')
                                </div>
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
            .quiz_table > thead tr th {
                font-weight: bold;
                font-size: 1em;
            }
            .question_table td, .question_table th {
                border: 0;
            }
            .question_table tr:first-child th {
                border-top: 0;
            }
            .question_table tr:first-child th {
                border-bottom: 1px solid #ddd;
            }
            .question_table tr {
                border-bottom: 1px dashed #ddd;
            }
        </style>
    @endsection

@section('script')
@parent
<script>
    $(document).ready(function() {
          let url = location.href.replace(/\/$/, "");
          if (location.hash) {
            const hash = url.split("#");
            $('#nav-tab a[href="#'+hash[1]+'"]').tab("show");
            url = location.href.replace(/\/#/, "#");
            history.replaceState(null, null, url);
            setTimeout(() => {
              $(window).scrollTop(0);
            }, 400);
          }

          $('a[data-toggle="tab"]').on("click", function() {
            let newUrl;
            const hash = $(this).attr("href");
            if(hash == "#nav-home") {
              newUrl = url.split("#")[0];
            } else {
              newUrl = url.split("#")[0] + hash;
            }
            // newUrl += "/";
            history.replaceState(null, null, newUrl);
          });

    });
</script>

@endsection
