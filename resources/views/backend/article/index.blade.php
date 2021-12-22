@extends('backend.layouts.default')

@section('title', 'Articles')

@section('content')
    <div class="card">
        <header class="card-header">
            <h4 class="card-title">
                @can('add_article')
                    <a href="{{ route('admin.article.create') }}" class="btn btn-primary text-white">New</a>
                @endcan
            </h4>
            <form action="{{ route('admin.article.index') }}" method="get">
                <div class="card-header-actions">
                    <div class="lookup lookup-right d-none d-lg-block">
                        <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                    </div>
                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control']) !!}
                    <button class="btn btn-primary">Search</button>
                    <a href="{{ route('admin.article.index') }}" class="btn">Reset</a>
                </div>
            </form>
        </header>

        <div class="card-body">
            <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
                <thead>
                <tr>
                    <th width="60">@sortablelink('id', 'ID')</th>
                    <th>@sortablelink('title', 'Title')</th>
                    <th>{{ __('Image') }}</th>
                    <th>@sortablelink('category_id', 'Category')</th>
                    <th>{{ __('Published') }}</th>
                    <th width="150">@sortablelink('updated_at', 'Updated At')</th>
                    <th width="150" class="text-center">{{__('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                    @php
                       function UR_exists($url){
                        $headers=get_headers($url);
                        return stripos($headers[0],"200 OK")?true:false;
                        }
                    @endphp
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>
                            
                            @if(!file_exists(public_path($post->getThumbnailPath())))
                                @foreach($post->media as $mediafile)
                                @php
                                $_filename = str_replace('-thumb','',$mediafile->file_name);
                                @endphp
                                <img src="{{ asset('storage/'.$mediafile->id.'/'.$_filename) }}" alt="{{ $post->title }}" width="150px">
                                @break
                                @endforeach
                            
                            @elseif ($img_url = $post->getThumbnailPath())
                            <img src="{{ asset($img_url) }}" alt="{{ $post->title }}">
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $post->category->title ?? '' }}</td>
                        <td class="text-center">
                            {!! $post->published ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}
                        </td>
                        <td>{{ $post->updated_at }}</td>
                        <td class="text-right table-options">
                            <a target="_blank" class="table-action hover-primary cat-edit mr-3" href="{{ url($post->path()) }}" data-provide="tooltip" title="View"><i class="ti-eye"></i></a>

                            @can('edit_article')
                                <a class="table-action hover-primary cat-edit"
                                   href="{{ route('admin.article.edit', $post->id) }}" data-provide="tooltip"
                                   title="Edit"><i class="ti-pencil"></i>
                                </a>
                            @endcan

                            @can('delete_article')
                                {!! Form::open(array('route' => array('admin.article.destroy', $post->id), 'method'
                                => 'delete' , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit"
                                        class="btn btn-pure table-action hover-danger confirmation-popup">
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
