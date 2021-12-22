@extends('frontend.layouts.default')
@section('title', __('Question'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
    <!--<canvas class="constellation" data-radius="0"></canvas>-->
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   <h1>{{ __('Question'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New ' ).']' }}</h1>
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
                        {{ __('Question') }}
                        @if (isset($post->id)) [Edit] @else [New] @endif
                    </h4>

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.question.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => ['member.question.store', $quiz->id],
                    'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @endif
                    {!! Form::hidden('redirect_to', url()->previous()) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>@lang('Quiz Title') : {{$quiz->title}}</h5>
                                <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                                <input type="hidden" name="quiz_type" id="quiz_type" value="{{$quiz->type}}">
                            </div>    
                            <div class="form-group">
                                <label for="title" class="require">@lang('Question Title')</label>
                                <textarea  v-validate="'required'" name="title" placeholder="Title.."   id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="attached_file">{{ __('Image File') }}</label>
                                @if(isset($post->id))
                                {{ Form::file('attached_file',
                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'image|size:1024'"]) }}
                                    @if ($post->getThumbnailPath())
                                      <div style="padding-top: 5px;" id="file_wrap">
                                          @forelse($post->getMedia('question_attached_file') as $image)
                                              <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                <img src="{{ asset($image->getUrl('thumb')) }}">
                                              </a>
                                              <a href="javascript::void(0)" data-href="{{ route('member.ajax.media.destroy', $image->id) }}" data-id class="text-danger remove_image">Remove</a>
                                          @empty
                                          @endforelse
                                      </div>
                                    @endif
                                    <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                @else
                                {{ Form::file('attached_file',
                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'image|size:1024'"]) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="type">@lang('Quiz Type')</label>
                                <input type="text" class="form-control"
                                    value="{{ $quiz->getQuizType() }}" disabled >
                            </div>
                            <div class="">
                                <label class="require">@lang('Answer')</label>
                            </div>
                            @if($quiz->type == \App\Models\Quiz::TRUE_FALSE)
                              @include('frontend.member.question.true_false_form')
                            @elseif($quiz->type == \App\Models\Quiz::BLANK)
                              @include('frontend.member.question.blank_form')
                            @elseif($quiz->type == \App\Models\Quiz::SHORT_QUESTION)
                              @include('frontend.member.question.short_question_form')
                            @elseif($quiz->type == \App\Models\Quiz::MULTIPLE_CHOICE)
                              @include('frontend.member.question.multiple_choice_form')
                            @elseif($quiz->type == \App\Models\Quiz::REARRANGE)
                              @include('frontend.member.question.rearrange_form')
                            @elseif($quiz->type == \App\Models\Quiz::MATCHING)
                              @include('frontend.member.question.matching_form')
                            @else
                            @endif
                           <!-- Description Start -->
                           <div class="form-group">
                               <label for="description" class="">@lang('Detail Explanation')</label>
                               <textarea  v-validate="''" name="description" placeholder="Description..."   id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                               {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                               <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                           </div>
                           <!-- Description End -->

                    </div>                    
                </div>

                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                         @if(!isset($post))
                        <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                        @endif
                        <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
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

          $('#description, #title').summernote({
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
        $(document).ready(function() {
            // let quiz_type = $('#quiz_type').val();
            // change_quiz_type(quiz_type);
            
            // $('#quiz_type').on('change', function() {
            //     change_quiz_type(this.value);
            // });

          // ajax emove media
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

        // function change_quiz_type(quiz_type){
        //       const TRUE_FALSE = 'true_false';
        //       const MULTIPLE_CHOICE = 'multiple_choice';
        //       const BLANK = 'blank';
        //       const REARRANGE = 'rearrange';
        //       const MATCHING = 'matching';
        //       const SHORT_QUESTION = 'short_question';
        //       if (quiz_type == TRUE_FALSE) {
        //               $('#true_or_false').removeClass('d-none')
        //               $('#true_or_false input').prop("disabled", false);
        //               $('#rearrange, #fill_in_the_blank, #multiple_choice, #matching, #short_question').addClass('d-none')
        //               $('#rearrange input, #multiple_choice input, #fill_in_the_blank input, #matching input, #short_question input').prop("disabled", true);
        //         }else if(quiz_type == SHORT_QUESTION){
        //               $('#short_question').removeClass('d-none')
        //               $('#short_question input').prop("disabled", false);
        //               $('#rearrange, #fill_in_the_blank, #true_or_false, #matching, #multiple_choice').addClass('d-none')
        //               $('#rearrange input, #true_or_false input, #fill_in_the_blank input, #matching input, #multiple_choice input').prop("disabled", true);
        //         }else if(quiz_type == MULTIPLE_CHOICE){
        //               $('#multiple_choice').removeClass('d-none')
        //               $('#multiple_choice input').prop("disabled", false);
        //               $('#rearrange, #fill_in_the_blank, #true_or_false, #matching, #short_question').addClass('d-none')
        //               $('#rearrange input, #true_or_false input, #fill_in_the_blank input, #matching input, #short_question input').prop("disabled", true);
        //         }else if(quiz_type == BLANK){
        //             $('#fill_in_the_blank').removeClass('d-none')
        //             $('#fill_in_the_blank input').prop("disabled", false);
        //             $('#rearrange, #multiple_choice, #true_or_false, #matching, #short_question').addClass('d-none')
        //             $('#rearrange input, #true_or_false input, #multiple_choice input, #matching input, #short_question input').prop("disabled", true);
        //         }else if(quiz_type == REARRANGE){
        //             $('#rearrange').removeClass('d-none')
        //             $('#rearrange input').prop("disabled", false);
        //             $('#fill_in_the_blank, #multiple_choice, #true_or_false, #matching, #short_question').addClass('d-none')
        //             $('#fill_in_the_blank input, #true_or_false input, #multiple_choice input, #matching input, #short_question input').prop("disabled", true);              
        //         }else if(quiz_type == MATCHING){
        //             $('#matching').removeClass('d-none')
        //             $('#matching input').prop("disabled", false);
        //             $('#fill_in_the_blank, #multiple_choice, #true_or_false, #rearrange, #short_question').addClass('d-none')
        //             $('#fill_in_the_blank input, #true_or_false input, #multiple_choice input, #rearrange input, #short_question input').prop("disabled", true);              
        //         }
        //     }

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
