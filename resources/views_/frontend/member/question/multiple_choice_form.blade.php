<!-- Multiple Choice Section Start-->
  <div  id="multiple_choice">
     @if(isset($post))
         @php
            $multi_answers = $post->multiple_answers->toArray();
         @endphp

            @if(array_key_exists(0, $multi_answers) && $multi_answers[0]['name'] == 'A')
                <div class="form-group">
                     <label for="answer_A" class="require">@lang('A')</label>
                     <input type="checkbox" name="right_answer[]" value="A" {{(isset($multi_answers[0]['is_right_answer']) && $multi_answers[0]['is_right_answer'] == true  ? 'checked' : '' )}}>
                     <input v-validate="'required'" type="text" placeholder="" name="answer_A" id="answer_A"
                         class="form-control summernote {{ $errors->has('answer_A') ? ' is-invalid' : '' }}"
                         value="{!! old('answer_A', isset($multi_answers[0]['answer']) ? $multi_answers[0]['answer']: '') !!}">
                     {!! $errors->first('answer_A', '<div class="invalid-feedback">:message</div>') !!}
                     <div v-show="errors.has('answer_A')" class="invalid-feedback">@{{ errors.first('answer_A') }}</div>
                 </div>
             @endif

            @if(array_key_exists(1, $multi_answers) && $multi_answers[1]['name'] == 'B')
                <div class="form-group">
                     <label for="answer_B" class="require">@lang('B')</label>
                     <input type="checkbox" name="right_answer[]" value="B" {{(isset($multi_answers[1]['is_right_answer']) && $multi_answers[1]['is_right_answer'] == true  ? 'checked' : '' )}}>
                     <input v-validate="'required'" type="text" placeholder="" name="answer_B" id="answer_B"
                         class="form-control summernote {{ $errors->has('answer_B') ? ' is-invalid' : '' }}"
                         value="{{ old('answer_B', isset($multi_answers[1]['answer']) ? $multi_answers[1]['answer']: '') }}">
                     {!! $errors->first('answer_B', '<div class="invalid-feedback">:message</div>') !!}
                     <div v-show="errors.has('answer_B')" class="invalid-feedback">@{{ errors.first('answer_B') }}</div>
                 </div>
             @endif

             @if(array_key_exists(2, $multi_answers) && $multi_answers[2]['name'] == 'C')
                <div class="form-group">
                     <label for="answer_C" class="require">@lang('C')</label>
                     <input type="checkbox" name="right_answer[]" value="C" {{(isset($multi_answers[2]['is_right_answer']) && $multi_answers[2]['is_right_answer'] == true  ? 'checked' : '' )}}>
                     <input v-validate="'required'" type="text" placeholder="" name="answer_C" id="answer_C"
                         class="form-control summernote {{ $errors->has('answer_C') ? ' is-invalid' : '' }}"
                         value="{{ old('answer_C', isset($multi_answers[2]['answer']) ? $multi_answers[2]['answer']: '') }}">
                     {!! $errors->first('answer_C', '<div class="invalid-feedback">:message</div>') !!}
                     <div v-show="errors.has('answer_C')" class="invalid-feedback">@{{ errors.first('answer_C') }}</div>
                 </div>
             @endif

             @if(array_key_exists(3, $multi_answers) && $multi_answers[3]['name'] == 'D')
                <div class="form-group">
                     <label for="answer_D" class="">@lang('D') </label><span class="ml-1"> * </span>
                     <input type="checkbox" name="right_answer[]" value="D" {{(isset($multi_answers[3]['is_right_answer']) && $multi_answers[3]['is_right_answer'] == true  ? 'checked' : '' )}}>
                     <input type="text" placeholder="" name="answer_D" id="answer_D"
                         class="form-control summernote " value="{{ old('answer_D', isset($multi_answers[3]['answer']) ? $multi_answers[3]['answer']: '') }}">
                 </div>
             @else
                <div class="form-group">
                     <label for="answer_D" class="">@lang('D')  </label></label><span class="ml-1"> * </span>
                     <input type="checkbox" name="right_answer[]" value="D" >
                     <input type="text" placeholder="" name="answer_D" id="answer_D"
                         class="form-control summernote " value="{{ old('answer_D') }}">
                 </div>
             @endif

             @if(array_key_exists(4, $multi_answers) && $multi_answers[4]['name'] == 'E')
                <div class="form-group">
                     <label for="answer_E" class="">@lang('E')</label><span class="ml-1"> * </span>
                     <input type="checkbox" name="right_answer[]" value="E" {{(isset($multi_answers[4]['is_right_answer']) && $multi_answers[4]['is_right_answer'] == true  ? 'checked' : '' )}}>
                     <input type="text" placeholder="" name="answer_E" id="answer_E"
                         class="form-control summernote " value="{{ old('answer_E', isset($multi_answers[4]['answer']) ? $multi_answers[4]['answer']: '') }}">
                 </div>
             @else
                <div class="form-group">
                    <label for="answer_E" class="">@lang('E')</label><span class="ml-1"> * </span>
                    <input type="checkbox" name="right_answer[]" value="E" >
                    <input type="text" placeholder="" name="answer_E" id="answer_E"
                         class="form-control summernote " value="{{ old('answer_E') }}">
                </div>
             @endif

    @else
        <div class="form-group">
            <label for="answer_A" class="require">@lang('A')</label>
             <input type="checkbox" name="right_answer[]" value="A" >
             <input v-validate="'required'" type="text" placeholder="" name="answer_A" id="answer_A"
                 class="form-control summernote {{ $errors->has('answer_A') ? ' is-invalid' : '' }}" value="{{ old('answer_A') }}">
             {!! $errors->first('right_answer', '<div class="invalid-feedback">:message</div>') !!}
             {!! $errors->first('answer_A', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_A')" class="invalid-feedback">@{{ errors.first('answer_A') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_B" class="require">@lang('B')</label>
             <input type="checkbox" name="right_answer[]" value="B" />
             <input v-validate="'required'" type="text" placeholder="" name="answer_B" id="answer_B"
                 class="form-control summernote {{ $errors->has('answer_B') ? ' is-invalid' : '' }}" value="{{ old('answer_B') }}">
             {!! $errors->first('answer_B', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_B')" class="invalid-feedback">@{{ errors.first('answer_B') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_C" class="require">@lang('C') </label>
             <input type="checkbox" name="right_answer[]" value="C" />
             <input v-validate="'required'" type="text" placeholder="" name="answer_C" id="answer_C"
                 class="form-control summernote {{ $errors->has('answer_C') ? ' is-invalid' : '' }}" value="{{ old('answer_C') }}">
             {!! $errors->first('answer_C', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_C')" class="invalid-feedback">@{{ errors.first('answer_C') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_D" class="">@lang('D')  </label></label><span class="ml-1"> * </span>
             <input type="checkbox" name="right_answer[]" value="D" />
             <input type="text" placeholder="" name="answer_D" id="answer_D"
                 class="form-control summernote " value="{{ old('answer_D') }}">
         </div>
         <div class="form-group">
             <label for="answer_E" class="">@lang('E')</label><span class="ml-1"> * </span>
             <input type="checkbox" name="right_answer[]" value="E" />
             <input type="text" placeholder="" name="answer_E" id="answer_E"
                 class="form-control summernote " value="{{ old('answer_E') }}">
         </div>
    @endif
  </div>

  <!-- Multiple Choice Section End