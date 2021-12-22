@extends('backend.layouts.default')

@section('title', __('Roles'))

@section('content')
<div class="card">
    <header class="card-header">
        <h4 class="card-title">
            @can('add_role')
            <a href="{{ route('admin.role.create') }}" class="btn btn-primary text-white">{{ __('New') }}</a>
            @endcan
        </h4>
        <form action="{{ route('admin.role.index') }}" method="get">
            <div class="card-header-actions">
                <div class="lookup lookup-right d-none d-lg-block">
                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                </div>
                <button class="btn btn-primary">{{ __('Search') }}</button>
                <a href="{{ route('admin.role.index') }}" class="btn">{{ __('Reset') }}</a>
            </div>
        </form>
    </header>

    <div class="card-body">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
            <thead>
                <tr>
                    <th width="60">@sortablelink('id', 'ID')</th>
                    <th>@sortablelink('name', 'Name')</th>
                    <th width="160">@sortablelink('updated_at', 'Updated At')</th>
                    <th width="160" class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->name }}</td>
                    <td>{{ $post->updated_at }}</td>
                    <td class="text-right table-options">
                        @if ($post->id != 1)
                        @can('edit_role')
                        <a class="table-action hover-primary cat-edit" href="{{ route('admin.role.edit', $post->id) }}" data-provide="tooltip" title="Edit"><i class="ti-pencil"></i></a>
                        @endcan
                        @endif

                        @if ($post->id != 1)
                        @can('delete_role')
                        {!! Form::open(array('route' => array('admin.role.destroy', $post->id), 'method' => 'delete' , 'onsubmit'
                        => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                        <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit" class="btn btn-pure table-action hover-danger confirmation-popup">
                        <i class="ti-trash"></i>
                        </button>
                        {!! Form::close() !!}
                        @endcan
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <footer class="card-footer text-center">
        {{ $posts->links() }}
    </footer>
</div>
@endsection
