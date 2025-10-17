<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <!-- Styles -->
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <link rel="stylesheet" href="{{asset('vendors/feather/feather.css')}}">
        <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
        <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
        <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('js/select.dataTables.min.css')}}">
        <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- End plugin css for this page -->
        <!-- inject:css -->

        <link rel="stylesheet" href="{{asset('css/vertical-layout-light/style.css')}}">
        <!-- endinject -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @include('navigation-menu')
            <div class="container-fluid page-body-wrapper">
                
                @include('sidebar-menu')
                <div class="main-panel">
                    <div class="content-wrapper">
                         @yield('content')
                         <div class="spinner-body"><div class="spinner"></div></div> 
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        <script>
            $(document).ready((e)=>{
               /* $("a").click((e)=>{
                    setTimeout(() => {
                        $(".spinner-body").show();
                    }, 1000);
                })*/
            })
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        <!-- plugins:js -->
        <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
        <script src="{{asset('js/app.js?v=123')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('vendors/datatables.net/jquery.dataTables.js')}}"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
        <script src="{{asset('js/dataTables.select.min.js')}}"></script>

        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="{{asset('js/off-canvas.js')}}"></script>
        <script src="{{asset('js/hoverable-collapse.js')}}"></script>
        <script src="{{asset('js/template.js')}}"></script>
        <script src="{{asset('js/settings.js')}}"></script>
        <script src="{{asset('js/todolist.js')}}"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="{{asset('js/dashboard.js')}}"></script>
        <script src="{{asset('js/Chart.roundedBarCharts.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- End custom js for this page-->
        
    </body>
</html>
