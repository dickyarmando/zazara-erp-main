<!doctype html>
<html lang="en" class="light-style layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ url('assets') }}/" data-template="horizontal-menu-theme-default-light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    @livewireStyles

    <!-- Core head -->
    @include('admin.partials.head', ['vendor' => isset($vendor) ? $vendor : []])

    <!-- head stack -->
    @stack('head')

    <title>{{ config('app.name', 'Hypercode') }} - @yield('title')</title>

    <style type="text/css">
        .sorticon {
            visibility: hidden;
            color: darkgray;
        }

        .sort:hover .sorticon {
            visibility: visible;
        }

        .sort:hover {
            cursor: pointer;
        }

        .sortable-chosen {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">

            @include('admin.partials.menu')

            <!-- Layout container -->
            <div class="layout-page">

                @include('admin.partials.navbar')

                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">

                    @yield('content')

                    @isset($slot)
                        {{ $slot }}
                    @endisset

                </div><!-- /Content -->

                <div class="content-backdrop fade"></div>

            </div><!-- /Layout page -->

        </div><!-- /Layout container -->

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div><!-- /Layout wrapper -->

    <!-- Core script -->
    @include('admin.partials.script', ['vendor' => isset($vendor) ? $vendor : []])

    @livewireScripts

    <!-- Stackscript-->
    @stack('scripts')

</body>

</html>
