@extends('backend.layouts.default')

@section('title', __('Slide'))

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
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.slide.update',
                    $post->id) ,
                    'class' => 'form-horizontal')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.slide.store',
                    'class' => 'form-horizontal')) !!}
                    @endif

                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="title" class="require">@lang('Title')</label>
                                <input type="text" placeholder="Title.." name="title" id="title"
                                    class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="weight">{{ __('Weight') }}</label>
                                <input type="text" placeholder="0 (or) must be integer" name="weight" id="weight"
                                    class="form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                    value="{{ old('weight', isset($post->weight) ? $post->weight: '') }}">
                                {!! $errors->first('weight', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="description" class="">@lang('Description')</label>
                                <textarea data-provide="summernote" data-height="200"
                                    class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5"
                                    name="description"
                                    id="description">{{ old('description', isset($post->description) ? $post->description: '') }}</textarea>
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="published" class="require">@lang('Image')</label>
                                    {{ Form::file('uploaded_file') }}

                                    @if (isset($post))
                                    @php
                                    $images = $post->getMedia('slides');
                                    @endphp

                                    @foreach($images as $image)
                                    <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                        <img src="{{ asset($image->getUrl('thumb')) }}">
                                    </a>
                                    <a onclick="return confirm('Are you sure you want to delete?')"
                                        href="{{ route('admin.media.destroy', $image->id) }}">Remove</a>
                                    @endforeach
                                    @endif

                                    {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>')
                                    !!}
                                </div>
                            </div>

                            @can('publish_page')
                            <div class="form-group">
                                <div><label for="published_yes" class="col-xs-12">Published</label></div>
                                <div class="form-check form-check-inline">
                                    {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes',
                                        'class' => 'form-check-input']) }}
                                    <label for="published_yes" class="form-check-label">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no',
                                        'class' => 'form-check-input']) }}
                                    <label for="published_no" class="form-check-label">No</label>
                                </div>
                                {!! $errors->first('published', '
                                <div class="invalid-feedback">:message</div>') !!}
                            </div>
                            @endcan

                            <div class="form-group">
                                @if (auth()->user()->can('add_page') || auth()->user()->can('edit_page'))
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">{{ __('Save') }}
                                </button>
                                @endif
                                <a href="{{ route('admin.slide.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                            </div>
                        </div>

                        <div class="col-md-4">

                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- END Default Elements -->
        </div>
    </div>
</div>
@stop
