@extends('frontend.layouts.default')
@section('title', __('Edit User') . ' - '. $post->name)

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">

    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">

            <div class="row">
                <div class="col-md-3">
                    @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-9">
                    <h1>{{ __('Edit User') }}</h1>

                    <div class="container">
                        <div class="row gap-y">
                            {!! \Form::open(array('method' => 'put', 'route' =>
                           array('member.user.update', $post->id),
                            'class' => $errors->any() ? 'form-horizontal was-validated' : 'form-horizontal')) !!}

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="name" class="col-xs-12">{{ __('Name') }}</label>
                                        {{ $post->name }}
                                    </div>

                                    <div class="form-group">
                                        <label for="username" class="col-xs-12">{{ __('Username') }}</label>
                                       {{ $post->username }}
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-xs-12">{{ __('Email') }}</label>
                                        {{ $post->email }}
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile_no" class="require">{{ __('Mobile No.') }}
                                        </label>

                                        {{ $post->mobile_no }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5>{{ __('Student/Teacher/Staff Information') }}</h5>
                                    <div class="form-group {!! $errors->first('user_type', 'has-error') !!}">
                                        <label for="user_type" class="col-xs-12">{{ __('Type of Users') }}</label>
                                        {!! Form::select('user_type', $user_types, old('user_type',
                                        isset($post->user_type) ? $post->user_type : ''), ['class' => $errors->has('user_type')
                                        ? 'form-control is-invalid' : 'form-control']) !!}
                                        {!! $errors->first('user_type','<div class="invalid-feedback">:message</div>')
                                        !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="ec_college" class="col-xs-12">{{ __('Education College') }}</label>
                                        {!! Form::select('ec_college',
                                        $ec_colleges, old('ec_college', isset($post->ec_college) ? $post->ec_college :
                                        ''), ['class' => $errors->has('ec_college')
                                        ? 'form-control is-invalid' : 'form-control']) !!}
                                        {!! $errors->first('ec_college', '<div class="invalid-feedback">:message</div>')
                                        !!}

                                    </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-primary">{{ __('Update') }}</button>
                                </div>
                            </div>

                        </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>
@endsection

@section('script') @parent
<script>
    $(function() {//alert("P");
		//$('.subject-div').hide();
		if($('.user_types').val() == 'education_college_teaching_staff') {
			$('.subject-div').show();
		} else {
			$('.subject-div').hide();
		}

		$('.user_types').change(function(){//alert("K");
			if($('.user_types').val() == 'education_college_teaching_staff') {
				$('.subject-div').show();
			} else {
				$('.subject-div').hide();
			}
		});
	});
</script>
@endsection
