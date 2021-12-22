@extends('layouts.default')
@section('title', __('Account Verified'))

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
                            <h5 class="mb-7">{{ __('Account Verified') }}</h5>

                            <div class="alert alert-success" role="alert">
                                {{ __('Your account has been successfully verified. Once administrator has approved your account, you can log in to the system.')}}
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
</main>
@endsection
