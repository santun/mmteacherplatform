@extends('frontend.layouts.default')
@section('title', __('Quiz'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
    <!--<canvas class="constellation" data-radius="0"></canvas>-->
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   <h1>{{ __('Quiz'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New ' ).']' }}</h1>
                </div>
            </div>
        </div>
    </header>
</div>

@endsection

@section('content')

<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">
            <div class="row gap-y">
                <div class="col-md-3 mx-auto">
                    @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-9 mx-auto" id="quiz_root">
                    <h4>
                        {{ __('Quiz') }}
                        @if (isset($post->id)) [Edit] @else [New] @endif
                    </h4>

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.quiz.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => ['member.quiz.store', $course->id],
                    'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @endif
                    {!! Form::hidden('redirect_to', url()->previous()) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>@lang('Course') : {{$course->title}}</h5>
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                            </div>    
                            <div class="form-group">
                                <label for="title" class="require">@lang('Quiz Title')</label>
                                <textarea  v-validate="'required|max:255'" name="title" placeholder="Title.."   id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                            </div>
                            <div class="form-group">
                                @php
                                    $existing_lecture_id = get_lecture_from_query_string_or_resource(isset($post->lecture_id)? $post->lecture_id:
                                    '', request()->lecture_id);
                                @endphp
                                <label for="lecture_id" class="col-xs-12">{{__('Course or Lecture Title') }}</label>
                                {!! Form::select('lecture_id', $lectures, old('lecture_id', $existing_lecture_id), ['class' => $errors->has('lecture_id')?
                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'min:1'" ]) !!}
                                {!! $errors->first('lecture_id', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('lecture_id')" class="invalid-feedback">@{{ errors.first('lecture_id') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-xs-12 require">{{__('Quiz Type') }}</label>
                                @if(isset($post->type))
                                {!! Form::select('type', $quiz_types, old('type', isset($post->type)? $post->type:
                                    ''), ['class' => $errors->has('type')?
                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'", 'id'=>'quiz_type', 'disabled' => true ]) !!}
                                    <input type="hidden" name="type" id="type" value="{{$post->type}}">
                                  @else
                                    {!! Form::select('type', $quiz_types, old('type', isset($post->type)? $post->type:
                                    ''), ['class' => $errors->has('type')?
                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'", 'id'=>'quiz_type' ]) !!}
                                  @endif
                                {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('type')" class="invalid-feedback">@{{ errors.first('type') }}</div>
                            </div> 
                    </div>                    
                </div>

                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                        <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                         @if(!isset($post))
                        <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                        @endif
                        <input class="btn btn-primary" type="submit" name="btnSaveAddQuestion" value="Save & Add Question">
                        <a href="{{ route('member.course.show', $course->id).'#nav-quiz' }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        </div>
    </section>
</main>

@stop

@section('css')
@parent
@endsection

@section('script')
@parent
    <script src="https://unpkg.com/vee-validate@latest"></script>
    <script>
        $(document).ready(function() {
          // ajax remove media
        $('.remove_image').on('click', function() {
            let yes_or_no = confirm("Are you sure to remove?");
            if (yes_or_no == true) {
              let url = $(this).data('href');
              $.get(url, function(data, status){
                // alert("Data: " + data + "\nStatus: " + status);
                if (status == 'success') {
                  $('#file_wrap').remove();
                }
              });
            }
        });

        });
    </script>

<script>

        new Vue({
            el: '#quiz_root',
            data: {
                messages: {

                },
            },
            //components: [commodity_component],

            mounted() {
            },
            methods: {
                validateBeforeSubmit: function(e) {
                    this.$validator.validateAll().then((result) => {
                        console.log(result)

                        if (result) {
                            // eslint-disable-next-line
                            return true;
                        }
                        e.preventDefault();
                    });
                }
            }

        });
        </script>
    @endsection
