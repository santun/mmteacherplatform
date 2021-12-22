<!-- Topbar -->
<section class="topbar d-lg-flex">
    @include('frontend.layouts.partials.topbar')
</section>
<!-- /.topbar -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg @yield('navbar-css', 'navbar-dark') navbar-stick-dark" data-navbar="fixed">
    <div class="container">
        <div class="navbar-left">
            <button class="navbar-toggler" type="button">&#9776;</button>
            <a class="navbar-brand" href="{{ url('/'.LaravelLocalization::setLocale()) }}">
                <img class="logo-dark" src="{{ asset('assets/img/logo-dark.png') }}" srcset="{{ asset('assets/img/logo-dark@2x.png') }} 2x" alt="{{ config('app.name') }}" height="50px">
            </a>
        </div>

        <section class="navbar-mobile">
            <nav class="nav nav-navbar ml-auto">
                <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                <a class="nav-link" href="{{ route('article.index') }}">{{ __('Media') }}</a>
                <a class="nav-link" href="{{ route('elibrary.index') }}">{{ __('E-Library') }}</a>
                @if(request()->is( '*/e-learning*'))
                    <a class="nav-link" href="{{ route('courses.index') }}">{{ __('Available Courses') }}</a>
                    @if(auth()->check())
                        <a class="nav-link" href="{{ route('courses.my-courses') }}">{{ __('My Courses') }}</a>
                    @endif
                @else
                    <a class="nav-link" href="{{ route('courses.index') }}">{{ __('E-Learning') }}</a>
                @endif

            </nav>
            <div class="d-block d-sm-block d-md-block d-lg-none d-xl-none">
            @include('frontend.layouts.partials.topbar')
            </div>
        </section>
    </div>
</nav>
<!-- /.navbar -->
