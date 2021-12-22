<!-- Matching Section Start-->
  <div id="matching" class="d-none">
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_A" class="require">@lang('A')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_A" id="matching_A"
                     class="form-control{{ $errors->has('matching_A') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_A', isset($post->matching_answer->answer['match_one']['first']) ? $post->matching_answer->answer['match_one']['first']: '') }}">
                 {!! $errors->first('matching_A', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_A')" class="invalid-feedback">@{{ errors.first('matching_A') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_One" class="require">@lang('1')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_One" id="matching_One"
                     class="form-control{{ $errors->has('matching_One') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_One', isset($post->matching_answer->answer['match_one']['second']) ? $post->matching_answer->answer['match_one']['second']: '') }}">
                 {!! $errors->first('matching_One', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_One')" class="invalid-feedback">@{{ errors.first('matching_One') }}</div>
             </div>
          </div>
      </div>  
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_B" class="require">@lang('B')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_B" id="matching_B"
                     class="form-control{{ $errors->has('matching_B') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_B', isset($post->matching_answer->answer['match_two']['first']) ? $post->matching_answer->answer['match_two']['first']: '') }}">
                 {!! $errors->first('matching_B', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_B')" class="invalid-feedback">@{{ errors.first('matching_B') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Two" class="require">@lang('2')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_Two" id="matching_Two"
                     class="form-control{{ $errors->has('matching_Two') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_Two', isset($post->matching_answer->answer['match_two']['second']) ? $post->matching_answer->answer['match_two']['second']: '') }}">
                 {!! $errors->first('matching_Two', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_Two')" class="invalid-feedback">@{{ errors.first('matching_Two') }}</div>  
             </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_C" class="require">@lang('C')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_C" id="matching_C"
                     class="form-control{{ $errors->has('matching_C') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_C', isset($post->matching_answer->answer['match_three']['first']) ? $post->matching_answer->answer['match_three']['first']: '') }}">
                 {!! $errors->first('matching_C', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_C')" class="invalid-feedback">@{{ errors.first('matching_C') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Three" class="require">@lang('3')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_Three" id="matching_Three"
                     class="form-control{{ $errors->has('matching_Three') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_Three', isset($post->matching_answer->answer['match_three']['second']) ? $post->matching_answer->answer['match_three']['second']: '') }}">
                 {!! $errors->first('matching_Three', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_Three')" class="invalid-feedback">@{{ errors.first('matching_Three') }}</div>
             </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_D" class="require">@lang('D')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_D" id="matching_D"
                     class="form-control{{ $errors->has('matching_D') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_D', isset($post->matching_answer->answer['match_four']['first']) ? $post->matching_answer->answer['match_four']['first']: '') }}">
                 {!! $errors->first('matching_D', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_D')" class="invalid-feedback">@{{ errors.first('matching_D') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Four" class="require">@lang('4')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_Four" id="matching_Four"
                     class="form-control{{ $errors->has('matching_Four') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_Four', isset($post->matching_answer->answer['match_four']['second']) ? $post->matching_answer->answer['match_four']['second']: '') }}">
                 {!! $errors->first('matching_Four', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_Four')" class="invalid-feedback">@{{ errors.first('matching_Four') }}</div>
             </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_E" class="require">@lang('E')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_E" id="matching_E"
                     class="form-control{{ $errors->has('matching_E') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_E', isset($post->matching_answer->answer['match_five']['first']) ? $post->matching_answer->answer['match_five']['first']: '') }}">
                 {!! $errors->first('matching_E', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_E')" class="invalid-feedback">@{{ errors.first('matching_E') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Five" class="require">@lang('5')</label>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="matching_Five" id="matching_Five"
                     class="form-control{{ $errors->has('matching_Five') ? ' is-invalid' : '' }}"
                     value="{{ old('matching_Five', isset($post->matching_answer->answer['match_five']['second']) ? $post->matching_answer->answer['match_five']['second']: '') }}">
                 {!! $errors->first('matching_Five', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_Five')" class="invalid-feedback">@{{ errors.first('matching_Five') }}</div>
             </div>
          </div>
      </div>
 </div>
 <!-- Matching Section End-->