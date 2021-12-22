@extends('layouts.default')

@section('title', __('Register'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white" style="background-color: #4CAF50">
        <!--<canvas class="constellation" data-radius="0"></canvas>-->
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <!-- <h1>{{ __('Dashboard') }}</h1>-->
                </div>
            </div>
        </div>
    </header>
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section bg-gray overflow-hidden"
        style="background-image:  url({{ asset('assets/img/bg/2.jpg') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card px-5">
                        <div class="bg-white rounded shadow-7 mw-100 p-6">
                            <h5 class="text-center mb-6">{{ __('Register to eLibrary') }}</h5>

                            <div class="alert alert-primary">
                                {{ __('Registration is only for Students, Teachers and Staff from Ministry of Education, Myanmar.') }}
                            </div>
                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Here R -->
                                        @csrf
                                        <div class="form-group">
                                            {!! Form::select('user_type', \App\Repositories\UserRepository::getUserTypes(),
                                            old('user_type'), ['class' => 'form-control
                                            user_types']) !!} @if ($errors->has('user_type'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('user_type') }}</strong>
                                            </span> @endif

                                        </div>
                                        <div class="form-group">
                                            {!! Form::select('ec_college',
                                            \App\Repositories\CollegeRepository::getItemList(true),
                                            old('ec_college'), ['class' => $errors->has('ec_college') ? 'form-control
                                            is-invalid' : 'form-control']) !!} @if ($errors->has('ec_college'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('ec_college') }}</strong>
                                            </span> @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="suitable_for_ec_year"
                                                class="col-xs-12">{{ __('Year of Study/Teaching') }}</label>
                                            @php $years = \App\Repositories\ResourceRepository::getEducationCollegeYears();
    
                                            @endphp
    
                                            @foreach ($years as $key => $year)
                                            <div class="custom-control custom-checkbox">
                                                {{ Form::checkbox('suitable_for_ec_year[]', $key, '', ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')?
                                                'custom-control-input is-invalid' : 'custom-control-input']) }} {{ Form::label('ecy_' . $key, $year, ['class' =>
                                                'custom-control-label']) }} {!! $errors->first('suitable_for_ec_year', '
                                                <div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                            @endforeach
    
                                        </div>

                                        <div class="form-group">
                                            <input id="name" type="text"
                                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                name="name" value="{{ old('name') }}" placeholder="Name" required
                                                autofocus>

                                            @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <input id="username" type="text"
                                                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                                name="username" value="{{ old('username') }}" placeholder="Username"
                                                required>

                                            @if ($errors->has('username'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <input id="email" type="email"
                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                name="email" value="{{ old('email') }}" placeholder="E-Mail Address"
                                                required>

                                            @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input id="mobile_no" type="text"
                                                    class="form-control{{ $errors->has('mobile_no') ? ' is-invalid' : '' }}"
                                                    name="mobile_no" value="{{ old('mobile_no', '+959') }}" placeholder=""
                                                    required>
                                                    @if ($errors->has('mobile_no'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mobile_no') }}</strong>
                                                    </span>
                                                    @endif
                                            </div>

                                            <small id="emailHelp"
                                                class="form-text text-muted">{!! __('Please enter your mobile phone number in International format.<br> e.g., <kbd>+959971112222</kbd>') !!}</small>

                                        </div>

                                        <div class="form-group">
                                            <input id="password" type="password"
                                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                name="password" placeholder="Password" required>

                                            @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif

                                        </div>

                                        <div class="form-group">
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" placeholder="Confirm Password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="notification_channel"
                                                class="col-form-label text-md-right">{{ __('Notification Channel') }}</label>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" checked="" disabled=""
                                                                type="checkbox" name="notification_channel[]" value="email">
                                                            <label class="custom-control-label">{{ __('Email') }} (Default)</label>
                                                        </div>               
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="hidden"
                                                                name="notification_channel[]" value="email">
                                                            <input class="custom-control-input" type="checkbox"
                                                                name="notification_channel[]" value="sms"
                                                                {{ (old('notification_channel.1') == 'sms') ? 'checked=""' : '' }}>
                                                            <label class="custom-control-label">{{ __('SMS') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                         

                                            
                                        </div>

                                        {{--
                                    <div class="form-group row">

                                        <label for="notification_methoad" class="col-md-5 col-form-label text-md-right">{{ __('Notification Channel') }}</label>

                                        <div class="col-md-6">
                                            <div class="col-md-8">
                                                <input id="notification_channel_sms" type="radio"
                                                    class="{{ $errors->has('notification_channel') ? ' is-invalid' : '' }}"
                                                    name="notification_channel" value="sms" require>

                                                <label for="notification_channel_sms"
                                                    class="col-form-label text-md-right">{{ __('SMS') }}</label>
                                            </div>

                                            <div class="col-md-8">
                                                <input id="notification_channel_email" type="radio"
                                                    class="{{ $errors->has('notification_channel') ? ' is-invalid' : '' }}"
                                                    name="notification_channel" value="email" checked="checked"
                                                    required>
                                                <label for="notification_channel_email"
                                                    class=" col-form-label text-md-right">{{ __('Email') }}</label>
                                            </div>

                                            @if ($errors->has('notification_channel'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('notification_channel') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    --}}

                                    <div class="form-group">
                                        <label for="profile_image" class="col-form-label text-md-right">@lang('Profile
                                            Image')</label>
                                        <div class="row">
                                        <div class="col-md-12">
                                            <div class="box">
                                            {{ \Form::file('profile_image', ['class' => $errors->has('profile_image') ? 'is-invalid' : '']) }}

                                            {!! $errors->first('profile_image', '<div class="invalid-feedback">:message
                                            </div>') !!}     
                                            </div>                                       
                                        </div>
                                    </div>
                                    </div>

                                </div>

                                {{-- <div class="col-md-6">
                                    <!-- Here L -->
                                    <h5>{{ __('Student/Teacher/Staff Information') }}</h5>
                                    <div class="form-group">
                                        {!! Form::select('user_type', \App\Repositories\UserRepository::getUserTypes(),
                                        old('user_type'), ['class' => 'form-control
                                        user_types']) !!} @if ($errors->has('user_type'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('user_type') }}</strong>
                                        </span> @endif

                                    </div>
                                    <div class="form-group">
                                        {!! Form::select('ec_college',
                                        \App\Repositories\CollegeRepository::getItemList(true),
                                        old('ec_college'), ['class' => $errors->has('ec_college') ? 'form-control
                                        is-invalid' : 'form-control']) !!} @if ($errors->has('ec_college'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('ec_college') }}</strong>
                                        </span> @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="suitable_for_ec_year"
                                            class="col-xs-12">{{ __('Year of Study/Teaching') }}</label>
                                        @php $years = \App\Repositories\ResourceRepository::getEducationCollegeYears();

                                        @endphp

                                        @foreach ($years as $key => $year)
                                        <div class="custom-control custom-checkbox">
                                            {{ Form::checkbox('suitable_for_ec_year[]', $key, '', ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')?
                                            'custom-control-input is-invalid' : 'custom-control-input']) }} {{ Form::label('ecy_' . $key, $year, ['class' =>
                                            'custom-control-label']) }} {!! $errors->first('suitable_for_ec_year', '
                                            <div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        @endforeach

                                    </div>

                                    <div class="subject-div" style="display: none">
                                        <div class="form-group">
                                            <label for="subjects" class="col-xs-12">
                                                {{ __('Subject(s)/ Learning Area(s) that you teach') }}
                                            </label>

                                            @php
                                            $subjects = \App\Repositories\SubjectRepository::getAllPublished();
                                            @endphp

                                            @foreach ($subjects as $subject)

                                            <div class="custom-control custom-checkbox">

                                                {{ Form::checkbox('subjects[]', $subject->id, '', ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'custom-control-input is-invalid' : 'custom-control-input' ]) }}

                                                {{ Form::label('sub_' . $subject->id, $subject->title, ['class' => 'custom-control-label']) }}
                                            </div>

                                            @endforeach

                                            @if ($errors->has('subjects'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('subjects') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                    </div>


                                </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                
                                    <button class="btn btn-primary w-100 py-3 mt-4" type="submit">{{ __('Register') }}</button>
                                
                            </div>
                        </div>

                        </form>

                        <hr class="w-30">

                        <p class="text-center text-muted small-2">Already a member? <a href="{{ route('login') }}">Login
                                here</a></p>
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>
</main>
@endsection

@section('script')
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
