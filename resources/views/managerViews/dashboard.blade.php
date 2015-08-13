@extends('managerViews/layout')
@section('content')
<!-- Date Picker -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <!-- Date and time range -->
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group" style="margin-top:10px;">
                            <button class="btn btn-default daterange-btn" id="">
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
<!-- End Date Picker -->

<!-- Global View -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Global View</h3>
            </div>
            <div class="box-body">
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <div  id="div1"></div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 ">
                    <div  id="div2"></div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <div  id="div3"></div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <div  id="div4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Global View -->

<!-- Average Tickets Handling Time & Tickets Priority -->
<div class="row">
    <div class="col-lg-7 col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Average Tickets Handling Time</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div id="gauge1" style="height:300px;width:100%"></div>
                    </div>
                    <div class="col-lg-6">
                        <div id="gauge2" style="height:300px;width:100%"></div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->

    <div class="col-lg-5 col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Tickets Priority</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="priorityPie"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div> <!-- /.row -->
<!-- End Average Tickets Handling Time & Tickets Priority -->

<!-- Tickets Per Hours -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Tickets Per Hours</h3>
                <div class="pull-right" id="select2">
                    <label for="product">Filter By Product</label>
                    <select name="product" id="product" class="form-control select2">
                        <option value="all">All</option>
                        @foreach ($tickets_all['product'] as $key => $value)
                        <option value="{{ $key }}">{{ $key }} ({{ count($value) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="box-body">
                <div  id="ticketsChart"></div>
            </div>
        </div>
    </div>
</div>
<!-- End Tickets Per Hours -->

<!-- Average Resolution Time Per Category -->
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Average Resolution Time Per Category</h3>
            </div><!-- /.box-header -->
            <div class="box-body" style="height:440px">
                <iframe src="http://localhost/kpi/Graphs/global/avg_resol_time.php" style="width:100%; height: 420px;border-width:0px;"></iframe>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Tickets Per Country</h3>
            </div><!-- /.box-header -->
            <div class="box-body" style="height:440px">
                <div id="regions_div" style="width: 100%; height: 100%;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div>
<!-- End Average Resolution Time Per Category -->
@endsection
@section('script')
<!-- Select2 -->
<script src="{{ asset('/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/d3.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/amcharts.js') }}"></script>
<script src="{{ asset('/js/gauge.js') }}"></script>
<script src="{{ asset('/js/light.js') }}"></script>
<script src="{{ asset('/js/highcharts.js') }}"></script>
<script src="{{ asset('/js/highcharts-more.js') }}"></script>
<script src="{{ asset('/js/solid-gauge.js') }}"></script>
<script src="{{ asset('/js/pie.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/radialProgress.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/serial.js') }}" type="text/javascript"></script>

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
        data: [{{ $avg_tht['all'][0] }}],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
            ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
            '<span style="font-size:14px;color:blue;font-family:Open Sans">{{ $avg_tht['all'][1] }}</span></div>'
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
        data: [{{ $avg_tht['password'][0] }}],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
            ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
            '<span style="font-size:14px;color:blue;font-family:Open Sans">{{ $avg_tht['password'][1] }}</span></div>'
        }
    }]
}));
</script>
<!-- End Gauge2 Chart -->

<!-- Priority Pie Chart -->
<script language="JavaScript">
    // Make monochrome colors and set them as default for all pies
    Highcharts.getOptions().plotOptions.pie.colors = (function() {
        var colors = [],
        base = Highcharts.getOptions().colors[0],
        i;

        for (i = 0; i < 10; i += 1) {
            // Start out with a darkened base color (negative brighten), and end
            // up with a much brighter color
            colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
        }
        return colors;
    }());
    // Build the chart
    $('#priorityPie').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Tickets Priority'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: "Pourcentage",
            data: JSON.parse('<?php echo json_encode($priority); ?>')
        }]
    });
    $( "text:contains('Highcharts')" ).hide();
</script>
<!-- End Priority Pie Chart -->

<!-- Global View Chart -->
<script language="JavaScript">
    start();

    function start() {
        var rp1 = radialProgress(document.getElementById('div1'))
        .label("Total CI Usage")
        .diameter(180)
        .value({{ $ci }})
        .render();
        var rp2 = radialProgress(document.getElementById('div2'))
        .label("Total KB Usage")
        .diameter(180)
        .value({{ $kb }})
        .render();
        var rp3 = radialProgress(document.getElementById('div3'))
        .label("Total FCR")
        .diameter(180)
        .value({{ $fcr['all'] }})
        .render();
        var rp4 = radialProgress(document.getElementById('div4'))
        .label("Total FCR Resolvable")
        .diameter(180)
        .value({{ $fcr['resolvable'] }})
        .render();
    }
</script>
<!-- End Global View Chart -->

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
        var ticketsData = [];
        for (var i = 0; i < data.length; i++) {
            ticketsData.push({
                date: new Date(data[i].CreatedYear, data[i].CreatedMonth - 1, data[i].CreatedDay, data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
                visits: data[i].count
            });
        };
        var chart1 = AmCharts.makeChart("ticketsChart", {
            "type": "serial",
            "theme": "light",
            "marginRight": 80,
            "autoMarginOffset": 20,
            "marginTop": 7,
            "dataProvider": ticketsData,
            "valueAxes": [{
                "axisAlpha": 0.2,
                "dashLength": 1,
                "position": "left"
            }],
            "mouseWheelZoomEnabled": true,
            "graphs": [{
                "id": "ticketsChart",
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
                "graph": "ticketsChart",
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
            chart1.zoomToIndexes(ticketsData.length - 40, ticketsData.length - 1);
        }
    }
</script>
<!-- End Tickets Per Hour Chart -->

<!-- Map Chart -->
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['geochart']}]}"></script>
<script>
    google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {

        var data = google.visualization.arrayToDataTable([
          ['Country', 'Popularity'],
          ['Germany', 200],
          ['United States', 300],
          ['Brazil', 400],
          ['Canada', 500],
          ['France', 600],
          ['ES', 700]
        ]);

        var options = {colorAxis: {colors: ['#81D4D5','#2E6E8A']}};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }
</script>
<!-- End Map Chart -->

<!-- Others Script -->
<script>
    $('.select2').select2();
</script>
<!-- End Others Script -->
@endsection