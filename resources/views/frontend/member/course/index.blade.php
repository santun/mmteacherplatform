@extends('frontend.layouts.default')
@section('title', __('Manage Courses'))

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-2">
                    @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-10 mx-auto">
                    <h1>{{ __('Manage Courses') }}</h1>
                    <div class="card">
                        <header class="card-header">
                            <h4 class="card-title">
                                <a href="{{ route('member.course.create') }}" class="btn btn-primary">{{ __('New') }}</a>
                            </h4>

                            <form action="{{ route('member.course.index') }}" method="get">
                                <div class="card-header-actions">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="lookup lookup-right d-none d-lg-block">
                                                <input name="search" class="form-control" placeholder="Search" type="text" value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                        {!! Form::select('course_category_id', $categories, Request::get('course_category_id'), ['class'=> 'form-control']) !!}
                                        </div>
                                        <div class="col-md-3">
                                        {!! Form::select('level_id', $levels, Request::get('level_id'), ['class' => 'form-control']) !!}
                                        </div>

                                        @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                        <div class="col-md-3">
                                        {!! Form::select('uploaded_by',
                                        $uploaded_by, request('uploaded_by'), ['class' => 'form-control', 'placeholder' => '-Uploaded By-' ])
                                        !!}
                                        </div>
                                        @endif

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                        {!! Form::select('approval_status', $approvalStatus, request('approval_status'), ['class' => 'form-control', 'placeholder' => '-Select Status-' ]) !!}
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary">{{ __('Search') }}</button>
                                            <a href="{{ url('profile/course') }}" class="btn">{{ __('Reset') }}</a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </header>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-vcenter dataTable no-footer" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="80">{{ __('No#') }}</th>
                                        <th>@sortablelink('title', __('Course Title'))</th>
                                        <th>{{ __('Course Category') }}</th>
                                        <th>{{ __('Course Level') }}</th>
                                        <th>{{ __('Approval Status') }}</th>
                                        <th>{{ __('Published') }}</th>
                                        <th>{{ __('Uploaded By') }}</th>
                                        <th>@sortablelink('created_at', __('Created At'))</th>
                                        <th width="100px" class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $key => $post)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->category->name }}</td>
                                        <td>
                                            {{--<div class="text-center">
                                            @if (!empty($post->cover_image))
                                                <img src="{{ asset('assets/course/cover_image/'.$post->id.'/'.$post->cover_image) }}" width="100" height="80">
                                            @endif
                                            </div>--}}
                                            {{ $post->getLevel() }}
                                        </td>
                                        <td>
                                            @if ($post->approval_status === null)
                                            <!--
                                            <a class="btn btn-primary" href="{{ route('member.resource.submit-request', $post->id) }}" data-provide="tooltip"
                                            title="Submit">
                                            {{ __('Submit') }}
                                            </a>
                                            -->
                                            {{ __('Ready to submit') }}
                                            @else
                                            {{ $post->getApprovalStatus() }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $post->is_published ? __('Yes'): __('No') !!}
                                        </td>
                                        <td>
                                            @if( isset($post->user->name))
                                            <a href="{{ route('profile.show', $post->user->username) }}">{{ $post->user->name ?? '' }}</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $post->created_at ?? '' !!}
                                        </td>

                                        <td>
                                            <div class="btn-group btn-small">
                                                @php
                                                $canEdit = App\Repositories\CoursePermissionRepository::canEdit($post);
                                                @endphp
                                                    <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.course.show', $post->id) }}" data-provide="tooltip"
                                                        title="View"><i class="ti-eye"></i> {{ __('View') }} </a>
                                                    @if ($canEdit)
                                                    <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.course.edit', $post->id) }}" data-provide="tooltip"
                                                        title="Edit"><i class="ti-pencil"></i> {{ __('Edit') }} </a>
                                                    @endif
                                                    @if (isset($post->id) && $post->is_published != 1 && $post->approval_status == 0 && $post->isRequested == 0)
                                                    <a class="btn pr-2 pl-1 btn-outline" href="{{ route('member.course.submit-request', $post->id) }}" data-provide="tooltip" title="Request for Approval" ><i class="ti-arrow"></i> => {{ __('Request') }} </a>
                                                    @endif
                                                @if ($canEdit)
                                                <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    {!! Form::open(array('route' => array('member.course.destroy', $post->id), 'method' => 'delete'
                                                    , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                                    <button data-provide="tooltip" style="cursor: pointer" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                                    <i class="ti-trash"></i>
                                                    {{ __('Delete') }}
                                                    </button>
                                                    {!! Form::close() !!}
                                                    @if(count($post->courseLearners) != 0)
                                                    <a class="btn pr-2 pl-1 btn-outline dropdown-item" href="{{ route('member.take-course-user', $post->id) }}" data-provide="tooltip" title="View Take Course Users"><i class="ti-eye"></i> {{ __('Take Course User') }} </a>
                                                    @endif
                                                    <a class="btn pr-2 pl-1 btn-outline dropdown-item" href="{{ route('member.lecture.create', $post->id) }}" data-provide="tooltip" title="New Lecture" ><i class="ti-plus"></i> {{ __('New Lecture') }} </a>
                                                    <a class="btn pr-2 pl-1 btn-outline dropdown-item" href="{{ route('member.quiz.create', $post->id) }}" data-provide="tooltip" title="New Quiz" ><i class="ti-plus"></i> {{ __('New Quiz') }} </a>
                                                    <a class="btn pr-2 pl-1 btn-outline dropdown-item" href="{{ route('member.assignment.create', $post->id) }}" data-provide="tooltip" title="New Assignment" ><i class="ti-plus"></i> {{ __('New Assignment') }} </a>
                                                </div>
                                                @endif
                                            </div>
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
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
