@extends('layouts.default')

@section('title', __('Login'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white" style="background-color: #4CAF50">
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <!-- <h1>{{ __('Login') }}</h1>-->
                </div>
            </div>
        </div>
    </header>
</div>

@endsection

@section('content')
<main class="main-content">
    <section class="section bg-gray" style="background-image:  url({{ asset('assets/img/bg/2.jpg') }});">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card my-4">
                        <div class="bg-white rounded shadow-7 w-400 mw-100 p-6">

                            <h5 class="mb-7 text-center">Login to eLibrary</h5>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                @if ($message = Session::get('approve'))
                                <div class="alert alert-danger alert-block">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <div>{{ __('Error') }}</div>
                                    {{ $message }}
                                </div>
                                @endif
                                <div class="form-group">
                                    <!--<input type="text" class="form-control" name="username" placeholder="Username">-->
                                    <input id="email" type="text"
                                        class="form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email" placeholder="Username or Email Address"
                                        value="{{ old('username') ?: old('email') }}" required autofocus>
                                    @if ($errors->has('username') || $errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <!--<input type="password" class="form-control" name="password" placeholder="Password">-->
                                    <input id="password" type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="Password" name="password" required>
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group flexbox py-3 d-flex justify-content-end">
                                    <a class="text-muted small-2"
                                        href="{{ route('auth.get.password_reset_option') }}">Forgot password?</a>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-block btn-primary" type="submit">Login</button>
                                </div>
                            </form>



                            <hr class="w-30">

                            <p class="text-center text-muted small-2">Don't have an account? <a
                                    href="{{ route('register') }}">Register here</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </section>
</main>
@endsection
