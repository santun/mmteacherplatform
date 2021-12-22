@extends('frontend.layouts.default')

@section('title', __('Change Password'))

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
                    <div class="container">
                        <div class="row gap-y">
                            <div class="col-md-6">

                                <form method="post" action="{{ route('member.change-password.update') }}" class="{{ $errors->any() ? 'was-validated' : '' }}">
                                    {{ csrf_field() }}

                                    <h1>{{ __('Change Password') }}</h1>

                                    <div class="form-group">
                                        <label for="password" class="require">@lang('Current Password')</label>
                                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password"
                                            value="" placeholder=""> {!! $errors->first('password',
                                        '
                                        <div class="invalid-feedback">:message</div>') !!}
                                        <small class="form-text text-muted">Type your current password first.</small>
                                    </div>

                                    <hr>
                                    <div class="form-group">
                                        <label for="new_password" class="require">@lang('New Password')</label>
                                        <input type="password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" name="new_password" id="new_password"
                                            value="" placeholder=""> {!! $errors->first('new_password',
                                        '
                                        <div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password_confirmation">@lang('Confirm Password')</label>
                                        <input type="password" class="form-control{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" name="new_password_confirmation"
                                            id="new_password_confirmation" value="" placeholder="">                                        {!! $errors->first('new_password_confirmation', '
                                        <div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary">{{ __('Update') }}</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
