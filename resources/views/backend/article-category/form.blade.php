@extends('backend.layouts.default')

@section('title', 'Article Category')

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
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.article-category.update',
                            $post->id) , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route'
                            => 'admin.article-category.store', 'class' => 'form-horizontal')) !!}
                        @endif
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label for="title" class="require">{{ __('Title') }}</label>
                                    <input type="text" placeholder="Title.." name="title" id="title"
                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                    {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                </div>

                                <div class="form-group">
                                    <label for="slug">{{ __('Slug') }}
                                        <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                           data-placement="top"
                                           data-original-title="The user friendly and part of a URL which identifies a particular Category on a website in a form readable by users. e.g., http://example.com/{about-us}"></i>
                                    </label>
                                    <input type="text" placeholder="Slug.." name="slug" id="slug"
                                           class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                           value="{{ old('slug', isset($post->slug) ? $post->slug: '') }}">
                                    {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
                                </div>

                                @can('publish_article_category')
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
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    @if (auth()->user()->can('add_article_category') || auth()->user()->can('edit_article_category'))
                                        <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                            {{ __('Save') }}
                                        </button>
                                        {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">
                                            Apply
                                        </button>--}}
                                    @endif
                                    <a href="{{ route('admin.article-category.index') }}"
                                       class="btn btn-flat">{{ __('Cancel') }}</a>
                                </div>
                            </div>
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
