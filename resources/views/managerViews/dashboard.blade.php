@extends('managerViews/layout')
@section('content')
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-inbox"></i> Global View</li>
        </ul>
        <!-- Date and time range -->
        <div class="form-group" style="margin:5px;">
            <div class="input-group">
                <button class="btn btn-default " id="daterange-btn">
                    <i class="fa fa-calendar"></i> Choose a Date
                    <i class="fa fa-caret-down"></i>
                </button>
                <h3 id="rangelabel" style="margin-left: 50px;"> All </h3>
            </div>

        </div><!-- /.form group -->

        <div class="tab-content no-padding">
            <div  class="row" style="padding-top: 20px;">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div  id="div1"></div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 ">
                    <div  id="div2"></div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div  id="div3"></div>
                </div>
            </div>
            <script src="{{ asset('js/d3.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('js/radialProgress.js') }}" type="text/javascript"></script>
            <script language="JavaScript">
                start();

                function start() {

                    var rp1 = radialProgress(document.getElementById('div1'))
                            .label("Total CI Usage")
                            .diameter(180)
                            .value( {{ $ci }} )
                            .render();
                    var rp2 = radialProgress(document.getElementById('div2'))
                            .label("Total KB Usage")
                            .diameter(180)
                            .value({{ $kb }})
                            .render();
                    var rp2 = radialProgress(document.getElementById('div3'))
                            .label("Total FCR")
                            .diameter(180)
                            .value({{ $fcr }})
                            .render();
                }
            </script>
        </div>
    </div><!-- /.nav-tabs-custom -->
@endsection