@extends('layouts.default')
@section('title', __('Reset Password Option'))

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
				<div class="col-md-4">
					
					<div class="card">
						<div class="bg-white rounded shadow-7 w-400 mw-100 p-6">		
						<h5 class="mb-7">{{ __('Reset Password Option') }}</h5>
						
							@if (session('status'))
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
								</div>
							@endif

							<form method="POST" action="{{ route('auth.post.password_reset_option') }}">
								@csrf

								<div class="form-group">
									<label for="notification_channel" class="col-form-label text-md-right">{{ __('Choose Channel') }}</label>
									
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" checked="" disabled="" type="checkbox" name="notification_channel[]" value="email">
										<label class="custom-control-label">{{ __('Email') }} (Default)</label>
									</div>
									
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" type="hidden" name="notification_channel[]" value="email">
										<input class="custom-control-input" type="checkbox" name="notification_channel[]" value="sms">
										<label class="custom-control-label">{{ __('SMS') }}</label>
									</div>
								</div>								
								
								<div class="form-group ">
									
									<button class="btn btn-block btn-primary" type="submit">
										{{ __('Continue') }}
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
 