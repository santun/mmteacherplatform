@extends('backend.layouts.default')

@section('title', __('Years'))

@section('content')`
    <div class="card">
        <header class="card-header">
            <h4 class="card-title">
                @can('add_year')
                    <a href="{{ route('admin.year.create') }}" class="btn btn-primary text-white">{{ __('New') }}</a>
                @endcan
            </h4>
            <form action="{{ route('admin.year.index') }}" method="get">
                <div class="card-header-actions">
                    <div class="lookup lookup-right d-none d-lg-block">
                        <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-primary">{{ __('Search') }}</button>
                    <a href="{{ route('admin.year.index') }}" class="btn">{{ __('Reset') }}</a>
                </div>
            </form>
        </header>

        <div class="card-body">
            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                <thead>
                <tr>
                    <th width="60">@sortablelink('id', __('ID'))</th>
                    <th>@sortablelink('title', __('Title'))</th>
                    <th width="100">{{ __('Published') }}</th>
                    <th width="160">@sortablelink('created_at', __('Created At'))</th>
                    <th width="160" class="text-center">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td class="text-center">
                            {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                        </td>
                        <td>{{ $post->created_at }}</td>
                        <td class="text-right table-options">
                            @can('edit_year')
                                <a class="table-action hover-primary cat-edit"
                                   href="{{ route('admin.year.edit', $post->id) }}" data-provide="tooltip"
                                   title="Edit"><i class="ti-pencil"></i></a>
                            @endcan

                            @can('delete_year')
                                {!! Form::open(array('route' => array('admin.year.destroy', $post->id), 'method' => 'delete' , 'onsubmit'	=> 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit" class="btn btn-pure table-action hover-danger confirmation-popup">
                                    <i class="ti-trash"></i>
                                </button>
                                {!! Form::close() !!}
                            @endcan
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
