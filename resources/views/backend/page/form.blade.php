@extends('backend.layouts.default')

@section('title', __('Page'))

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
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.page.update',
                    $post->id) ,
                    'class' => 'form-horizontal')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.page.store',
                    'class' => 'form-horizontal')) !!}
                    @endif

                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="title" class="require">{{ __('Title') }}</label>
                                <input type="text" placeholder="Title.." name="title" id="title"
                                    class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            {{--<div class="form-group">
									<label for="second_title">@lang('Second Title')</label>
									<input type="text" placeholder="Second Title.." name="second_title" id="second_title"
										   class="form-control"
										   value="{{ old('second_title', isset($post->second_title) ? $post->second_title: '') }}">
                            {!! $errors->first('second_title', '<div class="invalid-feedback">:message</div>') !!}
                        </div>--}}

                        <div class="form-group">
                            <label for="slug">{{ __('Slug') }}
                                <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="The user friendly and part of a URL which identifies a particular page on a website in a form readable by users. e.g., http://example.com/{about-us}"></i>
                            </label>
                            <input type="text" placeholder="Slug.." name="slug" id="slug"
                                class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                value="{{ old('slug', isset($post->slug) ? $post->slug: '') }}">
                            {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="body" class="require">{{ __('Body') }}</label>
                            <textarea data-provide="summernote" data-height="200"
                                class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" rows="5" name="body"
                                id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                            {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="published">{{ __('Image') }}</label>
                                {{ Form::file('uploaded_file') }}

                                @if (isset($post))
                                @php
                                $images = $post->getMedia('pages');
                                @endphp

                                <div>
                                    @foreach($images as $image)
                                    <a target="_blank"
                                        href="{{ $image->getFullUrl() }} {{-- asset($image->getUrl()) --}}">
                                        <img src="{{ asset($image->getUrl('thumb')) }} {{-- asset($image->getFullUrl('thumb')) --}}"
                                            style="border: 1px solid #ccc; margin-right: 10px">
                                    </a>

                                    <a onclick="return confirm('Are you sure you want to delete?')"
                                        href="{{ route('admin.media.destroy', $image->id) }}">{{ __('Remove') }}</a>
                                    @endforeach
                                </div>

                                @endif
                                {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                        @can('publish_page')
                        <div class="form-group">
                            <div><label for="published_yes" class="col-xs-12">{{ __('Published') }}</label></div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes',
											'class' => 'form-check-input']) }}
                                <label for="published_yes" class="form-check-label">{{ __('Yes') }}</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no',
											'class' => 'form-check-input']) }}
                                <label for="published_no" class="form-check-label">{{ __('No') }}</label>
                            </div>
                            {!! $errors->first('published', '
                            <div class="invalid-feedback">:message</div>') !!}
                        </div>
                        @endcan

                        <div class="form-group">
                            @if (auth()->user()->can('add_page') || auth()->user()->can('edit_page'))
                            <button class="btn btn-primary" type="submit" name="btnSave" value="1">{{ __('Save') }}
                            </button>
                            {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply
										</button>--}}
                            @endif
                            <a href="{{ route('admin.page.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                        </div>
                    </div>

                    {{--
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div><label for="show_title_yes" class="col-xs-12">Show Title</label></div>
                                    <div class="form-check form-check-inline">
                                        {{ Form::radio('show_title', 1, (isset($post->show_title) && $post->show_title == 1 ? true : false ), ['id' => 'show_title_yes',
                                        'class' => 'form-check-input']) }}
                    <label for="show_title_yes" class="form-check-label">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    {{ Form::radio('show_title', 0, (!isset($post->show_title) || $post->show_title == 0 ? true : false ), ['id' => 'show_title_no',
                                        'class' => 'form-check-input']) }}
                    <label for="show_title_no" class="form-check-label">No</label>
                </div>
                {!! $errors->first('show_title', '
                <div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        --}}

    </div>
    </form>
</div>
</div>
<!-- END Default Elements -->
</div>
</div>
</div>
@stop
