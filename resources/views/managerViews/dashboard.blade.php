@extends('managerViews/layout')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Average Handling Time</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div id="chartdiv1" style="margin:auto;height:300px;"></div>
                    <script src="{{ asset('js/jQuery-2.1.4.min.js') }}"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.13.0/amcharts.js"></script>
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
                    <span class="pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-gear"></i> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" id="RadarChart">Radar Chart</a></li>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" id="PieChart">Pie Chart</a></li>
                        </ul>                        
                    </span>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div id="priorityRadar"></div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.13.0/radar.js" type="text/javascript"></script>
                    <script>
                        var chart;
                        var priority = JSON.parse('<?php echo json_encode($priority); ?>');
                        AmCharts.ready(function () {
                            // RADAR CHART
                            chart = new AmCharts.AmRadarChart();
                            chart.dataProvider = priority;
                            chart.categoryField = "priority";
                            chart.startDuration = 2;
                            // VALUE AXIS
                            var valueAxis = new AmCharts.ValueAxis();
                            valueAxis.axisAlpha = 0.15;
                            valueAxis.minimum = 0;
                            valueAxis.maximum = 2000;
                            valueAxis.dashLength = 3;
                            valueAxis.axisTitleOffset = 20;
                            valueAxis.gridCount = 5;
                            chart.addValueAxis(valueAxis);
                            // GRAPH
                            var graph = new AmCharts.AmGraph();
                            graph.valueField = "count";
                            graph.bullet = "round";
                            graph.balloonText = "[[value]]";
                            chart.addGraph(graph);
                            // WRITE
                            chart.write("priorityRadar");
                        });
                    </script>

                    <div id="priorityPie"></div>
                    <script src="{{ asset('js/pie.js') }}" type="text/javascript"></script>
                    <script>
                        var chartPie;
                        var legend;
                        var chartPieData = JSON.parse('<?php echo json_encode($priority) ?>');
                        AmCharts.ready(function () {
                            // PIE CHART
                            chartPie = new AmCharts.AmPieChart();
                            chartPie.dataProvider = chartPieData;
                            chartPie.titleField = "priority";
                            chartPie.valueField = "count";
                            chartPie.outlineColor = "#FFFFFF";
                            chartPie.outlineAlpha = 0.8;
                            chartPie.outlineThickness = 2;
                            chartPie.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
                            // this makes the chart 3D
                            chartPie.depth3D = 15;
                            chartPie.angle = 30;
                            // WRITE
                            chartPie.write("priorityPie");
                        });
                        $(document).ready(function(){
                            $('#priorityPie').css('height','0px');
                        });
                        $('#RadarChart').click(function() {
                            $('#priorityPie').hide();
                            $('#priorityRadar').show();
                        });
                        $('#PieChart').click(function() {
                            
                            $('#priorityRadar').hide();
                            $('#priorityPie').show();
                            $('#priorityPie').css('height','300px');
                        });
                    </script>
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
                var chart1 = AmCharts.makeChart("chartdiv", {
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
                chart1.pathToImages = '/kpi/public/img/';
                chart1.addListener("rendered", zoomChart);
                zoomChart();

                // this method is called when chart is first inited as we listen for "dataUpdated" event
                function zoomChart() {
                    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                    chart1.zoomToIndexes(chartData.length - 40, chartData.length - 1);
                }
            </script>
        </div>
        <!-- end chart ayoub -->
    </div><!-- /.nav-tabs-custom -->

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Average Resolution Time</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <iframe scrolling="no" src="http://localhost/kpi/Graphs/global/avg_resol_time.php" style="width:100%; height: 420px;border-width:0px;"></iframe>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
@endsection