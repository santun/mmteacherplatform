@extends('frontend.layouts.default')
@section('title', __('Lecture'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
    <!--<canvas class="constellation" data-radius="0"></canvas>-->
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   <h1>{{ __('Lecture'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New ' ).']' }}</h1>
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

                <div class="col-md-9 mx-auto" id="lecture_root">
                    <h4>
                        {{ __('Lecture') }}
                        @if (isset($post->id)) [Edit] @else [New] @endif
                    </h4>

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.lecture.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => ['member.lecture.store', $course->id],
                    'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @endif
                    {!! Form::hidden('redirect_to', url()->previous()) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('Course Title')}} : </label><span> {{$course->title}} </span>
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                            </div>
                            <div class="form-group">
                                <label for="lecture_title" class="require">@lang('Lecture Title')</label>
                                <textarea  v-validate="'required'" name="lecture_title" placeholder="Title.."   id="lecture_title" class="form-control{{ $errors->has('lecture_title') ? ' is-invalid' : '' }}">{{old('lecture_title', isset($post->lecture_title) ? $post->lecture_title: '')}}</textarea>
                                {!! $errors->first('lecture_title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('lecture_title')" class="invalid-feedback">@{{ errors.first('lecture_title') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="attached_file" class="@if(isset($post->id))  @else require @endif">{{ __('Attached File') }}</label>
                                @if(isset($post->id))
                                {{ Form::file('attached_file',
                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:pdf,ppt,pptx,mp3,mp4,odp'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx and .pdf</small>
                                <div style="padding: 10px 0px;">
                                    {{--<img src="{{asset('assets/course/attached_file'.'/'.$post->id.'/'.$post->attached_file)}}" width="150" height="90" style="border: 3px solid #ddd;">--}}
                                    @foreach($post->getMedia('lecture_attached_file') as $resource)
                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                    @endforeach
                                </div>
                                @else
                                {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'required|ext:pdf,ppt,pptx,mp3,mp4'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx and .pdf</small>
                                @endif
                                <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <label for="resource_link" class="">{{ __('Resource Link') }}</label>
                                <input v-validate="'url'" type="text" placeholder="link" name="resource_link" id="resource_link"
                                        value="{{ old('resource_link', isset($post->resource_link) ? $post->resource_link: '') }}"
                                        class="form-control{{ $errors->has('strand') ? ' is-invalid' : '' }}">
                                {!! $errors->first('resource_link', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('resource_link')" class="invalid-feedback">@{{ errors.first('resource_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="">@lang('Description')</label>
                                <textarea  v-validate="''" name="description" placeholder="Description..."   id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                        <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                         @if(!isset($post))
                        <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                        @endif
                        <input class="btn btn-primary" type="submit" name="btnSaveNext" value="Save & Next">
                        <a href="{{ route('member.course.show', $course->id).'#nav-lecture' }}" class="btn btn-flat">{{ __('Cancel') }}</a>
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
    $('#description').summernote({
        height: 200,
        toolbar: [
            // [groupName, [list of button]],
            ['fontname', ['fontname']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            // ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
});
</script>

<script>

        new Vue({
            el: '#lecture_root',
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
