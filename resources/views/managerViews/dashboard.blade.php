@extends('managerViews/layout')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Average Handling Time</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div id="chartdiv1" style="margin:auto;width:300px;height:300px;"></div>
                    <script src="http://www.amcharts.com/lib/3/amcharts.js"></script>
                    <script src="http://www.amcharts.com/lib/3/gauge.js"></script>
                    <script src="http://www.amcharts.com/lib/3/themes/light.js"></script>
                    <script type="text/javascript">
                        var gaugeChart = AmCharts.makeChart( "chartdiv1", {
                            "type": "gauge",
                            "theme": "light",
                            "axes": [ {
                                "axisThickness": 1,
                                "axisAlpha": 0.2,
                                "tickAlpha": 0.2,
                                "valueInterval": 2,
                                "bands": [ {
                                    "color": "#84b761",
                                    "endValue": 8,
                                    "startValue": 0
                                }, {
                                    "color": "#fdd400",
                                    "endValue": 10,
                                    "startValue": 8
                                }, {
                                    "color": "#cc4748",
                                    "endValue": 22,
                                    "innerRadius": "95%",
                                    "startValue": 10
                                } ],
                                "bottomText": "05 min 33 sec",
                                "bottomTextYOffset": -20,
                                "endValue": 22
                            } ],
                            "arrows": [ {} ],
                            "export": {
                                "enabled": true
                            }
                        } );

                        setInterval( randomValue, 2000 );

                        // set random value
                        function randomValue() {
                            var value = "5.372518333333305";
                            if ( gaugeChart ) {
                                if ( gaugeChart.arrows ) {
                                    if ( gaugeChart.arrows[ 0 ] ) {
                                        if ( gaugeChart.arrows[ 0 ].setValue ) {
                                            gaugeChart.arrows[ 0 ].setValue( value );
                                        }
                                    }
                                }
                            }
                        }
                    </script>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->

        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Tickets Priority</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <span class="pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-gear"></i> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Bar Chart</a></li>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Bubble Chart</a></li>
                        </ul>
                        <!-- hna !! -->
                    </span>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div> <!-- /.row -->
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
            </div>

        </div><!-- /.form group -->
        <!-- chart zakaria -->
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
        <!-- end chart zakaria -->
    </div><!-- /.nav-tabs-custom -->

    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-inbox"></i> Tickets Per Hours</li>
        </ul>
        <!-- chart ayoub -->
        <div class="tab-content no-padding">
            <div  class="row" style="padding-top: 20px;">
                <div class="col-xs-12">
                    <div  id="chartdiv"></div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.13.0/amcharts.js" type="text/javascript"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.13.0/serial.js" type="text/javascript"></script>
            <script src="{{ asset('/js/light.js') }}" type="text/javascript"></script>
            <script language="JavaScript">
                var data = JSON.parse( '<?php echo json_encode($tickets); ?>' );
                var chartData=[];
                for (var i = 0; i < data.length; i++) {
                    chartData.push({
                        date: new Date(data[i].CreatedYear, data[i].CreatedMonth-1, data[i].CreatedDay, data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
                        visits: data[i].count
                    });
                };
                var chart = AmCharts.makeChart("chartdiv", {
                    "type": "serial",
                    "theme": "light",
                    "marginRight": 80,
                    "autoMarginOffset": 20,
                    "marginTop": 7,
                    "dataProvider": chartData,
                    "valueAxes": [{
                        "axisAlpha": 0.2,
                        "dashLength": 1,
                        "position": "left"
                    }],
                    "mouseWheelZoomEnabled": true,
                    "graphs": [{
                        "id": "g1",
                        "balloonText": "[[category]]<br/><b><span style='font-size:14px;'>value: [[value]]</span></b>",
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletColor": "#FFFFFF",
                        "hideBulletsCount": 50,
                        "title": "red line",
                        "valueField": "visits",
                        "useLineColorForBulletBorder": true
                    }],
                    "chartScrollbar": {
                        "autoGridCount": true,
                        "graph": "g1",
                        "scrollbarHeight": 40
                    },
                    "chartCursor": {
                        "categoryBalloonDateFormat": "JJ h, DD MMMM",
                        "cursorPosition": "mouse"
                    },
                    "categoryField": "date",
                    "categoryAxis": {
                        "parseDates": true,
                        "minPeriod": "mm",
                        "axisColor": "#DADADA",
                        "dashLength": 1,
                        "minorGridEnabled": true
                    },
                    "export": {
                        "enabled": true
                    }
                });
chart.pathToImages = '/kpi/public/img/';
chart.addListener("rendered", zoomChart);
zoomChart();

        // this method is called when chart is first inited as we listen for "dataUpdated" event
        function zoomChart() {
            // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
            chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
        }
    </script>
</div>
<!-- end chart ayoub -->
</div><!-- /.nav-tabs-custom -->
@endsection