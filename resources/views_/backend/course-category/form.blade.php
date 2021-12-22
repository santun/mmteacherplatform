@extends('backend.layouts.default')

@section('title', __('Course Category'))

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
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.course-category.update',
                            $post->id) , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route'
                            => 'admin.course-category.store', 'class' => 'form-horizontal')) !!}
                        @endif
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label for="name" class="require">{{ __('Name') }}</label>
                                    <input type="text" placeholder="Name...." name="name" id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
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
                                    <a href="{{ route('admin.course-category.create') }}"
                                       class="btn btn-default">{{ __('Reset') }}</a>
                                    <a href="{{ route('admin.course-category.index') }}"
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
