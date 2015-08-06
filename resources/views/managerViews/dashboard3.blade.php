@extends('managerViews/layout')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Agents KPIs</h3>
                </div><!-- /.box-header -->
                <div class="box-body" style="min-height:600px">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Choose Agent</label>
                                <select class="form-control select2">
                                    <option selected="selected">Walid Walid</option>
                                    <option>Oussama Laouina</option>
                                    <option>Mehdi Benaqa</option>
                                    <option>Khalil Khalil</option>
                                    <option>Lionel Messi</option>
                                    <option>Dave Darrel</option>
                                    <option>Eric Clapton</option>
                                </select>
                            </div><!-- /.form-group -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date range:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="reservation" />
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5" >
                            <img src="{{ asset('/img/default-user.png') }}" class="agent-image"/>
                            <h3 class="agent-name">Oussama Oussama Oussama</h3>
                        </div>
                        <div class="col-lg-7" >
                            <table class="table percentable">
                                <tr>
                                    <td>FCR</td>
                                    <td>
                                        <div class="shiva"><span class="count">80</span>%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>FCR Resolvable</td>
                                    <td>
                                        <div class="shiva"><span class="count">55</span>%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EKMS Usage</td>
                                    <td>
                                        <div class="shiva" style="color:red"><span class="count">33</span>%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CI Usage</td>
                                    <td>
                                        <div class="shiva"><span class="count">23</span>%</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <h3 class="titles">Average Tickets Handling Time</h3>
                        <div class="col-lg-6" >
                            <div id="gauge1" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                        </div>
                        <div class="col-lg-6">
                            <div id="gauge2" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <h3 class="titles">Number Of Tickets Per Time</h3>
                            <div class="form-group pull-right" id="select2">
                                <label for="product">Filter By Product</label>
                                <select name="product" id="product" class="form-control select2">
                                    <option value="all">All</option>
                                    @foreach ($tickets_all['product'] as $key => $value)
                                        <option value="{{ $key }}">{{ $key }} ({{ count($value) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="ticketsChart2" style="width:100%;height:400px;"></div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Comparing Agents Performance</h3>
                </div><!-- /.box-header -->
                <div class="box-body" style="min-height:600px">
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/amcharts.js') }}"></script>
    <script src="{{ asset('/js/serial.js') }}" type="text/javascript"></script>
    <!-- High Charts -->
    <script src="{{ asset('/js/highcharts.js') }}"></script>
    <script src="{{ asset('/js/highcharts-more.js') }}"></script>
    <!-- End High Charts -->
    <!-- Gauge chart -->

    <script src="{{ asset('/js/solid-gauge.js') }}"></script>
    <!-- Gauge1 Chart -->
    <script type="text/javascript">
        var gaugeOptions = {
            chart: {
                type: 'solidgauge'
            },
            title: null,
            pane: {
                center: ['50%', '85%'],
                size: '100%',
                startAngle: -90,
                endAngle: 90,
                background: {
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                    innerRadius: '60%',
                    outerRadius: '100%',
                    shape: 'arc'
                }
            },
            tooltip: {
                enabled: false
            },
            // the value axis
            yAxis: {
                stops: [
                    [0.1, '#337C99'], // blue
                    [0.7, '#C9BE58'], // yellow
                    [0.9, '#CF6E6E'] // red
                ],
                lineWidth: 0,
                minorTickInterval: null,
                tickPixelInterval: 400,
                tickWidth: 0,
                title: {
                    y: -70
                },
                labels: {
                    y: 16
                }
            },
            plotOptions: {
                solidgauge: {
                    dataLabels: {
                        y: 5,
                        borderWidth: 0,
                        useHTML: true
                    }
                }
            }
        };
        // The speed gauge
        $('#gauge1').highcharts(Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 20,
                title: {
                    text: 'THT'
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Speed',
                data: [5],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
                    '<span style="font-size:14px;color:blue;font-family:Open Sans">5</span></div>'
                }
            }]
        }));
    </script>
    <!-- End Gauge1 Chart -->

    <!-- Gauge2 Chart -->
    <script language="JavaScript">
        var gaugeOptions = {
            chart: {
                type: 'solidgauge'
            },
            title: null,
            pane: {
                center: ['50%', '85%'],
                size: '100%',
                startAngle: -90,
                endAngle: 90,
                background: {
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                    innerRadius: '60%',
                    outerRadius: '100%',
                    shape: 'arc'
                }
            },
            tooltip: {
                enabled: false
            },
            // the value axis
            yAxis: {
                stops: [
                    [0.1, '#337C99'], // blue
                    [0.7, '#C9BE58'], // yellow
                    [0.9, '#CF6E6E'] // red
                ],
                lineWidth: 0,
                minorTickInterval: null,
                tickPixelInterval: 400,
                tickWidth: 0,
                title: {
                    y: -70
                },
                labels: {
                    y: 16
                }
            },
            plotOptions: {
                solidgauge: {
                    dataLabels: {
                        y: 5,
                        borderWidth: 0,
                        useHTML: true
                    }
                }
            }
        };
        // The speed gauge
        $('#gauge2').highcharts(Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 10,
                title: {
                    text: 'THT(Password Reset Closure)'
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Speed',
                data: [1.3],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
                    '<span style="font-size:14px;color:blue;font-family:Open Sans">1.3</span></div>'
                }
            }]
        }));
    </script>
    <!-- End Gauge2 Chart -->
    <!-- End Gauge chart -->
    <!-- Percentage Counter -->
    <script src="{{ asset('/js/radialProgress.js') }}" type="text/javascript"></script>
    <script language="JavaScript">
        $('.count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 3000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    </script>
    <!-- End Percentage Counter -->
    <!-- Select2 -->
    <script src="{{ asset('/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <!-- Tickets Per Hour Chart -->
    <script language="JavaScript">
        var data_temp = JSON.parse('<?php echo json_encode($tickets_all); ?>');
        var data=data_temp.all;
        $(".select2").change(function() {
            var v=$(this).val();
            if(v=='all'){
                data=data_temp.all;
            }else{
                data=data_temp.product[v];
            }
            draw();
        });
        draw();
        function draw() {
            var ticketsData1 = [];
            for (var i = 0; i < data.length; i++) {
                ticketsData1.push({
                    date: new Date(data[i].CreatedYear, data[i].CreatedMonth - 1, data[i].CreatedDay, data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
                    visits: data[i].count
                });
            };

            var chart2 = AmCharts.makeChart("ticketsChart2", {
                "type": "serial",
                "theme": "light",
                "marginRight": 80,
                "autoMarginOffset": 20,
                "marginTop": 7,
                "dataProvider": ticketsData1,
                "valueAxes": [{
                    "axisAlpha": 0.2,
                    "dashLength": 1,
                    "position": "left"
                }],
                "mouseWheelZoomEnabled": true,
                "graphs": [{
                    "id": "ticketsChart2",
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
                    "graph": "ticketsChart2",
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
            chart2.pathToImages = '/kpi/public/img/';
            chart2.addListener("rendered", zoomChart);
            zoomChart();

            // this method is called when chart is first inited as we listen for "dataUpdated" event
            function zoomChart() {
                // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                chart2.zoomToIndexes(ticketsData1.length - 40, ticketsData1.length - 1);
            }
        }
    </script>
    <!-- End Tickets Per Hour Chart -->
    <script type="text/javascript">
        //Applying select2
        $(".select2").select2();
        //Date range picker
        $('#reservation').daterangepicker();
    </script>
@endsection