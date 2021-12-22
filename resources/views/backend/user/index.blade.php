@extends('backend.layouts.default')

@section('title', __('Users'))

@section('content')
<div class="card">
    <header class="card-header">
        <h4 class="card-title">
            @can('add_user')
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary text-white">{{ __('New') }}</a>
            @endcan
            <!--<a href="{{ route('admin.user.batch-upload.create') }}" class="btn btn-primary text-white">{{ __('Batch Upload') }}</a>-->
        </h4>
        <form action="{{ route('admin.user.index') }}" method="get">
            <div class="card-header-actions">
                <div class="lookup lookup-right d-none d-lg-block">
                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                </div>
                {!! Form::select('type', $accessible_rights, request('type', ''), ['class' => 'form-control']) !!}
                {!! Form::select('ec_college', $ec_colleges, request('ec_college', ''), ['placeholder' => '-Education
                College-', 'class' => 'form-control']) !!}
                {!! Form::select('role_name', $roles, request('role_name', ''), ['placeholder' => '-Role-', 'class' =>
                'form-control']) !!}
                {!! Form::select('verified', $yes_no, request('verified', ''), ['placeholder' => '-Verified-', 'class'
                => 'form-control']) !!}
                {!! Form::select('approved',
                $approvalStatus, request('approved'), ['class' => 'form-control', 'placeholder' => '-Select
                Status-' ]) !!}

                <button class="btn btn-primary">{{__('Search') }}</button>
                <a href="{{ route('admin.user.index') }}" class="btn">{{ __('Reset') }}</a>
            </div>
        </form>
    </header>

    <div class="card-body">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer">
            <thead>
                <tr>
                    <th width="60">@sortablelink('id', 'ID')</th>
                    <th>@sortablelink('name', 'Name')</th>
                    <th>@sortablelink('type', 'Accessible Right')</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Education College') }}</th>
                    <th>{{ __('Verified') }}</th>
                    <th>{{ __('Approved') }}</th>
                    <th>@sortablelink('created_at', 'Created At')</th>
                    <th width="140" class="text-center">{{__('Actions') }}</th>
                </tr>
            </thead>

            <tbody>

                @foreach($posts as $post)
                <tr class="{{ $post->approved == App\User::APPROVAL_STATUS_BLOCKED ? 'bg-pale-pink' : '' }}">
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->name }}
                        <br>
                        <span class="text-cyan">{{ $post->email }}</span>
                    </td>
                    <td>{{ $post->type }}</td>
                    <td>
                        @foreach($post->roles as $role)
                        {{-- $post->roles->contains('id', $role->id) --}}
                        {{ $role->name }}
                        @endforeach
                    </td>
                    <td>
                        {{ $post->college->title ?? '' }}
                        @if ($post->user_type)
                        <div>({{ $post->getStaffType() }})</div>
                        @endif
                    </td>
                    <td class="text-left">
                        {{ $post->verified == 1 ? __('Yes'): __('No') }}
                    </td>
                    <td class="text-left">
                        {{ $post->getApprovalStatus($post->approved) }}
                    </td>
                    <td>{{ $post->created_at }}</td>
                    {{--<td>{{ $post->updated_at }}</td>--}}
                    <td class="text-right table-options">
                        @if ($post->id != 1)
                        @can('edit_user')
                        <a class="table-action hover-primary cat-edit" href="{{ route('admin.user.edit', $post->id) }}"
                            data-provide="tooltip" title="Edit"><i class="ti-pencil"></i></a>
                        @endcan
                        @endif

                        @if ($post->id != 1)
                        @can('delete_user')
                        {!! Form::open(array('route' => array('admin.user.destroy', $post->id), 'method' => 'delete' ,
                        'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display:
                        inline', '')) !!}
                        <button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit"
                            class="btn btn-pure table-action hover-danger confirmation-popup">
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
        <p>
            {{ __('Legend') }}: <span class="bg-pale-pink"
                style="display: inline-block; width: 50px; height: 50">&nbsp;</span> {{ __('User is blocked.')}}
        </p>
    </footer>
</div>
@endsection
