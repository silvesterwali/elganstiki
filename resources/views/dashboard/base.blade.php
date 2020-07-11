<!DOCTYPE html>
<!-- * CoreUI - Free Bootstrap Admin Template * @version v3.0.0-alpha.1 * @link
https://coreui.io * Copyright (c) 2019 creativeLabs Łukasz Holeczek * Licensed
under MIT (https://coreui.io/license) -->

<html lang="en">
    <head>
        <base href="./">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta
            name="description"
            content="CoreUI - Open Source Bootstrap Admin Template">
        <meta name="author" content="silvester">
        <meta
            name="keyword"
            content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
        <title>Seminar</title>
        <link
            rel="icon"
            sizes="57x57"
            href="assets/img/logo-styoseph.jpeg">

        <link rel="manifest" href="assets/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta
            name="msapplication-TileImage"
            content="assets/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- Icons-->
        <link href="{{ asset('css/free.min.css') }}" rel="stylesheet">
        <!-- icons -->
        <link href="{{ asset('css/flag-icon.min.css') }}" rel="stylesheet">
        <!-- icons -->
        <!-- Main styles for this application-->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/pace.min.css') }}" rel="stylesheet">
        <!-- <link href="{{ asset('DataTables') }}/datatables.min.css" rel="stylesheet"/> -->
        <link
            href="{{ asset('DataTables') }}/DataTables-1.10.20/css/dataTables.bootstrap4.min.css"
            rel="stylesheet"/>

        <link href="{{ asset('css') }}/fontawesome/css/all.min.css" rel="stylesheet"/>

        @yield('css')

        <!-- Global site tag (gtag.js) - Google Analytics-->
        <script
            async=""
            src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            // Shared ID
            gtag('config', 'UA-118965717-3');
            // Bootstrap ID
            gtag('config', 'UA-118965717-5');
        </script>


        <link href="{{ asset('css/coreui-chartjs.css') }}" rel="stylesheet">
    </head>

    <body class="c-app">
        <div
            class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show"
            id="sidebar">

            @include('dashboard.shared.nav-builder')
             @include('dashboard.shared.header')

            <div class="c-body">

                <main class="c-main">

                    @yield('content')

                </main>
            </div>
            @include('dashboard.shared.footer')
        </div>


        <!-- CoreUI and necessary plugins-->
        <script src="{{ asset('js/pace.min.js') }}"></script>
        <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
        <script src="{{ asset('DataTables') }}/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
        <script src="{{ asset('DataTables')}}/datatables.min.js"></script>
        <!-- <script src="{{
        asset('DataTables')}}/DataTables-1.10.20/js/dataTables.bootstrap4.min.js"
        ></script> -->

        @yield('javascript')

    </body>
</html>
