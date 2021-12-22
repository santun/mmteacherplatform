<!-- Fill BLank Start-->
<div  id="fill_in_the_blank">
    <div class="form-group">
       <textarea  v-validate="'required'" name="blank_answer" placeholder="Answer..."   id="blank_answer" class="form-control{{ $errors->has('blank_answer') ? ' is-invalid' : '' }}">{{old('blank_answer', isset($post->blank_answer->answer) ? $post->blank_answer->answer: '')}}</textarea>
       {!! $errors->first('blank_answer', '<div class="invalid-feedback">:message</div>') !!}
       <div v-show="errors.has('blank_answer')" class="invalid-feedback">@{{ errors.first('blank_answer') }}</div>
   </div>
</div>
<!-- Fill BLank End-->