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
                Eduardo Gordin - BJJ
            @endif
        @endisset
    </title>
    <link rel="icon" type="image/x-icon" href="{{Vite::asset('resources/images/favicon.ico')}}"/>

    @vite(['resources/scss/layouts/modern-light-menu/light/loader.scss'])
    @vite(['resources/scss/layouts/modern-light-menu/dark/loader.scss'])
    @vite(['resources/layouts/modern-light-menu/loader.js'])

    @vite(['resources/scss/light/assets/components/font-icons.scss'])
    @vite(['resources/scss/dark/assets/components/font-icons.scss'])



    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/src/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/src/notification/snackbar/snackbar.min.css')}}">
    @vite(['resources/scss/light/assets/main.scss'])
    @vite(['resources/scss/dark/assets/main.scss'])
    @vite(['resources/scss/light/plugins/perfect-scrollbar/perfect-scrollbar.scss'])
    @vite(['resources/scss/dark/plugins/perfect-scrollbar/perfect-scrollbar.scss'])

    <link rel="stylesheet" href="{{asset('plugins/src/waves/waves.min.css')}}">
    @vite(['resources/scss/layouts/modern-light-menu/light/structure.scss'])
    @vite(['resources/scss/layouts/modern-light-menu/dark/structure.scss'])
    <link rel="stylesheet" href="{{asset('plugins/src/highlight/styles/monokai-sublime.css')}}">
    @vite(['resources/scss/light/assets/elements/custom-pagination.scss', 'resources/scss/dark/assets/elements/custom-pagination.scss'])
    @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])

    @vite(['resources/scss/my.scss'])

    <style>
        body:not(.dark) .logo-light {
            display: block;
        }

        body:not(.dark) .logo-dark {
            display: none;
        }

        body.dark .logo-light {
            display: none;
        }

        body.dark .logo-dark {
            display: block;
        }
    </style>


    @isset($scrollspy)
        @if ($scrollspy)
            @vite(['resources/scss/light/assets/scrollspyNav.scss'])
            @vite(['resources/scss/dark/assets/scrollspyNav.scss'])
        @endif
    @endisset
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @yield('styles')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @vite('resources/js/laravel-app.js')
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
<!-- BEGIN LOADER -->
<!--  END LOADER -->

@isset($simplePage)

    @if ($simplePage)

        @yield('content')

    @else

        @if (!Request::routeIs('blank'))
            <!--  BEGIN NAVBAR  -->
            @include('layouts.navbar')
            <!--  END NAVBAR  -->
        @endif

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">

            <div class="overlay"></div>
            <div class="search-overlay"></div>

            @if (!Request::routeIs('blank'))
                <!--  BEGIN SIDEBAR  -->
                @include('layouts.sidebar')
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
                                    @include('layouts.secondaryNav')
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
                                    @if ((! Request::routeIs('blank')))
                                        <!--  BEGIN BREADCRUMBS  -->
                                        @include('layouts.secondaryNav')
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
                    @include('layouts.footer')
                    <!--  END FOOTER  -->
                @endif
            </div>
            <!--  END CONTENT AREA  -->

        </div>
<!-- END MAIN CONTAINER -->

    @endif

@endisset

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{asset('plugins/src/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('plugins/src/mousetrap/mousetrap.min.js')}}"></script>
<script src="{{asset('plugins/src/waves/waves.min.js')}}"></script>
<script src="{{asset('plugins/src/highlight/highlight.pack.js')}}"></script>
<script src="{{asset('plugins/src/notification/snackbar/snackbar.min.js')}}"></script>
<script type="module" src="{{asset('plugins/src/notify/bootstrap-notify.min.js')}}"></script>

@vite(['resources/js/components/notification/custom-snackbar.js'])
@vite(['resources/layouts/vertical-light-menu/app.js'])

@isset($scrollspy)
    @if ($scrollspy)
        @vite(['resources/js/scrollspyNav.js'])
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
    <script type="module">
        @foreach($errors->all() as $key => $error)
        $.notify({
                title: '',
                message: '{{$error}}',
            },
            {
                type: 'danger',
                allow_dismiss: false,
                newest_on_top: false,
                mouse_over: false,
                showProgressbar: false,
                spacing: 10,
                timer: 50000,
                placement: {
                    from: 'top',
                    align: 'right'
                },
                offset: {
                    x: 30,
                    y: 30
                },
                delay: 1000,
                z_index: 10000,
                allow_dismiss: true,
                animate: {
                    enter: 'animated bounce',
                    exit: 'animated bounce'
                }
            });
        @endforeach
    </script>
@endif
@if(Session::has('message'))
    @foreach(Session::get('message') as $key => $text)
        <script type="module">
            $.notify({
                    title: '',
                    message: '{{$text}}',
                },
                {
                    @switch($key)
                            @case('success')
                    type: 'success',
                    @break
                            @case('error')
                    type: 'danger',
                    @break
                            @case('alert')
                    type: 'warning',
                    @break
                            @case('info')
                    type: 'info',
                    @break
                            @endswitch
                    allow_dismiss: false,
                    newest_on_top: false,
                    mouse_over: false,
                    showProgressbar: false,
                    spacing: 10,
                    timer: 2000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 1000,
                    z_index: 10000,
                    animate: {
                        enter: 'animated bounce',
                        exit: 'animated bounce'
                    }
                });
        </script>
    @endforeach
@endif

<script type="module" src="{{asset('plugins/src/font-icons/feather/feather.min.js')}}"></script>
<script type="module">
    feather.replace();
</script>
@livewire('bootstrap-modal')
</body>
</html>