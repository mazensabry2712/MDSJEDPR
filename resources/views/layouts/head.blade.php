<!-- Title -->
<title>
    @yield('title')
</title>




@yield('css')


<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



@if (App::getlocale() == 'en' || App::getlocale() == 'fr')
    <!-- Favicon -->
    <link rel="icon" href="{{ URL::asset('assets/img/brand/favicon.png') }}?v={{ time() }}" type="image/x-icon" />
    <!-- Icons css -->
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">
    <!--  Custom Scroll bar-->
    <link href="{{ URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet" />
    <!--  Right-sidemenu css -->
    <link href="{{ URL::asset('assets/plugins/sidebar/sidebar.css') }}" rel="stylesheet">
    <!-- Sidemenu css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/sidemenu.css') }}">
    <!-- Maps css -->
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <!-- style css -->
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/style-dark.css') }}" rel="stylesheet">
    <!---Skinmodes css-->
    <link href="{{ URL::asset('assets/css/skin-modes.css') }}" rel="stylesheet" />
@else
    <!-- Favicon -->
    <link rel="icon" href="{{ URL::asset('assets/img/brand/favicon.png') }}?v={{ time() }}" type="image/x-icon" />
    <!-- Icons css -->
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">
    <!--  Custom Scroll bar-->
    <link href="{{ URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet" />
    <!--  Sidebar css -->
    <link href="{{ URL::asset('assets/plugins/sidebar/sidebar.css') }}" rel="stylesheet">
    <!-- Sidemenu css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css-rtl/sidemenu.css') }}">
    <!--- Style css -->
    <link href="{{ URL::asset('assets/css-rtl/style.css') }}" rel="stylesheet">
    <!--- Dark-mode css -->
    <link href="{{ URL::asset('assets/css-rtl/style-dark.css') }}" rel="stylesheet">
    <!---Skinmodes css-->
    <link href="{{ URL::asset('assets/css-rtl/skin-modes.css') }}" rel="stylesheet">
@endif

<!-- Custom Logo Styling -->
<style>
    /* Logo above red line - outside menu */
    .sidebar-logo-container {
        padding: 5px 10px 0 !important;
        text-align: center;
        background: transparent;
        margin-bottom: 0 !important;
    }

    .sidebar-logo-link {
        display: inline-block;
    }

    .sidebar-main-logo {
        max-width: 140px;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* Remove top margin from main-sidemenu to stick to logo */
    .app-sidebar .main-sidemenu {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* Remove top spacing from first category */
    .app-sidebar .side-menu .side-item-category:first-child {
        margin-top: 0 !important;
        padding-top: 8px !important;
    }

    /* Dark mode */
    .dark-mode .sidebar-logo-container {
        background: transparent;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .sidebar-logo-container {
            padding: 5px 8px 0 !important;
        }

        .sidebar-main-logo {
            max-width: 110px;
        }
    }
</style>
