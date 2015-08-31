@extends('managerViews/layout')
@section('title', ' Global Dashboard')

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
							<button class="btn btn-default daterange-btn" id="daterange-dash">
								<i class="fa fa-calendar"></i> Choose a Date Range
								<i class="fa fa-caret-down"></i>
							</button>
						</div>
					</div><!-- /.form group -->
				</div>
				<div class="col-lg-6">
					<span class="pull-right" id="range"><span class="date total_ticket">{{ $total_ticket }}</span> Tickets Handled between :  <span class="date" id="debut">1900-01-01</span> and : <span class="date" id="fin">2015-08-19</span></span>
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
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin:auto;">
					<div  id="div1"></div>
					<table class="table" style="width:80%;margin:auto;">
						<tr>
							<th>Volume</th>
							<th class="pull-right">Missed</th>
						</tr>
						<tr>
							<td class="total_ticket">{{ $total_ticket }}</td>
							<td class="pull-right" id="ci_missed">{{ $ci_missed }}</td>
						</tr>
					</table>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 ">
					<div  id="div2"></div>
					<table class="table" style="width:80%;margin:auto;">
						<tr>
							<th>Volume</th>
							<th class="pull-right">Missed</th>
						</tr>
						<tr>
							<td class="total_ticket">{{ $total_ticket }}</td>
							<td class="pull-right" id="kb_missed">{{ $kb_missed }}</td>
						</tr>
					</table>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
					<div  id="div3"></div>
					<table class="table" style="width:80%;margin:auto;">
						<tr>
							<th>Volume</th>
							<th class="pull-right">Missed</th>
						</tr>
						<tr>
							<td class="total_ticket">{{ $total_ticket }}</td>
							<td class="pull-right" id="fcr_missed">{{ $fcr_missed }}</td>
						</tr>
					</table>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
					<div id="div4"></div>
					<table class="table" style="width:80%;margin:auto;">
						<tr>
							<th>Volume</th>
							<th class="pull-right">Missed</th>
						</tr>
						<tr>
							<td id="fcr_reso_total">{{ $fcr_reso_total }}</td>
							<td class="pull-right" id="fcr_reso_missed">{{ $fcr_reso_missed }}</td>
						</tr>
					</table>
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
			<div class="box-body" style="height:394px">
				<div class="row">
					<div class="col-lg-6">
						<div id="gauge1" style="height:300px;width:100%"></div>
						<h5 class="minititle">Average THT</h5>
					</div>
					<div class="col-lg-6">
						<div id="gauge2" style="height:300px;width:100%"></div>
						<h5 class="minititle">Average THT for Password Reset Closure</h5>
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
				<table class="table text-center" style="width:100%;margin:auto;">
					<tr>
						<th>Critical</th>
						<th>High</th>
						<th>Medium</th>
						<th>Low</th>
						<th>Low/Planning</th>
					</tr>
					<tr id="pr">
						<td>{{ $critical }}</td>
						<td>{{ $high }}</td>
						<td>{{ $medium }}</td>
						<td>{{ $low }}</td>
						<td>{{ $planning }}</td>
					</tr>
				</table>
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
				<h3 class="box-title">Tickets Per Time</h3>
			</div>
			<div class="box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab">Tickets Per Hours</a></li>
						<li><a href="#tab_2" data-toggle="tab">Compare Two Weeks</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div>
								<div id="select2" class="col-sm-12">
									<label for="product">Filter By Product</label>
									<select name="product" id="product" class="form-control select2">
										<option value="all">All</option>
										@foreach ($tickets_all['product'] as $key => $value)
										<option value="{{ $key }}">{{ $key }}</option>
										@endforeach
									</select>
								</div>
								<div  id="ticketsChart"></div>
							</div>
						</div><!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<div>
								<form action="" class="form-inline">
									<div class="form-group">
										<select name="product_week" id="product-week" class="form-control select2">
											<option value="all">All</option>
											@foreach ($tickets_all['product'] as $key => $value)
											<option value="{{ $key }}">{{ $key }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group">
										<input type="text" class="form-control week-picker" name="start_date">
									</div>
									<div class="form-group">
										<input type="text" class="form-control week-picker" name="end_date">
									</div>
									<input type="hidden" value="{{ csrf_token() }}" name="_token">
									<a class="btn btn-default" id="compare">valider</a>
								</form>
								<div id="weeks"></div>
							</div>
						</div><!-- /.tab-pane -->
					</div><!-- /.tab-content -->
				</div><!-- nav-tabs-custom -->
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
				<h3 class="box-title">Tickets per Categories</h3>
			</div><!-- /.box-header -->
			<div class="box-body" style="height:440px">
				<div id="categoryPie"></div>
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

<!-- Ajax -->
<script>
	var data_temp = JSON.parse('<?php echo json_encode($tickets_all); ?>');
	$(document).ready(function(){
		$('#daterange-dash').on('apply.daterangepicker', function(ev, picker) {
			$('#debut').html(picker.startDate.format('YYYY-MM-DD'));
			$('#fin').html(picker.endDate.format('YYYY-MM-DD'));
			$.ajax({
				url: 'reload',
				type: "post",
				data: {
					'_token': $('input[name=_token]').val(),
					'datedeb': picker.startDate.format('YYYY-MM-DD'),
					'datefin': picker.endDate.format('YYYY-MM-DD')
				},
				success: function(response){
					console.log(response);
					globalView('div1', "Total CI Usage", 100*(1-response.ci_missed/response.total_ticket));
					globalView('div2', "Total KB Usage", 100*(1-response.kb_missed/response.total_ticket));
					globalView('div3', "Total FCR", 100*(1-response.fcr_missed/response.total_ticket_phone));
					globalView('div4', "Total FCR Resolvable", 100*(1-response.fcr_reso_missed/response.fcr_reso_total));
					drawGauge('#gauge1', [response.avg_tht.all[0]], 0, 20, 'THT', response.avg_tht.all[1]);
					drawGauge('#gauge2', [response.avg_tht.password[0]], 0, 10, 'THT(Password Reset Closure)', response.avg_tht.password[1]);
					drawPie('#priorityPie',response.priority);
					drawBar('#categoryPie',response.category);
					reloadPriority(response.critical,response.high,response.medium,response.low,response.planning);
					data_temp=response.ticket_all;
					reloadSelect(data_temp.product,'#product');
					draw(data_temp.all,'ticketsChart');
					drawMap(response.countryChart);
					reloadMissed(response.total_ticket, response.ci_missed, response.kb_missed, response.fcr_missed, response.fcr_reso_missed, response.fcr_reso_total);
				}
			});
});	
});
function reloadPriority (critical,high,medium,low,planning) {
	$('#pr').children().eq(0).text(critical)
	$('#pr').children().eq(1).text(high)
	$('#pr').children().eq(2).text(medium)
	$('#pr').children().eq(3).text(low)
	$('#pr').children().eq(4).text(planning)
}
function reloadSelect (data,id) {
	var str='<option value="all">All</option>';
	for (var property in data) {
		if (data.hasOwnProperty(property)) {
			str+='<option value='+property+'>'+property+'</option>';
		}
	}
	$(id).html(str);
}
function reloadMissed(total_ticket, ci_missed, kb_missed, fcr_missed, fcr_reso_missed, fcr_reso_total) {
	$('.total_ticket').html(total_ticket);
	$('#ci_missed').html(ci_missed);
	$('#kb_missed').html(kb_missed);
	$('#fcr_missed').html(fcr_missed);
	$('#fcr_reso_missed').html(fcr_reso_missed);
	$('#fcr_reso_total').html(fcr_reso_total);
}
</script>
<!-- End Ajax -->

<!-- Tickets Per Hour Chart -->
<script language="JavaScript">
	var data=data_temp.all;
	$(".select2").change(function() {
		var v=$(this).val();
		if(v=='all'){
			data=data_temp.all;
		}else{
			data=data_temp.product[v];
		}
		draw(data,'ticketsChart');
	});
	draw(data,'ticketsChart');
	function draw(d,id) {
		var ticketsData = [];
		for (var i = 0; i < d.length; i++) {
			ticketsData.push({
				date: new Date(d[i].CreatedYear, d[i].CreatedMonth - 1, d[i].CreatedDay, d[i].CreatedHour, d[i].CreatedMinute, d[i].CreatedSecond, 0),
				visits: d[i].count,

			});
		};
		var chart1 = AmCharts.makeChart(id, {
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

<!-- Compare Weeks -->
<script>
	$('.week-picker').daterangepicker({
		"dateLimit": {
			"days": 30
		},
		format: 'YYYY/MM/DD'
	}, function(start, end, label) {
		/*console.log("New date range selected: ' + start.format('YYYY-DD-MM') + ' to ' + end.format('YYYY-DD-MM') + ' (predefined range: ' + label + ')");*/
	});
	$(document).ready(function(){
		$('#compare').click(function(){
			var start_date=$('input[name=start_date]').val();
			var end_date=$('input[name=end_date]').val();
			console.log($('#product-week').val());
			$.ajax({
				url: 'jib',
				type: "post",
				data: {'start_date':start_date,'end_date':end_date,'product':$('#product-week').val(), '_token': $('input[name=_token]').val()},
				success: function(response){
					var data=response[0];
					var datao=response[1];
					var chartData=[];
					if (data.length==0 && datao.length==0)
					{   
						chartData.push({
							date: new Date(),
							visits: 0,
							hits : 0
						});
					}
					if(data.length>datao.length){
						for (var i = 0; i < data.length; i++) {
							try{
								chartData.push({
									date: new Date(data[i].CreatedYear, data[i].CreatedMonth-1, data[i].CreatedDay,data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
									visits: data[i].count,
									hits : datao[i].count
								});
							}catch(err){
								chartData.push({
									date: new Date(data[i].CreatedYear, data[i].CreatedMonth-1, data[i].CreatedDay, data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
									visits: data[i].count,
									hits : 0,
								});
							}
						}
					}else{
						for (var i = 0; i < datao.length; i++) {
							try{
								chartData.push({
									date: new Date(datao[i].CreatedYear, datao[i].CreatedMonth-1, datao[i].CreatedDay, datao[i].CreatedHour, 0),
									visits: data[i].count,
									hits : datao[i].count,
									

								});
							}catch(err){
								chartData.push({
									date: new Date(datao[i].CreatedYear, datao[i].CreatedMonth-1, datao[i].CreatedDay, datao[i].CreatedHour, 0),
									visits: 0,
									hits : datao[i].count,
									
								});
							}
						}
					}
					var weekday=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
					function formatLabel(value, date, categoryAxis){
						if (date.getHours()==0){
							return weekday[date.getDay()];}
							else {return String(date.getHours())+":"+String(date.getMinutes())};
						}
						var chart = AmCharts.makeChart("weeks", {
							"type": "serial",
							"theme": "light",
							"legend": {
								"useGraphSettings": true
							},
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
								"title": "week<br>"+ " "+start_date ,
								"valueField": "visits",
								"useLineColorForBulletBorder": true
							},
							{
								"id": "g2",
								"balloonText": "[[category]]<br/><b><span style='font-size:14px;'>value: [[value]]</span></b>",
								"bullet": "round",
								"bulletBorderAlpha": 1,
								"bulletColor": "#FFFFFF",
								"hideBulletsCount": 50,
								"title": "week<br>"+ " "+end_date ,
								"valueField": "hits",
								"useLineColorForBulletBorder": true
							}
							],
							"chartScrollbar": {
								"autoGridCount": true,
								"graph": "g1",
								"scrollbarHeight": 40
							},
							"chartCursor": {
								"categoryBalloonDateFormat": "JJ h",
								"cursorPosition": "mouse"
							},
							"categoryField": "date",
							"categoryAxis": {
								"parseDates": true,
								"labelFunction":formatLabel,
								"minPeriod": "mm",
								"axisColor": "#DADADA",
								"dashLength": 1,
								"minorGridEnabled": true
							},
							"export": {
								"enabled": true
							}
						});
chart.addListener("rendered", zoomChart);
zoomChart();
						// this method is called when chart is first inited as we listen for "dataUpdated" event
						function zoomChart() {
						// different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
						chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
					}
				}
			});
});
});
</script>
<!-- End Compare Weeks -->

<!-- Gauge Chart -->
<script type="text/javascript">
	drawGauge('#gauge1', [{{ $avg_tht['all'][0] }}], 0, 20, 'THT', '{{ $avg_tht['all'][1] }}');
	drawGauge('#gauge2', [{{ $avg_tht['password'][0] }}], 0, 10, 'THT(Password Reset Closure)', '{{ $avg_tht['password'][1] }}');
	function drawGauge (id,dataGauge,mn,mx,txt,t) {
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
		[0.1, '#A0D9FF'], // blue
		[0.5, '#7CB5EC'], // yellow
		[0.9, '#346DA4'] // red
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
$(id).highcharts(Highcharts.merge(gaugeOptions, {
	yAxis: {
		min: mn,
		max: mx,
		title: {
			text: txt
		}
	},
	credits: {
		enabled: false
	},
	series: [{
		name: 'Speed',
		data: dataGauge,
		dataLabels: {
			format: '<div style="text-align:center"><span style="font-size:25px;color:' +
			((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
			'<span style="font-size:14px;color:blue;font-family:Open Sans">'+t+'</span></div>'
		}
	}]
}));
}
</script>
<!-- End Gauge Chart -->

<!-- Priority Pie Chart -->
<script language="JavaScript">
	drawPie('#priorityPie',JSON.parse('<?php echo json_encode($priority); ?>'));
	// Make monochrome colors and set them as default for all pies
	function drawPie(id,dataPriority){
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
	$(id).highcharts({
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
			data: dataPriority
		}]
	});
}
</script>
<!-- End Priority Pie Chart -->

<!-- Global View Chart -->
<script language="JavaScript">
	globalView('div1', "Total CI Usage", 100*(1-{{ $ci_missed/$total_ticket }}));
	globalView('div2', "Total KB Usage", 100*(1-{{ $kb_missed/$total_ticket }}));
	globalView('div3', "Total FCR", 100*(1-{{ $fcr_missed/$total_ticket_phone }}));
	globalView('div4', "Total FCR Resolvable", 100*(1-{{ $fcr_reso_missed/$fcr_reso_total }}));
	function globalView(id, title, value) {
		if (isNaN(value)) {
			value=0;
		};
		var rp1 = radialProgress(document.getElementById(id))
		.label(title)
		.diameter(180)
		.value(value)
		.render();
	}
</script>
<!-- End Global View Chart -->

<!-- Map Chart -->
<script src="{{ asset('/js/map.js') }}"></script>
<script src="{{ asset('/js/data.js') }}"></script>
<script src="{{ asset('/js/exporting.js') }}"></script>
<script src="{{ asset('/js/world.js') }}"></script>
<script>
	var dataTemp=<?php echo json_encode($countryChart); ?>;
	drawMap(dataTemp);

	function drawMap (dataTemp) {
		$('#regions_div').highcharts('Map', {
			title : {
				text : ''
			},
			mapNavigation: {
				enabled: true,
				enableDoubleClickZoomTo: true
			},
			colorAxis: {
				min: 1,
				max: 2000,
				type: 'logarithmic'
			},
			series : [{
				data : dataTemp,
				mapData: Highcharts.maps['custom/world'],
				joinBy: ['iso-a2', 'code'],
				name: 'Number of Tickets',
				states: {
					hover: {
						color: '#BADA55'
					}
				},
				tooltip: {
					valueSuffix: ' ticket'
				}
			}]
		});
	}

</script>
<!-- End Map Chart -->

<!-- Others Script -->
<script>
	$('.select2').select2();
</script>
<!-- End Others Script -->

<script type="text/javascript">
	drawBar('#categoryPie',JSON.parse('<?php echo json_encode($category); ?>'));
	function drawBar(id,dataBar){
		$(id).highcharts({
			chart:{
				type:'bar'
			},
			exporting: { enabled: false },
			tooltip: {
				valueSuffix: ' ticket'
			},
			title: {
				text: ' '
			},
			credits: {
				enabled: false
			},
			xAxis: {
				categories: dataBar[0]
			},
			series: [{
				name: 'EKMS Usage',
				showInLegend: false,
				data: dataBar[1]
			}]
		});
	}
</script>
@endsection