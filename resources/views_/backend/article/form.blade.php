@extends('backend.layouts.default')

@section('title', __('Article'))

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
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.article.update', $post->id)
                            , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route' => 'admin.article.store',
                            'class' => 'form-horizontal')) !!}
                        @endif

                        <div class="form-group">
                            <label for="title" class="require">@lang('Title')</label>
                            <input type="text" placeholder="Title.." name="title" id="title"
                                   class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                   value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="col-xs-12 require">
                                @lang('Category')
                            </label>
                            {!! Form::select('category_id', $categories, old('category_id', isset($post->category_id)
                            ? $post->category_id: ''), ['class' => $errors->has('category_id') ? 'form-control is-invalid' : 'form-control']) !!}
                            {!! $errors->first('category_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="body" class="require">@lang('Body')</label>
                            <textarea data-provide="summernote" data-height="200" class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}" rows="5" name="body"
                                      id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                            {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="published">@lang('Image')</label>
                                {{ Form::file('uploaded_file') }}
                                @if (isset($post))
                                @php
                                $images = $post->getMedia('articles');
                                @endphp
                                <div>
                                @foreach($images as $image)
                                <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                    <img src="{{ asset($image->getUrl('thumb')) }}">
                                </a>
                                <a onclick="return confirm('Are you sure you want to delete?')" href="{{ route('admin.media.destroy', $image->id) }}">Remove</a>
                                @endforeach
                                </div>
                                @endif
                                {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                        @can('publish_article')
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
                            @if (auth()->user()->can('add_article') || auth()->user()->can('edit_article'))
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">Save
                                </button>
                                {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply
                                </button>--}}
                            @endif
                            <a href="{{ route('admin.article.index') }}" class="btn btn-flat">Cancel</a>
                        </div>

                        </form>
                    </div>
                </div>
                <!-- END Default Elements -->
            </div>
        </div>
    </div>
@stop
