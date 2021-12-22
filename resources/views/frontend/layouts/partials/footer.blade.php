<footer class="footer" style="background-color: #4CAF50">
    <div class="container">
        <div class="row gap-y align-items-center">
            <div class="col-lg-4">
                <div class="nav nav-trim justify-content-lg-center">
                    <a class="nav-link text-white" href="{{ url(LaravelLocalization::setLocale().'/about-us') }}">{{ __('About Us') }}</a>
                    <a class="nav-link text-white" href="{{ url(LaravelLocalization::setLocale().'/faq') }}">{{ __('FAQs') }}</a>
                    <a class="nav-link text-white" href="{{ url(LaravelLocalization::setLocale().'/contact-us') }}">{{ __('Contact Us') }}</a>
                    <a class="nav-link text-white" href="{{ url(LaravelLocalization::setLocale().'/disclaimer') }}">{{ __('Disclaimer') }}</a>
                </div>
            </div>
            <div class="col-12 col-lg-8 text-right order-lg-last">
                <div class="social">
                <img class="mb-2" src="{{ asset('assets/img/logos/logo-UNESCO-white.png') }}" alt="UNESCO" title="UNESCO">
                <img class="ml-2 mb-2" src="{{ asset('assets/img/logos/logo-australian-aid.png') }}" alt="Australian Aid" title="Australian AID">
                <img class="ml-2" src="{{ asset('assets/img/logos/logo-FORMIN.png') }}" alt="Ministry for Foreign Affairs of Finland (FORMIN)" title="Ministry for Foreign Affairs of Finland (FORMIN)">
                <img class="ml-2" src="{{ asset('assets/img/logos/logo-ukaid.png') }}" alt="UK Aid" title="UK aid">
                </div>
            </div>
        </div>
    </div>
</footer>

<button class="btn btn-circle btn-primary scroll-top"><strong class="fa fa-angle-up"></strong><span class="d-none">Up</span></button>
