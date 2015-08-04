@extends('managerViews/layout')
@section('content')
    <!-- chart zakaria -->
    <h2 class="page-header"> KB /CI Usage and FCR per agent</h2>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_4" data-toggle="tab">KB Usage & CI Usage & FCR Per Agent</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_4">
                    <iframe scrolling="no" src="http://localhost/kpi/Graphs/agents/ci_kb_fcr.php" style="width:100%; height: 420px;border-width:0px"></iframe>
                </div><!-- /.tab-pane -->
            </div>
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">KB Usage</a></li>
                <li><a href="#tab_2" data-toggle="tab">CI Usage</a></li>
                <li><a href="#tab_3" data-toggle="tab">FCR</a></li>
                <li class="pull-right">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-gear"></i> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Bar Chart</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Bubble Chart</a></li>
                    </ul>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <iframe src="http://localhost/kpi/Graphs/agents/kb.php" style="width:100%; height: 400px;border-width:0px"></iframe>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <iframe src="http://localhost/kpi/Graphs/agents/ci.php" style="width:100%; height: 400px;border-width:0px"></iframe>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                    <iframe src="http://localhost/kpi/Graphs/agents/fcr.php" style="width:100%; height: 400px;border-width:0px"></iframe>
                </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    <!-- end chart zakaria -->
@endsection
@section('script')

@endsection