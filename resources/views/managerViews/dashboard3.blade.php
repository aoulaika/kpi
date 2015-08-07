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
                                <label for="agent">Choose Agent</label>
                                <select class="form-control select2" id="agent" name="agent">
                                    @foreach ($fcr_reso_users as $key => $user)
                                    <option value="{{ $key }}">{{ $user->Name }}</option>
                                    @endforeach
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
                            <h3 class="agent-name" id="agent_name">Oussama Oussama Oussama</h3>
                        </div>
                        <div class="col-lg-7" >
                            <table class="table percentable">
                                <tr>
                                    <td>FCR</td>
                                    <td>
                                        <div class="shiva" id="fcr"><span class="count">80</span>%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>FCR Resolvable</td>
                                    <td>
                                        <div class="shiva" id="fcr_reso"><span class="count">55</span>%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EKMS Usage</td>
                                    <td>
                                        <div class="shiva" id="kb"><span class="count">33</span>%</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CI Usage</td>
                                    <td>
                                        <div class="shiva" id="ci"><span class="count">23</span>%</div>
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
    <!-- Percentage Counter -->
    <script src="{{ asset('/js/radialProgress.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/solid-gauge.js') }}"></script>
    <!-- End Percentage Counter -->
    <!-- Script Change User -->
    <script>
        var ci_temp = JSON.parse('<?php echo json_encode($ci_users); ?>');
        var kb_temp = JSON.parse('<?php echo json_encode($kb_users); ?>');
        var fcr_temp = JSON.parse('<?php echo json_encode($fcr_users); ?>');
        var fcr_reso_temp = JSON.parse('<?php echo json_encode($fcr_reso_users); ?>');
        var tht_temp = JSON.parse('<?php echo json_encode($tht); ?>');
        var agent_name=ci_temp[0].Name;
        var ci=ci_temp[0].count;
        var kb=kb_temp[0].count;
        var fcr=fcr_temp[0].count;
        var fcr_reso=fcr_reso_temp[0].count;
        var tht=tht_temp[0].tht;
        var tht_password=tht_temp[0].tht_password;
        var tht_time=tht_temp[0].tht_time;
        var tht_password_time=tht_temp[0].tht_password_time;
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
                        [0.1, '#3EBBAD'], // green
                        [0.4, '#3EBBAD'], // yellow
                        [0.9, '#DF5353'] // red
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
            var gData1={
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
                data: tht,
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
                    '<span style="font-size:14px;color:blue;font-family:Open Sans">'+tht_time+'</span></div>'
                }
            }]
        };
        var gData2={
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
                data: tht_password,
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
                    '<span style="font-size:14px;color:blue;font-family:Open Sans">'+tht_password_time+'</span></div>'
                }
            }]
        };
        $("#agent").change(function() {
            var v=$(this).val();
            agent_name=ci_temp[v].Name;
            ci=ci_temp[v].count;
            kb=kb_temp[v].count;
            fcr=fcr_temp[v].count;
            fcr_reso=fcr_reso_temp[v].count;
            tht=tht_temp[v].tht;
            tht_password=tht_temp[v].tht_password;
            tht_time=tht_temp[v].tht_time;
            tht_password_time=tht_temp[v].tht_password_time;
            doit();
            gauge('#gauge1',0,20,'THT',[Number(tht)],tht_time);
            gauge('#gauge2',0,10,'THT(Password Reset Closure)',[Number(tht_password)],tht_password_time);
        });
        doit();
        gauge('#gauge1',0,20,'THT',[Number(tht)],tht_time);
        gauge('#gauge2',0,10,'THT(Password Reset Closure)',[Number(tht_password)],tht_password_time);
        function doit(){
            if (ci<50) {
                $("#ci").css('color','red');
            }else{
                $("#ci").css('color','');
            }
            if (kb<50) {
                $("#kb").css('color','red');
            }else{
                $("#kb").css('color','');
            }
            if (fcr<50) {
                $("#fcr").css('color','red');
            }else{
                $("#fcr").css('color','');
            }
            if (fcr_reso<50) {
                $("#fcr_reso").css('color','red');
            }else{
                $("#fcr_reso").css('color','');
            }
            $('#agent_name').html(agent_name);
            $('#ci').html('<span class="count">'+ci+'</span>%');
            $('#kb').html('<span class="count">'+kb+'</span>%');
            $('#fcr').html('<span class="count">'+fcr+'</span>%');
            $('#fcr_reso').html('<span class="count">'+fcr_reso+'</span>%');
            $('.count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
        }

        function gauge(id,mi,mx,title,tht,tht_time){
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
            var gData={
            yAxis: {
                min: mi,
                max: mx,
                title: {
                    text: title
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Speed',
                data: tht,
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
                    '<span style="font-size:14px;color:blue;font-family:Open Sans">'+tht_time+'</span></div>'
                }
            }]
        };
        // The speed gauge
        $(id).highcharts(Highcharts.merge(gaugeOptions, gData));
        }
    </script>
    <!-- End Script Change User -->

    <!-- Select2 -->
    <script src="{{ asset('/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <!-- Tickets Per Hour Chart -->
    <script language="JavaScript">
        var data_temp = JSON.parse('<?php echo json_encode($tickets_all); ?>');
        var data=data_temp.all;
        $("#product").change(function() {
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