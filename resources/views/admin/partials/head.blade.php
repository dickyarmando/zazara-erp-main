<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ url('assets/img/logo-zazara.png') }}" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="{{ url('assets/vendor/fonts/boxicons.css') }}" />
<link rel="stylesheet" href="{{ url('assets/vendor/fonts/fontawesome.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ url('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ url('assets/vendor/css/rtl/theme-default.css') }}"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ url('assets/css/demo.css') }}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ url('assets/vendor/libs/animate-css/animate.css') }}" />
<link rel="stylesheet" href="{{ url('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ url('assets/vendor/libs/select2/select2.css') }}" />

@if (in_array('datatables', $vendor))
    <link rel="stylesheet" href="{{ url('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ url('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ url('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
@endif

<!-- Page CSS -->

<!-- Helpers -->
<script src="{{ url('assets/vendor/js/helpers.js') }}"></script>

<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ url('assets/js/config.js') }}"></script>
