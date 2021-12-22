@extends('backend.layouts.default')

@section('title', __('Role'))

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
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.role.update', $post->id)
                    , 'class' => 'form-horizontal')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.role.store',
                    'class' => 'form-horizontal')) !!}
                    @endif
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title" class="col-xs-12 require">@lang('Name')</label>
                                    <input type="text" placeholder="Name.." name="name" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <h5><b>{{ __('Permissions') }}</b></h5>

                            <div class='form-group'>
                                @foreach ($permissions as $permission)
                                    @if (isset($post) && $post->permissions)
                                    {{Form::checkbox('permissions[]', $permission->id, $post->permissions->contains('name', $permission->name), ['id' => 'p_'.$permission->id] ) }}
                                    @else
                                    {{Form::checkbox('permissions[]', $permission->id, null, ['id' => 'p_'.$permission->id]) }}
                                    @endif
                                    {{ Form::label('p_'.$permission->id, $permission->name) }}<br>
                                @endforeach
                                {!! $errors->first('permissions[]', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                            @if (auth()->user()->can('add_role') || auth()->user()->can('edit_role'))
                            <button class="btn btn-primary" type="submit" name="btnSave" value="1">{{ __('Save') }}</button>
                            @endif
                            <a href="{{ route('admin.role.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
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
