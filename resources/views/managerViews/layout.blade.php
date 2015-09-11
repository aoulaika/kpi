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
        .hiddenRow {
            padding: 0 !important;
        }
    </style>
    @yield('style')
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="#" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">KPIs</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">KPIs App</span>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('img/user2-160x160.jpg') }}" class="user-image" alt="User Image" />
                                <span class="hidden-xs">Mohamed Yassine El Harruchi</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image" />
                                    <p>
                                        Mohamed Yassine El Harruchi - Project Manager
                                        <small>Super User</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p style="font-size:0.85em">Mohamed Yassine El Harruchi</p>
                        <small>Project Manager</small>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="{{ (Request::is('dashboard') || Request::is('dashboard3'))? 'active' : '' }} treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('dashboard') ? 'active' : '' }}"><a href="{{ route("dashboard") }}"><i class="fa fa-circle-o"></i> Global Dashboard</a></li>
                            <li class="{{ Request::is('dashboard3') ? 'active' : '' }}"><a href=" {{ route("dashboard3") }} "><i class="fa fa-circle-o"></i> Agents Dashboard</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request::is('errors') ? 'active' : '' }} treeview">
                        <a href="#">
                            <i class="fa fa-exclamation-circle"></i>
                            <span>Errors</span>
                            <span class="label label-primary pull-right">4</span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('errors') ? 'active' : ''}}" }}><a href=" {{ route("errors") }} "><i class="fa fa-circle-o"></i> Errors Tracking</a></li>
                        </ul>
                    </li>
                    <li class="{{ (Request::is('users') || Request::is('language') || Request::is('project'))? 'active' : '' }} treeview">
                        <a href="#">
                            <i class="fa fa-cogs"></i>
                            <span>Managing</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('users') ? 'active' : '' }}"><a href=" {{ route("users") }} "><i class="fa fa-circle-o"></i> Users</a></li>
                            <li class="{{ Request::is('project') ? 'active' : '' }}"><a href="{{ route("project") }}"><i class="fa fa-circle-o"></i> Projects</a></li>
                            <li class="{{ Request::is('language') ? 'active' : '' }}"><a href="{{ route("language") }}"><i class="fa fa-circle-o"></i> Languages</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <!-- debut -->
        <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('page_title')
            </h1>
            <ol class="breadcrumb">
                @yield('page_current')
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>KPIs App Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2015 <a href="#">HP CDG IT Services Morocco</a>.</strong> All rights reserved.
    </footer>
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
        <!-- fin -->
        </div>
        <script src="{{ asset('/js/jQuery-2.1.4.min.js') }}"></script>
        <!-- daterangepicker -->
        <script src="{{ asset('/js/moment.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
            $('.daterange-btn').daterangepicker(
                {
                    ranges: {
                        'All':['01-01-1900',moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: '01-01-1900',
                    endDate: moment()
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
            );
        </script>
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