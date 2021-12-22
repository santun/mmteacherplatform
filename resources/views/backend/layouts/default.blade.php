<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>@yield('title') - {{ config('app.name' ) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ config('app.url') }}" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i|Dosis:300,500" rel="stylesheet">
    @if (App::isLocale('my-ZG'))
        <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=zawgyi' />
    @else
        <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=pyidaungsu' />
        <link href="https://fonts.googleapis.com/earlyaccess/notosansmyanmar.css" rel="stylesheet">
    @endif

    @section('css')
    <!-- Styles -->
    <link href="{{ asset('assets/backend/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/tag-editor/jquery.tag-editor.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
    @show

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
</head>

<body class="{{ App::getLocale() }} @yield('body-css')">
    @if (config('cms.enable_preloader'))
    <!-- Preloader -->
    <div class="preloader">
        <div class="spinner-dots">
            <span class="dot1"></span>
            <span class="dot2"></span>
            <span class="dot3"></span>
        </div>
    </div>
    @endif

    <!-- Sidebar -->
    <aside class="sidebar sidebar-expand-lg sidebar-light sidebar-sm sidebar-color-info">

        <header class="sidebar-header bg-info">
            <span class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <!--<strong>{{ config('app.name') }}</strong>-->
            <img src="{{ asset('assets/img/logo-dark.png') }}" srcset="{{ asset('assets/img/logo-dark@2x.png') }} 2x" alt="{{ config('app.name') }}">
            </a>
        </span>
            <span class="sidebar-toggle-fold text-primary"></span>
        </header>

        <nav class="sidebar-navigation">
            <ul class="menu menu-sm menu-bordery">
                <li class="menu-item {{ Request::is('*/backend')? 'active' : '' }} ">
                    <a class="menu-link" href="{{ route('admin.dashboard') }}">
                        <span class="icon ti-home"></span>
                        <span class="title">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <li class="menu-category">{{ __('eLibrary') }}</li>

                <li class="menu-item {{ Request::is('*/backend/resource*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.resource.index') }}">
                        <span class="icon ti-gallery"></span>
                        <span class="title">{{ __('Resources') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/subject*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.subject.index') }}">
                        <span class="icon ti-agenda"></span>
                        <span class="title">{{ __('Subjects') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/year*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.year.index') }}">
                        <span class="icon ti-time"></span>
                        <span class="title">{{ __('Years') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/college*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.college.index') }}">
                        <span class="icon ti-ruler-pencil"></span>
                        <span class="title">{{ __('Education Colleges') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/license*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.license.index') }}">
                        <span class="icon ti-layers-alt"></span>
                        <span class="title">{{ __('Licenses') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/keyword*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.keyword.index') }}">
                        <span class="icon ti-tag"></span>
                        <span class="title">{{ __('Keywords') }}</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('*/backend/contact*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.contact.index') }}">
                        <span class="icon ti-email"></span>
                        <span class="title">{{ __('Contact Messages') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/link-report*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.link-report.index') }}">
                        <span class="icon ti-link"></span>
                        <span class="title">{{ __('Link Reports') }}</span>
                    </a>
                </li>

                <li class="menu-category">{{ __('eLearning') }}</li>

                <li class="menu-item {{ Request::is('*/backend/course*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.course.index') }}">
                        <span class="icon ti-gallery"></span>
                        <span class="title">{{ __('Courses') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/course-category*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.course-category.index') }}">
                        <span class="icon ti-gallery"></span>
                        <span class="title">{{ __('Course Categories') }}</span>
                    </a>
                </li>

                <li class="menu-category">{{ __('CMS') }}</li>

                <li class="menu-item {{ Request::is('*/backend/page*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.page.index') }}">
                        <span class="icon ti-write"></span>
                        <span class="title">{{ __('Pages') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/slide*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.slide.index') }}">
                        <span class="icon ti-image"></span>
                        <span class="title">{{ __('Slides') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/article') || Request::is('*/backend/article/*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.article.index') }}">
                        <span class="icon ti-notepad"></span>
                        <span class="title">{{ __('Articles') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/article-category*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.article-category.index') }}">
                        <span class="icon ti-notepad"></span>
                        <span class="title">{{ __('Article Categories') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/faq') || Request::is('*/backend/faq/*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.faq.index') }}">
                        <span class="icon ti-announcement"></span>
                        <span class="title">{{ __('FAQs') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/faq-category*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.faq-category.index') }}">
                        <span class="icon ti-announcement"></span>
                        <span class="title">{{ __('FAQ Categories') }}</span>
                    </a>
                </li>
{{--
                <li class="menu-item {{ Request::is('*/backend/block*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.block.index') }}">
                        <span class="icon ti-layout"></span>
                        <span class="title">{{ __('Blocks') }}</span>
                    </a>
                </li>
 --}}
                @can('import_resource')
                <li class="menu-item {{ Request::is('*/backend/import/resource*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.resource.bulk-import') }}">
                        <span class="icon ti-import"></span>
                        <span class="title">{{ __('Import Resources') }}</span>
                    </a>
                </li>
                @endcan

                @can('import_user')
                <li class="menu-item {{ Request::is('*/backend/import/user*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.user.bulk-import') }}">
                        <span class="icon ti-import"></span>
                        <span class="title">{{ __('Import Users') }}</span>
                    </a>
                </li>
                @endcan

                <li class="menu-item {{ Request::is('*/backend/user*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.user.index') }}">
                        <span class="icon ti-user"></span>
                        <span class="title">{{ __('Users') }}</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('*/backend/role*')? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.role.index') }}">
                        <span class="icon ti-lock"></span>
                        <span class="title">{{ __('Roles') }}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <!-- END Sidebar -->

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <h4>@yield('title')</h4>
        </div>

        <div class="topbar-right">
            <ul class="topbar-btns">
                <li class="dropdown">
                    <span class="topbar-btn" data-toggle="dropdown">
                            @if($profileThumbnail = auth()->user()->getThumbnailPath())
                            <img class="avatar" src="{{ asset($profileThumbnail) }}" alt="...">
                            @else
                            <img class="avatar" src="{{ asset('assets/img/avatar.jpg') }}" alt="...">
                            @endif

                    </span>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('admin.profile.change-password') }}"><i class="ti-user"></i> Change Password</a>

                        {{--<a class="dropdown-item" href="{{ route('admin.profile.change-profile') }}"><i class="ti-user"></i> Change Profile</a>--}}

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            <i class="ti-power-off"></i> Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
            <span class="ml-2">{{ __('Hi') }}, {{ auth()->user()->name }}</span>

            <a target="_blank" href="{{ url('/') }}">
                <i class="ti-link"></i> {{ __('View website') }}
            </a>
            <a target="_blank" href="{{ route('member.dashboard') }}">
                <i class="ti-link"></i> {{ __('Dashboard') }}
            </a>

            <div class="dropdown mr-4">
                <span class="dropdown-toggle" data-toggle="dropdown">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if (App::isLocale($localeCode))
                        {{ $properties['native'] }}
                    @endif
                    @endforeach
                </span>
                <div class="dropdown-menu dropdown-menu">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a class="dropdown-item {{ App::isLocale($localeCode) ? 'active' : '' }}" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>
    <!-- END Topbar -->

    <!-- Main container -->
    <main class="main-container">
        <div class="main-content">
            @include('frontend.partials.messages')
            @include('frontend.partials.notifications')
            @yield('content')
        </div>
        <!--/.main-content -->

        <!-- Footer -->
        <footer class="site-footer">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-center text-md-left">Copyright © {{ Carbon\Carbon::now()->year }}
                        <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </main>
    <!-- END Main container -->

    <!-- Scripts -->
    @section('js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/backend/js/core.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/script.js') }}"></script>

    <script src="{{ asset('assets/backend/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
    @show
</body>
</html>
