<!doctype html>
<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ url('assets') }}/"
  data-template="vertical-menu-template-starter"
>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Core head -->
  @include('admin.partials.head-simple', ['vendor' => isset($vendor)?$vendor:[]])

  <!-- head stack -->
  @stack('head')

  <title>{{ config('app.name', 'Hypercode') }} - @yield('title')</title>
</head>
<body>

<!-- Content -->
@yield('content')
<!-- /Content -->

<!-- Core script -->
@include('admin.partials.script-simple', ['vendor' => isset($vendor)?$vendor:[]])

<!-- Stackscript-->
@stack('scripts')

</body>
</html>
