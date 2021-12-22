@extends('backend.layouts.default')

@section('title', __('User'))

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
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.user.update',
                    $post->id), 'class' => 'form-horizontal')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.user.store', 'class' => 'form-horizontal'))
                    !!}
                    @endif
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-8">

                            <div class="form-group">
                                <label for="name" class="col-xs-12 require">@lang('Name')</label>
                                <input type="text" placeholder="Name.." name="name" id="name"
                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="username" class="col-xs-12 require">@lang('Username')</label>
                                <input type="text" placeholder="Username.." name="username" id="username"
                                    class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                    value="{{ old('username', isset($post->username) ? $post->username : '') }}">
                                {!! $errors->first('username', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-xs-12 require">@lang('Email')</label>
                                <input type="text" placeholder="Email.." name="email" id="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email', isset($post->email) ? $post->email: '') }}">
                                {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="mobile_no" class="require">@lang('Mobile No.')
                                    <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                        data-placement="top"
                                        data-original-title="Mobile number starting with Country code. e.g., Please enter +959123456789 for 09123456789"></i>
                                </label>

                                <input type="text" placeholder="Mobile No..." name="mobile_no" id="mobile_no"
                                    class="form-control{{ $errors->has('mobile_no') ? ' is-invalid' : '' }}"
                                    value="{{ old('mobile_no', isset($post->mobile_no) ? $post->mobile_no : '') }}">
                                {!! $errors->first('mobile_no', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="ec_college" class="col-xs-12">@lang('Education College')</label>
                                {{--<input type="text" placeholder="Education College..." name="ec_college" id="ec_college" class="form-control{{ $errors->has('ec_college') ? ' is-invalid' : '' }}"
                                value="{{ old('ec_college', isset($post->ec_college) ? $post->ec_college : '') }}">--}}
                                {!! Form::select('ec_college', $ec_colleges, old('ec_college', isset($post->ec_college)
                                ? $post->ec_college : ''), ['class' => $errors->has('ec_college') ? 'form-control
                                is-invalid' : 'form-control']) !!}
                                {!! $errors->first('ec_college', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group {!! $errors->first('user_type', 'has-error') !!}">
                                <label for="user_type" class="col-xs-12">@lang('Type of Users')</label>
                                {!! Form::select('user_type', $user_types, old('user_type', isset($post->user_type) ?
                                $post->user_type : ''), ['class' => 'form-control user_types']) !!}
                                {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group {!! $errors->first('type', 'has-error') !!}">
                                <label for="type" class="col-xs-12 require">@lang('Accessible Right') </label>
                                {!! Form::select('type', $privilege_types, old('type', isset($post->type) ? $post->type
                                : ''), ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control'])
                                !!}
                                {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group {!! $errors->first('notification_channel', 'has-error') !!}">
                                <label for="notification_methoad"
                                    class="col-form-label text-md-right">{{ __('Notification Channel') }}</label>

                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" checked="" disabled="" type="checkbox"
                                        name="notification_channel[]" value="email">
                                    <label class="custom-control-label">{{ __('Email') }} (Default)</label>
                                </div>

                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="hidden" name="notification_channel[]"
                                        value="email">
                                    <input class="custom-control-input" type="checkbox" name="notification_channel[]"
                                        value="sms"
                                        {{ isset($post->notification_channel) && $post->notification_channel == 'sms' ? 'checked=checked' : '' }}>
                                    <label class="custom-control-label">{{ __('SMS') }}</label>
                                </div>

                                {!! $errors->first('notification_channel', '<div class="invalid-feedback">:message</div>
                                ') !!}
                            </div>

                        @if (isset($post))
                        <div class="alert alert-info">
                            <i class="fa fa-info"></i> If you want to reset the password, please type Password and
                            Confirm Password. If not, please leave the both fields blank.
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="password" class="col-xs-12 require">@lang('Password')</label>
                            <input type="password" placeholder="Password.." name="password" id="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="">
                            {!! $errors->first('password', '
                            <div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-xs-12">@lang('Confirm Password')</label>
                            <input type="password" placeholder="Confirm Password.." name="password_confirmation"
                                id="password_confirmation" class="form-control" value="">
                            {!! $errors->first('password_confirmation', '<div class="invalid-feedback">:message</div>')
                            !!}
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="btnSave" value="1">{{ __('Save') }}</button>
                            {{-- <button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply</button>--}}
                            <a href="{{ route('admin.user.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                        </div>
                    </div>

                    <div class="col-sm-4">
                    <h5><b>{{ __('Roles') }}</b></h5>
                    <div class='form-group'>
                        @foreach ($roles as $role)

                        <div class="custom-control custom-radio">

                            @if (isset($post) && $post->roles)
                            {{ Form::radio('roles', $role->id, $post->roles->contains('id', $role->id), ['id' => 'role_' . $role->id, 'class' => $errors->has('roles')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}
                            @else
                            {{ Form::radio('roles', $role->id, '', ['id' => 'role_' . $role->id, 'class' => $errors->has('roles')? 'custom-control-input is-invalid' : 'custom-control-input']) }}
                            @endif
                            {{ Form::label('role_' . $role->id, $role->name, ['class' => 'custom-control-label']) }}
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label for="suitable_for_ec_year" class="col-xs-12">@lang('Year of study/teaching')</label>

                        @php
                        $years = \App\Repositories\ResourceRepository::getEducationCollegeYears();
                        @endphp

                        @foreach ($years as $key => $year)

                        <div class="custom-control custom-checkbox">

                            @if (isset($post))
                            @php
                            $ec_years = array();
                            if($post->suitable_for_ec_year)
                            {
                                if (strpos($post->suitable_for_ec_year, ',') !== false) {
                                $ec_years = explode(',', $post->suitable_for_ec_year);
                                }
                                else $ec_years[] = $post->suitable_for_ec_year;
                                }

                            @endphp

                            {{ Form::checkbox('suitable_for_ec_year[]', $key, in_array($key, $ec_years)? true : false, ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}

                            @else

                            {{ Form::checkbox('suitable_for_ec_year[]', $key, '', ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'custom-control-input is-invalid' : 'custom-control-input']) }}

                            @endif

                            {{ Form::label('ecy_' . $key, $year, ['class' => 'custom-control-label']) }}

                            {!! $errors->first('suitable_for_ec_year', '<div class="invalid-feedback">:message</div>')
                            !!}
                        </div>
                        @endforeach


                    </div>

                    <div class="subject-div form-group">
                        <label for="subjects" class="col-xs-12">@lang('Subject(s)/ Learning Area(s) that you
                            teach')</label>

                        @foreach ($subjects as $subject)

                        <div class="custom-control custom-checkbox">

                            @if (isset($post))

                            @php
                            $subjects = array();
                            if($post->subjects) {

                            $post_subjects = $post->subjects;
                            foreach ($post_subjects as $post_subject) {
                            $subjects[] = $post_subject->id;
                            }
                            }
                            @endphp

                            {{ Form::checkbox('subjects[]', $subject->id, in_array($subject->id, $subjects)? true : false, ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}

                            @else

                            {{ Form::checkbox('subjects[]', $subject->id, '', ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'custom-control-input is-invalid' : 'custom-control-input']) }}

                            @endif

                            {{ Form::label('sub_' . $subject->id, $subject->title, ['class' => 'custom-control-label']) }}<br>
                        </div>
                        @endforeach

                        {!! $errors->first('subjects', '<div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group {!! $errors->first('profile_image', 'has-error') !!}">
                        <div class="form-group col-xs-12">
                            <label for="published">@lang('Profile Image')</label>

                            {{ \Form::file('profile_image', ['class' => $errors->has('profile_image') ? 'form-control is-invalid' : 'form-control']) }}

                            {!! $errors->first('profile_image', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        @if (isset($post))
                        @php
                        $images = $post->getMedia('profile'); //optional($post->getMedia('profile')->first()); //
                        @endphp

                        <div class="form-group col-xs-12">
                            @foreach($images as $image)
                            <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                <img src="{{ asset($image->getUrl('thumb')) }}">
                            </a>

                            <a class="col-md-3" onclick="return confirm('Are you sure you want to delete?')"
                                href="{{ route('member.media.destroy', $image->id) }}">Remove</a>
                            @endforeach
                        </div>

                        @endif
                    </div>
                    @can('verify_user')
                    <div class="form-group {!! $errors->first('verified', 'has-error') !!}">
                        <label for="published" class="col-xs-12">@lang('Verified')?</label>

                        <div class="custom-control custom-checkbox">
                            {{ Form::checkbox('verified', 1, (!empty($post->verified) ? 1 : 0 ), ['id' => 'verified', 'class' => "custom-control-input"]) }}
                            <label class="custom-control-label">@lang('Verified')</label>
                        </div>
                        {!! $errors->first('verified', '<div class="invalid-feedback">:message</div>') !!}
                </div>
                @endcan

                @can('approve_user')
                <div class="form-group">
                    <label for="approved" class="col-xs-12 require">{{__('Approval Status')}}</label>
                    {!! Form::select('approved', $approvalStatus,
                    old('approved', isset($post->approved) ? $post->approved: ''),
                    ['placeholder' => '-Approval Status-', 'class' => $errors->has('approved')?
                    'form-control
                    is-invalid' : 'form-control' ]) !!}
                    {!! $errors->first('approved', '<div class="invalid-feedback">:message</div>') !!}
                </div>
                @endcan

            </div>

            </form>
        </div>
    </div>
</div>
<!-- END Default Elements -->
</div>
</div>
</div>
@stop

@section('js')
@parent
<script>
$(function() {
    //$('.subject-div').hide();
    if($('.user_types').val() == 'education_college_teaching_staff') {
        $('.subject-div').show();
    } else {
        $('.subject-div').hide();
    }

    $('.user_types').change(function(){
        if($('.user_types').val() == 'education_college_teaching_staff') {
            $('.subject-div').show();
        } else {
            $('.subject-div').hide();
        }
        });
    });
</script>
@endsection
