@extends('managerViews/layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <!-- Date and time range -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="input-group" style="margin-top:10px;">
                                <button class="btn btn-default daterange-btn" id="daterange-agent">
                                    <i class="fa fa-calendar"></i> Choose a Date Range
                                    <i class="fa fa-caret-down"></i>
                                </button>
                            </div>
                        </div><!-- /.form group -->
                    </div>
                    <div class="col-lg-6">
                        <span class="pull-right" id="range">From :  <span class="date">03/08/2015</span> to : <span class="date">10/08/2015</span></span>
                    </div>
                </div><!-- /.box-header -->
            </div>
        </div>
    </div>
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
                    </div>
                    <div class="row">
                        <div class="col-lg-5" >
                            <img src="{{ asset('/img/default-user.png') }}" class="agent-image"/>
                            <h3 class="agent-name" id="agent_name"></h3>
                            <h4 class="minititle">Handled <span id="nbr" style="color: #44A1C1;"></span> Tickets</h4>
                            <h5 class="minititle">Missed <span style="color: red;"> 13/152</span> Resolvable Tickets </h5>
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
                        <h3 class="titles">Average Tickets Handle Time</h3>
                        <div class="col-lg-6" >
                            <div id="gauge1" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                            <h5 class="minititle">Average THT</h5>
                        </div>
                        <div class="col-lg-6">
                            <div id="gauge2" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                            <h5 class="minititle">Average THT for Password Reset Closure</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <h3 class="titles">Number Of Tickets Per Time</h3>
                        <div class="col-lg-6">
                            <div class="form-group" id="select2">
                                <label for="product">Filter By Product</label>
                                <select name="product" id="product" class="form-control select2">
                                    <option value="all">All</option>
                                    @foreach ($tickets_all['product'] as $key => $value)
                                        <option value="{{ $key }}">{{ $key }} ({{ count($value) }})</option>
                                    @endforeach
                                </select>
                            </div>
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
                <div class="box-body" style="min-height:600px;padding:0px">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">KB Usage</a></li>
                            <li><a href="#tab_2" data-toggle="tab">CI Usage</a></li>
                            <li><a href="#tab_3" data-toggle="tab">FCR</a></li>
                            <li><a href="#tab_4" data-toggle="tab">FCR Resolvable</a></li>
                            <li><a href="#tab_5" data-toggle="tab">Number of tickets</a></li>
                            <li class="pull-right">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-gear"></i> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Param1</a></li>
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Param2</a></li>
                                </ul>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div id="container1bis" class="containerbis"></div>
                                <hr>
                                <h3 class="titles">All agents comparison</h3>
                                <div class="containerscroll">
                                    <div id="container1" class="containerbar"></div>
                                </div>
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                                <div id="container2bis" class="containerbis"></div>
                                <hr>
                                <h3 class="titles">All agents comparison</h3>
                                <div class="containerscroll">
                                    <div id="container2" class="containerbar"></div>
                                </div>
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div id="container3bis" class="containerbis"></div>
                                <hr>
                                <h3 class="titles">All agents comparison</h3>
                                <div class="containerscroll">
                                    <div id="container3" class="containerbar"></div>
                                </div>
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane active" id="tab_4">
                                <div id="container4bis" class="containerbis"></div>
                                <hr>
                                <h3 class="titles">All agents comparison</h3>
                                <div class="containerscroll">
                                    <div id="container4" class="containerbar"></div>
                                </div>
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane active" id="tab_5">
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Top 10 Agents</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="rank">1</span>
                                </div>
                                <div class="col-lg-1">
                                    <img src="dist/img/default-50x50.gif" class="rank-img" alt="Product Image" />
                                </div>
                                <div class="col-lg-8">
                                    <a href="#" class="product-title">Oussama Laouina </a>
                                    <span class="product-description" style="font-size:0.9em">
                                      <span class="rank-titles" >Number of tickets :</span> 2410<br> <span class="rank-titles" >EKMS Usage :</span> 50%, <span class="rank-titles" >CI Usage :</span> 66%, <span class="rank-titles" >FCR  :</span> 89%, <span class="rank-titles" >FCR Resolvable</span> : 56%
                                    </span>
                                </div>
                                <div class="col-lg-2">
                                    <span class="pull-right" style="color:#CFB53B"><i class="fa fa-3x fa-trophy"></i></span>
                                </div>
                            </div>
                        </li><!-- /.item -->
                        <li class="item">
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="rank">2</span>
                                </div>
                                <div class="col-lg-1">
                                    <img src="dist/img/default-50x50.gif" class="rank-img" alt="Product Image" />
                                </div>
                                <div class="col-lg-8">
                                    <a href="#" class="product-title">Karima Majid </a>
                                    <span class="product-description" style="font-size:0.9em">
                                      <span class="rank-titles" >Number of tickets :</span> 2410<br> <span class="rank-titles" >EKMS Usage :</span> 50%, <span class="rank-titles" >CI Usage :</span> 66%, <span class="rank-titles" >FCR  :</span> 89%, <span class="rank-titles" >FCR Resolvable</span> : 56%
                                    </span>
                                </div>
                                <div class="col-lg-2">
                                    <span class="pull-right" style="color:#c0c0c0"><i class="fa fa-3x fa-trophy"></i></span>
                                </div>
                            </div>
                        </li><!-- /.item -->
                        <li class="item">
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="rank">3</span>
                                </div>
                                <div class="col-lg-1">
                                    <img src="dist/img/default-50x50.gif" class="rank-img" alt="Product Image" />
                                </div>
                                <div class="col-lg-8">
                                    <a href="#" class="product-title">Zakaria Seghrouchni </a>
                                    <span class="product-description" style="font-size:0.9em">
                                      <span class="rank-titles" >Number of tickets :</span> 2410<br> <span class="rank-titles" >EKMS Usage :</span> 50%, <span class="rank-titles" >CI Usage :</span> 66%, <span class="rank-titles" >FCR  :</span> 89%, <span class="rank-titles" >FCR Resolvable</span> : 56%
                                    </span>
                                </div>
                                <div class="col-lg-2">
                                    <span class="pull-right" style="color:#cd7f32"><i class="fa fa-3x fa-trophy"></i></span>
                                </div>
                            </div>
                        </li><!-- /.item -->
                    </ul>
                    <center><span id="more" class="hvr-pulse"> ... </span></center>
                </div>
            </div>
        </div><!-- /.col -->
    </div>
    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
@endsection
@section('script')
    <script src="{{ asset('/js/amcharts.js') }}"></script>
    <script src="{{ asset('/js/serial.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/light.js') }}"></script>
    <!-- High Charts -->
    <script src="{{ asset('/js/highcharts.js') }}"></script>
    <script src="{{ asset('/js/highcharts-more.js') }}"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
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
        var tickets_per_agent = JSON.parse('<?php echo json_encode($tickets_per_agent); ?>');
        var prc_nbr_temp = JSON.parse('<?php echo json_encode($prc_nbr); ?>');
        var agent_name=ci_temp[0].Name;
        var agent_nbr=tickets_per_agent[0].count;
        var ci=ci_temp[0].count;
        var kb=kb_temp[0].count;
        var fcr=fcr_temp[0].count;
        var fcr_reso=fcr_reso_temp[0].count;
        var tht=tht_temp[0].tht;
        var tht_password=tht_temp[0].tht_password;
        var tht_time=tht_temp[0].tht_time;
        var tht_password_time=tht_temp[0].tht_password_time;
        var prc_nbr=prc_nbr_temp[0].count;
        bar('#container1bis',agent_name,[Number(kb)],[Number({{ $kb_max }})],[Number({{ $kb_avg }})],[Number({{ $kb_min }})]);
        bar('#container2bis',agent_name,[Number(ci)],[Number({{ $ci_max }})],[Number({{ $ci_avg }})],[Number({{ $ci_min }})]);
        bar('#container3bis',agent_name,[Number(fcr)],[Number({{ $fcr_max }})],[Number({{ $fcr_avg }})],[Number({{ $fcr_min }})]);
        bar('#container4bis',agent_name,[Number(fcr_reso)],[Number({{ $fcr_reso_max }})],[Number({{ $fcr_reso_avg }})],[Number({{ $fcr_reso_min }})]);
        bar('#container5bis',agent_name,[Number(fcr_reso)],[Number({{ $ticket_max }})],[Number({{ $tickets_users_avg }})],[Number({{ $ticket_min }})]);
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
            agent_nbr=tickets_per_agent[v].count;
            prc_nbr=prc_nbr_temp[v].count;
            doit();
            gauge('#gauge1',0,20,agent_nbr+' Tickets',[Number(tht)],tht_time);
            gauge('#gauge2',0,10,prc_nbr+' Tickets ',[Number(tht_password)],tht_password_time);
            bar('#container1bis',agent_name,[Number(kb)],[Number({{ $kb_max }})],[Number({{ $kb_avg }})],[Number({{ $kb_min }})]);
            bar('#container2bis',agent_name,[Number(ci)],[Number({{ $ci_max }})],[Number({{ $ci_avg }})],[Number({{ $ci_min }})]);
            bar('#container3bis',agent_name,[Number(fcr)],[Number({{ $fcr_max }})],[Number({{ $fcr_avg }})],[Number({{ $fcr_min }})]);
        });
        doit();
        gauge('#gauge1',0,20,agent_nbr+' Tickets',[Number(tht)],tht_time);
        gauge('#gauge2',0,10,prc_nbr+' Tickets',[Number(tht_password)],tht_password_time);
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
            $('#nbr').html(agent_nbr);
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
            exporting: { enabled: false },
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

        /* Bar chart (Max Min Avg) function */
        function bar(id,name,value,max,avg,min) {
            var val=parseInt(value)
            var mx=parseInt(max);
            var av=parseInt(avg);
            var mn=parseInt(min);
            $(id).highcharts({
                chart:{
                    type:'bar'
                },

                exporting: { enabled: false },

                tooltip: {
                    valueSuffix: '%'
                },

                title: {
                    text: ' '
                },

                credits: {
                    enabled: false
                },

                xAxis: {
                    categories: [name,'','Maximum','Average','Minimum']
                },
                series: [{
                    name: 'Percentage',
                    showInLegend: false,
                    data: [
                        (val<70)?{y:val,color:'red'}:val,
                         0,
                        {y:mx,color:'#6DD187'},
                        {y:av,color:'#86F0A2'},
                        {y:mn,color:'#A3FFBC'}
                    ]
                }]
            });
        }
    </script>
    <!-- End Script Change User -->

    <!-- Change date range -->
    <script>
        $('#daterange-agent').on('apply.daterangepicker', function(ev, picker) {
            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
            $.ajax({
                url: 'rangedate',
                type: "post",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'datedeb': picker.startDate.format('YYYY-MM-DD'),
                    'datefin': picker.endDate.format('YYYY-MM-DD'),
                    'agent': agent_name
                },
                success: function(response){
                    console.log(response.tht);
                    /* Re-Setting the arrays of value of all agents */
                    tickets_per_agent=response.tickets_per_agent;
                    ci_temp=response.ci_usage;
                    kb_temp=response.kb_usage;
                    fcr_temp=response.fcr;
                    fcr_reso_temp = response.fcr_reso;
                    tht_temp = response.tht;
                    prc_nbr_temp = response.prc_nbr;

                    /* Getting agent Id*/
                    var v=$('#agent').val();

                    /* getting values of the current agent */
                    agent_nbr = tickets_per_agent[v].count;
                    ci = ci_temp[v].count;
                    kb = kb_temp[v].count;
                    fcr = fcr_temp[v].count;
                    fcr_reso = fcr_reso_temp[v].count;
                    tht = tht_temp[v].tht;
                    tht_password = tht_temp[v].tht_password;
                    tht_time = tht_temp[v].tht_time;
                    tht_password_time = tht_temp[v].tht_password_time;
                    prc_nbr = prc_nbr_temp[v].count;


                    /* Setting html values and graphes */
                    doit();
                    gauge('#gauge1',0,20,agent_nbr+' Tickets',[Number(tht)],tht_time);
                    gauge('#gauge2',0,10,prc_nbr+' Tickets',[Number(tht_password)],tht_password_time);
                }
            });
         });
    </script>
    <!-- End Change date range -->

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
    <!-- Bar Chart -->
    <script type="text/javascript">
        var ci_names=JSON.parse('<?php echo json_encode($ci_names); ?>');
        var ci_data=JSON.parse('<?php echo json_encode($ci); ?>');
        drawBar('#container2',ci_names,'CI Usage',ci_data);

        var kb_names=JSON.parse('<?php echo json_encode($kb_names); ?>');
        var kb_data=JSON.parse('<?php echo json_encode($kb); ?>');
        drawBar('#container1',kb_names,'EKMS Usage',kb_data);

        var fcr_names=JSON.parse('<?php echo json_encode($fcr_names); ?>');
        var fcr_data=JSON.parse('<?php echo json_encode($fcr); ?>');
        drawBar('#container3',fcr_names,'FCR',fcr_data);

        var fcr_reso_names=JSON.parse('<?php echo json_encode($fcr_reso_names); ?>');
        var fcr_reso_data=JSON.parse('<?php echo json_encode($fcr_reso); ?>');
        drawBar('#container4',fcr_reso_names,'FCR Resolvable',fcr_reso_data);

        function drawBar(id,name,graphName,data){
            $(id).highcharts({
                chart:{
                    type:'bar'
                },
                exporting: { enabled: false },
                tooltip: {
                    valueSuffix: '%'
                },
                title: {
                    text: ' '
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: name
                },
                series: [{
                    name: graphName,
                    showInLegend: false,
                    data: data
                }]
            });
        }
    </script>
    <!-- End Bar Chart -->
@endsection