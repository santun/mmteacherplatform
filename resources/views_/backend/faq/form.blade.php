@extends('backend.layouts.default')

@section('title', __('FAQ'))

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Default Elements -->
                <div class="card">
                    <h4 class="card-title">
                        @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                    </h4>
                    <div class="card-body">
                        @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.faq.update', $post->id)
                            , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route' => 'admin.faq.store',
                            'class' => 'form-horizontal')) !!}
                        @endif

                        <div class="form-group">
                            <label for="category_id" class="require col-xs-12">
                                {{ __('Category') }}
                            </label>
                            {!! Form::select('category_id', $categories, old('category_id', isset($post->category_id)
                            ? $post->category_id: ''), ['class' => $errors->has('category_id') ? 'form-control is-invalid' : 'form-control' ]) !!}
                            {!! $errors->first('category_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="question" class="require">{{ __('Question') }}</label>
                            <textarea data-provide="summernote" data-height="200" class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}" rows="5" name="question"
                                      id="question">{{ old('question', isset($post->question) ? $post->question: '') }}</textarea>
                            {!! $errors->first('question', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="answer" class="require">{{ __('Answer') }}</label>
                            <textarea data-provide="summernote" data-height="200" class="form-control {{ $errors->has('answer') ? ' is-invalid' : '' }}" rows="5" name="answer"
                                    id="answer">{{ old('answer', isset($post->answer) ? $post->answer: '') }}</textarea>
                            {!! $errors->first('answer', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        @can('publish_faq')
                            <div class="form-group">
                                <div><label for="published_yes" class="col-xs-12">Published</label></div>
                                <div class="form-check form-check-inline">
                                    {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes', 'class' => 'form-check-input']) }}
                                    <label for="published_yes" class="form-check-label">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no', 'class' => 'form-check-input']) }}
                                    <label for="published_no" class="form-check-label">No</label>
                                </div>
                                {!! $errors->first('published', '<p class="help-block">:message</p>') !!}
                            </div>
                        @endcan

                        <div class="form-group">
                            @if (auth()->user()->can('add_faq') || auth()->user()->can('edit_faq'))
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">{{ __('Save') }}
                                </button>
                                {{-- <button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply
                                </button>--}}
                            @endif
                            <a href="{{ route('admin.faq.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- END Default Elements -->
            </div>
        </div>
    </div>
@stop
