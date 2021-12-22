@extends('layouts.default')
@section('title', __('Account Verification'))

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
	<section class="section bg-gray" style="background-image:  url({{ asset('assets/img/bg/1.jpg') }});">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-5">

					<div class="card">
						<div class="bg-white rounded shadow-7 mw-100 p-6">
							<h5 class="mb-7">{{ __('Account Verification') }}</h5>

							@if (session('status'))
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
									<br>
									<a href="{{ route('auth.request.otp') }}">Resend OTP?</a>
								</div>
							@endif

							<form method="POST" action="{{ route('auth.verify.post_otp') }}">
							<!-- @csrf-->

                            {{ csrf_field() }}
                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <h4>{{ __('Error') }}</h4>
                                    {{ $message }}
                                </div>
                            @endif

								<div class="form-group ">

									<input id="otp_code" type="text" class="form-control{{ $errors->has('otp_code') ? ' is-invalid' : '' }}" name="otp_code" placeholder="Enter OTP" value="{{ old('otp_code') }}" >
									@if ($errors->has('otp_code'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('otp_code') }}</strong>
										</span>
									@endif

								</div>

								<div class="form-group ">
									<button class="btn btn-block btn-primary" type="submit">
										{{ __('VERIFY') }}
									</button>
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
