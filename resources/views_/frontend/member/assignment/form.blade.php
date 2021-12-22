@extends('frontend.layouts.default')
@section('title', __('Assignment'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
    <!--<canvas class="constellation" data-radius="0"></canvas>-->
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   <h1>{{ __('Assignment'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New ' ).']' }}</h1>
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

                <div class="col-md-9 mx-auto" id="assignment_root">
                    <h4>
                        {{ __('Assignment') }}
                        @if (isset($post->id)) [Edit] @else [New] @endif
                    </h4>

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.assignment.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => ['member.assignment.store', $course->id],
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
                                <label for="title" class="require">@lang('Assignment Title')</label>
                                <textarea  v-validate="'required'" name="title" placeholder="Title.."   id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description">@lang('Assignment Instruction')</label>
                                <textarea name="description" placeholder="Description..."   id="description" class="form-control">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="attached_file" class="@if(isset($post->id))  @else require @endif">{{ __('Attached File') }}</label>
                                @if(isset($post->id))
                                {{ Form::file('attached_file',
                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
                                <div style="padding: 10px 0px;">
                                    @foreach($post->getMedia('assignment_attached_file') as $resource)
                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                    @endforeach
                                </div>
                                @else
                                {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'required|ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
                                @endif 
                                <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>                        
                        </div>                    
                    </div>

                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                         @if(!isset($post))
                        <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                        @endif
                        <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                        <a href="{{ route('member.course.show', $course->id).'#nav-assignment' }}" class="btn btn-flat">{{ __('Cancel') }}</a>
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
            el: '#assignment_root',
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
