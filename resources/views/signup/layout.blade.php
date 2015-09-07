<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>KPIs App - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('/img/icon.png') }}">
    <!-- Select2 -->
    <link href="{{ asset('/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- google font -->
    <link href='http://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- Hover.css -->
    <link href="{{ asset('/css/hover.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="{{ asset('/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link href="{{ asset('/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/plugins/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="{{ asset('/css/style2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/extensions/Buttons/css/buttons.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .minititle{
            text-align: center;
            font-family: 'Share Tech', sans-serif;
            opacity: 0.8;
            color:black;
        }
        .rank {
            font-size: 2em;
            font-family: 'Vollkorn', serif;
            opacity: 0.8;
            margin-left:10px;
            margin-right:15px;
        }
        .rank-img {
            margin-top: 4px;
        }
        .rank-titles {
            color :#141a1d;
            opacity: 0.8;
        }
        #more {
            width:50px;
            margin: auto;
            font-size:4em;
            opacity:0.5;
            cursor:pointer;
        }
        .center {
            text-align: center;
        }
        .label{
            color:black;
        }

    </style>
    @yield('style')
</head>
<body class="skin-blue">

    <section class="content-header">
        <h1>
            SignUp
        </h1>
        <!-- <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @yield('content')
    </section><!-- /.content -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>KPIs App Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="#">HP CDG IT Services Morocco</a>.</strong> All rights reserved.
    </footer>
    <script src="{{ asset('/js/jQuery-2.1.4.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('/js/moment.min.js') }}" type="text/javascript"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script type="text/javascript">
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="{{ asset('/plugins/sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="{{ asset('/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('/plugins/knob/jquery.knob.js') }}" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="{{ asset('/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{{ asset('/plugins/fastclick/fastclick.min.js') }}" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/dist/js/app.min.js') }}" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('/dist/js/demo.js') }}" type="text/javascript"></script>
    @yield('script')
</body>
</html>