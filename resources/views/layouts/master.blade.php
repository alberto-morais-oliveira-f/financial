<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>
        @isset($title)
            @if ($title !== '')
                {{$title}}
            @else
                Financial Module
            @endif
        @endisset
    </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('vendor/financial/images/favicon.ico') }}"/>

    <!-- Loader -->
    <link href="{{ asset('vendor/financial/css/light-loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/financial/css/dark-loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('vendor/financial/js/loader.js') }}"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/financial/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/financial/plugins/notification/snackbar/snackbar.min.css') }}">

    <!-- Main CSS -->
    <link href="{{ asset('vendor/financial/css/light-main.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/financial/css/dark-main.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('vendor/financial/css/light-structure.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/financial/css/dark-structure.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('vendor/financial/css/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/financial/css/monokai-sublime.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('vendor/financial/plugins/waves/waves.min.css') }}">

    <!-- Custom Styles -->
    <link href="{{ asset('vendor/financial/css/custom-pagination.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/financial/css/custom-snackbar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/financial/css/my.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body:not(.dark) .logo-light { display: block; }
        body:not(.dark) .logo-dark { display: none; }
        body.dark .logo-light { display: none; }
        body.dark .logo-dark { display: block; }
    </style>

    @isset($scrollspy)
        @if ($scrollspy)
            <link href="{{ asset('vendor/financial/css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
        @endif
    @endisset
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @yield('styles')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
</head>
<body class="{{ Request::routeIs('error404') ? 'error text-center' : '' }}
    {{ Request::routeIs('maintenance') ? 'maintanence text-center' : '' }}
    {{
        (Request::routeIs('boxedSignIn') ||
        Request::routeIs('boxedSignUp') ||
        Request::routeIs('boxedLockscreen') ||
        Request::routeIs('boxedPasswordReset') ||
        Request::routeIs('boxed2sv')) ? 'form' : ''
    }}
    {{
        (Request::routeIs('coverSignIn') ||
        Request::routeIs('coverSignUp') ||
        Request::routeIs('coverLockscreen') ||
        Request::routeIs('coverPasswordReset') ||
        Request::routeIs('cover2sv')) ? 'form' : ''
    }}
    {{ Request::routeIs('collapsed') ? 'alt-menu' : '' }}" layout="{{ Request::routeIs('boxed') ? 'boxed' : '' }}">
@yield('body-scripts')

@isset($simplePage)

    @if ($simplePage)

        @yield('content')

    @else

        @if (!Request::routeIs('blank'))
            <!--  BEGIN NAVBAR  -->
            @include('financial::layouts.navbar')
            <!--  END NAVBAR  -->
        @endif

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">

            <div class="overlay"></div>
            <div class="search-overlay"></div>

            @if (!Request::routeIs('blank'))
                <!--  BEGIN SIDEBAR  -->
                @include('financial::layouts.sidebar')
                <!--  END SIDEBAR  -->
            @endif

            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content {{ Request::routeIs('blank') ? 'ms-0 mt-0' : '' }}">

                @isset($scrollspy)

                    @if ($scrollspy)
                        <div class="container">
                            <div class="container">
                                <div class="middle-content container-xxl p-0">

                                    <!--  BEGIN BREADCRUMBS  -->
                                    @include('financial::layouts.secondaryNav')
                                    <!--  END BREADCRUMBS  -->

                                    <!--  BEGIN CONTENT  -->
                                    @yield('content')
                                    <!--  END CONTENT  -->

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="layout-px-spacing">
                            <div class="middle-content {{ Request::routeIs('boxed') ? 'container-xxl' : '' }} p-0">
                                @if ($noShowBreadCrumb === false)
                                    <div class="row">
                                        <div class="page-meta mb-4">
                                            <nav class="breadcrumb-style-one d-inline-block mt-2"
                                                 aria-label="breadcrumb">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="#">{{$title ?? ''}}</a></li>
                                                </ol>
                                            </nav>
                                            @yield('btn_create')
                                        </div>
                                    </div>

                                    @if ((! Request::routeIs('blank')))
                                        <!--  BEGIN BREADCRUMBS  -->
                                        @include('financial::layouts.secondaryNav')
                                        <!--  END BREADCRUMBS  -->
                                    @endif
                                @endif

                                <!--  BEGIN CONTENT  -->
                                @yield('content')
                                <!--  END CONTENT  -->
                            </div>

                        </div>
                    @endif

                @endisset

                @if (!Request::routeIs('blank'))
                    <!--  BEGIN FOOTER  -->
                    @include('financial::layouts.footer')
                    <!--  END FOOTER  -->
                @endif
            </div>
            <!--  END CONTENT AREA  -->

        </div>
<!-- END MAIN CONTAINER -->

    @endif

@endisset

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{ asset('vendor/financial/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('vendor/financial/plugins/mousetrap/mousetrap.min.js') }}"></script>
<script src="{{ asset('vendor/financial/plugins/waves/waves.min.js') }}"></script>
<script src="{{ asset('vendor/financial/plugins/highlight/highlight.pack.js') }}"></script>
<script src="{{ asset('vendor/financial/plugins/notification/snackbar/snackbar.min.js') }}"></script>
<script src="{{ asset('vendor/financial/plugins/notify/bootstrap-notify.min.js') }}"></script>

<script src="{{ asset('vendor/financial/js/custom-snackbar.js') }}"></script>
<script src="{{ asset('vendor/financial/js/app.js') }}"></script>

@isset($scrollspy)
    @if ($scrollspy)
        <script src="{{ asset('vendor/financial/js/scrollspyNav.js') }}"></script>
    @endif
@endisset

<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@yield('scripts')
@yield('js')

@stack('scripts')
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@if((isset($errors) && ! $errors->isEmpty()) or session()->has('error'))
    @php
        $errors = collect([session()->get('error')]);
    @endphp
    <script>
        // Notification logic
    </script>
@endif

<script src="{{ asset('vendor/financial/plugins/font-icons/feather/feather.min.js') }}"></script>
<script>
    feather.replace();
</script>
@livewire('bootstrap-modal')
</body>
</html>