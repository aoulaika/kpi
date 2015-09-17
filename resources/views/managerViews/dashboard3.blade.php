@extends('managerViews/layout')
@section('title', ' Agents Dashboard')
@section('page_title')
Dashboard
<small>Agents</small>
@endsection
@section('style')
<style>
    #csiTracking{
        height: 210px;
    }
</style>
@endsection
@section('page_current')
<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboards</a></li>
<li class="active">Agents dashboard</li>
@endsection
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
                    <span class="pull-right" id="range"><span class="date" id="total_ticket">{{ $total_ticket }}</span> Tickets Handled between :  <span class="date" id="datedeb">{{ $begin }}</span> and : <span class="date" id="datefin">{{ $end }}</span></span>
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
                        <img src="{{ asset('/img/default-user.png') }}" id="agent-image" class="agent-image"/>
                        <h3 class="agent-name" id="agent_name"></h3>
                        <h4 class="minititle">Handled <span id="nbr" style="color: #44A1C1;"></span> Tickets</h4>
                        <h5 class="minititle">Missed <span style="color: red;" id="missed_resolvable"> {{ $fcr_reso_missed }}/{{ $fcr_reso_total }}</span> Resolvable Tickets </h5>
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
                <h3 class="titles">CSI Tracking</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <h5 class="minititle">Current CSI <span style="color: red;" id="csi"> {{ $csi->rate }}</span> For <span style="color: red;" id="csi-count">{{ $csi->count }}</span> Surveys</h5>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="minititle">CSI With Scrub <span style="color: red;" id="csiscrub"> {{ $csi_scrub->rate }}</span> For <span style="color: red;" id="csiscrub-count">{{ $csi_scrub->count }}</span> Surveys</h5>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="btn-group btn-group">
                        <a class="btn btn-xs btn-default active scrub-track">Current CSI</a>
                        <a class="btn btn-xs btn-default scrub-track">CSI With Scrub</a>
                    </div>
                </div>
                <div class="row">
                    <div id="csiTracking"></div>
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
                                <option value="{{ $key }}">{{ $key }}</option>
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
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div id="container1bis" class="containerbis"></div>
                            <hr>
                            <h3 class="titles">All agents comparison</h3>
                            <div class="containerscroll customScroll" data-mcs-theme="dark">
                                <div id="container1" class="containerbar"></div>
                            </div>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div id="container2bis" class="containerbis"></div>
                            <hr>
                            <h3 class="titles">All agents comparison</h3>
                            <div class="containerscroll customScroll" data-mcs-theme="dark">
                                <div id="container2" class="containerbar"></div>
                            </div>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <div id="container3bis" class="containerbis"></div>
                            <hr>
                            <h3 class="titles">All agents comparison</h3>
                            <div class="containerscroll customScroll" data-mcs-theme="dark">
                                <div id="container3" class="containerbar"></div>
                            </div>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_4">
                            <div id="container4bis" class="containerbis"></div>
                            <hr>
                            <h3 class="titles">All agents comparison</h3>
                            <div class="containerscroll customScroll" data-mcs-theme="dark">
                                <div id="container4" class="containerbar"></div>
                            </div>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_5">
                            <div id="container5bis" class="containerbis"></div>
                            <hr>
                            <h3 class="titles">All agents comparison</h3>
                            <div class="containerscroll customScroll">
                                <div id="container5" class="containerbar"></div>
                            </div>
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
<!-- Select2 -->
<script src="{{ asset('/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/function.js') }}" type="text/javascript"></script>
<!-- End Percentage Counter -->
<!-- date range picker -->
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
<!-- END date range picker -->
<script>
    var csiTracking = JSON.parse('<?php echo json_encode($csi_tracking); ?>');
    csiTrack('csiTracking', csiTracking);
</script>
<!-- Script Change User -->
<script>
    var ci_temp = JSON.parse('<?php echo json_encode($ci_users); ?>');
    var kb_temp = JSON.parse('<?php echo json_encode($kb_users); ?>');
    var fcr_temp = JSON.parse('<?php echo json_encode($fcr_users); ?>');
    var fcr_reso_temp = JSON.parse('<?php echo json_encode($fcr_reso_users); ?>');
    var tht_temp = JSON.parse('<?php echo json_encode($tht); ?>');
    var tickets_per_agent = JSON.parse('<?php echo json_encode($tickets_per_agent); ?>');
    var prc_nbr_temp = JSON.parse('<?php echo json_encode($prc_nbr); ?>');
    var tickets_users_temp = JSON.parse('<?php echo json_encode($tickets_users); ?>');
    
    var tht_password=tht_temp[0].tht_password;
    var tht_time=tht_temp[0].tht_time;
    var tht_password_time=tht_temp[0].tht_password_time;
    var prc_nbr=prc_nbr_temp[0].count;

    bar('#container1bis', '%', 'Percentage', ci_temp[0].Name, kb_temp[0].count, [c_max(kb_temp)], [c_avg(kb_temp)], [c_min(kb_temp)]);
    bar('#container2bis', '%', 'Percentage', ci_temp[0].Name, ci_temp[0].count, [c_max(ci_temp)], [c_avg(ci_temp)], [c_min(ci_temp)]);
    bar('#container3bis', '%', 'Percentage', ci_temp[0].Name, fcr_temp[0].count, [c_max(fcr_temp)], [c_avg(fcr_temp)], [c_min(fcr_temp)]);
    bar('#container4bis', '%', 'Percentage', ci_temp[0].Name, fcr_reso_temp[0].count, [c_max(fcr_reso_temp)], [c_avg(fcr_reso_temp)], [c_min(fcr_reso_temp)]);
    bar('#container5bis', '', 'Number of Tickets', ci_temp[0].Name, [tickets_per_agent[0].count], [c_max(tickets_per_agent)], [c_avg(tickets_per_agent)], [c_min(tickets_per_agent)]);
    
    $("#agent").change(function() {
        var v=$(this).val();

        doit(ci_temp[v].Name, tickets_per_agent[v].count, ci_temp[v].count, kb_temp[v].count, fcr_temp[v].count, fcr_reso_temp[v].count);

        gauge('#gauge1', 0, 20, tickets_per_agent[v].count+' Tickets', [parseInt(tht_temp[v].tht)], tht_temp[v].tht_time);
        gauge('#gauge2', 0, 10, prc_nbr_temp[v].count+' Tickets ', [parseInt(tht_temp[v].tht_password)], tht_temp[v].tht_password_time);

        bar('#container1bis', '%', 'Percentage', ci_temp[v].Name, kb_temp[v].count, [c_max(kb_temp)], [c_avg(kb_temp)], [c_min(kb_temp)]);
        bar('#container2bis', '%', 'Percentage', ci_temp[v].Name, ci_temp[v].count, [c_max(ci_temp)], [c_avg(ci_temp)], [c_min(ci_temp)]);
        bar('#container3bis', '%', 'Percentage', ci_temp[v].Name, fcr_temp[v].count, [c_max(fcr_temp)], [c_avg(fcr_temp)], [c_min(fcr_temp)]);
        bar('#container4bis', '%', 'Percentage', ci_temp[v].Name, fcr_reso_temp[v].count, [c_max(fcr_reso_temp)], [c_avg(fcr_reso_temp)], [c_min(fcr_reso_temp)]);
        bar('#container5bis', '', 'Number of Tickets', ci_temp[v].Name, [tickets_per_agent[v].count], [c_max(tickets_per_agent)], [c_avg(tickets_per_agent)], [c_min(tickets_per_agent)]);

        $.ajax({
            url: 'changeAgent',
            type: "post",
            data: {
                '_token': $('input[name=_token]').val(),
                'datedeb': $('#datedeb').text(),
                'datefin': $('#datefin').text(),
                'agent_id': parseInt($('#agent').val())+1
            },
            success: function(response){
                /* Setting values for tickets chart */
                console.log(response);
                /*csi*/
                csiTrack('csiTracking', response.csi_tracking);
                $('#csi').text(response.csi.rate);$('#csi-count').text(response.csi.count);
                $('#csiscrub').text(response.csi_scrub.rate);$('#csiscrub-count').text(response.csi_scrub.count);
                var img = "{{ asset('/img/default-user.png') }}";
                'localhost/kpi/public/img/default-user.png';
                if(response.user_pic != 'default-user.png'){
                    var i="/images/"+response.user_pic;
                    console.log(i);
                    img = "http://kpi.dev"+i;
                }
                $('#agent-image').attr('src', img);
                reloadSelect(response.tickets_all.product,'#product');
                draw(response.tickets_all.all);
                $('#missed_resolvable').html(response.fcr_reso_missed+"/"+response.fcr_reso_total);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr.responseText);
            }
        });
});
doit(ci_temp[0].Name, tickets_per_agent[0].count, ci_temp[0].count,kb_temp[0].count, fcr_temp[0].count, fcr_reso_temp[0].count);
gauge('#gauge1', 0, 20, tickets_per_agent[0].count+' Tickets', [parseInt(tht_temp[0].tht)], tht_temp[0].tht_time);
gauge('#gauge2', 0, 10, prc_nbr_temp[0].count+' Tickets', [parseInt(tht_temp[0].tht_password)], tht_temp[0].tht_password_time);

</script>
<!-- End Script Change User -->
<!-- Ajax Reloading data when Changing date range -->
<script>
    $('#daterange-agent').on('apply.daterangepicker', function(ev, picker) {
            //console.log(picker.startDate.format('YYYY-MM-DD'));
            //console.log(picker.endDate.format('YYYY-MM-DD'));
            $('#datedeb').html(picker.startDate.format('YYYY-MM-DD'));
            $('#datefin').html(picker.endDate.format('YYYY-MM-DD'));
            $.ajax({
                url: 'rangedate',
                type: "post",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'datedeb': picker.startDate.format('YYYY-MM-DD'),
                    'datefin': picker.endDate.format('YYYY-MM-DD'),
                    'agent_id': parseInt($('#agent').val())+1
                },
                success: function(response){
                    console.log(response);
                    tickets_per_agent=response.tickets_per_agent;
                    ci_temp=response.ci_usage;
                    kb_temp=response.kb_usage;
                    fcr_temp=response.fcr;
                    fcr_reso_temp = response.fcr_reso;
                    tht_temp = response.tht;
                    prc_nbr_temp = response.prc_nbr;

                    /* Getting agent Id*/
                    var v=$('#agent').val();

                    /*csi*/
                    $('#csi').text(response.csi.rate);$('#csi-count').text(response.csi.count);
                    $('#csiscrub').text(response.csi_scrub.rate);$('#csiscrub-count').text(response.csi_scrub.count);

                    csiTrack('csiTracking', response.csi_tracking);
                    /* Setting html values and graphes */
                    doit(ci_temp[v].Name,tickets_per_agent[v].count,ci_temp[v].count,kb_temp[v].count,fcr_temp[v].count,fcr_reso_temp[v].count);
                    gauge('#gauge1',0,20,tickets_per_agent[v].count+' Tickets',[tht_temp[v].tht],tht_temp[v].tht_time);
                    gauge('#gauge2',0,10,prc_nbr_temp[v].count+' Tickets',[tht_temp[v].tht_password],tht_temp[v].tht_password_time);
                    /* Setting values for tickets chart */
                    bar('#container1bis','%','Percentage',ci_temp[v].Name,kb_temp[v].count,[c_max(kb_temp)],[c_avg(kb_temp)],[c_min(kb_temp)]);
                    bar('#container2bis','%','Percentage',ci_temp[v].Name,ci_temp[v].count,[c_max(ci_temp)],[c_avg(ci_temp)],[c_min(ci_temp)]);
                    bar('#container3bis','%','Percentage',ci_temp[v].Name,fcr_temp[v].count,[c_max(fcr_temp)],[c_avg(fcr_temp)],[c_min(fcr_temp)]);
                    bar('#container4bis','%','Percentage',ci_temp[v].Name,fcr_reso_temp[v].count,[c_max(fcr_reso_temp)],[c_avg(fcr_reso_temp)],[c_min(fcr_reso_temp)]);

                    bar('#container5bis','','Number of Tickets',ci_temp[v].Name,[tickets_per_agent[v].count],[c_max(tickets_per_agent)],[c_avg(tickets_per_agent)],[c_min(tickets_per_agent)]);

                    drawBar('#container2',response.ci_names,'CI Usage','%',response.ci_ord);
                    drawBar('#container1',response.kb_names,'EKMS Usage','%',response.kb_ord);
                    drawBar('#container3',response.fcr_names,'FCR','%',response.fcr_ord);
                    drawBar('#container4',response.fcr_reso_names,'FCR Resolvable','%',response.fcr_reso_ord);
                    drawBar('#container5',response.ticket_ord_users,'Number of Ticket','',response.ticket_ord_value);
                    reloadSelect(response.tickets_all.product,'#product');
                    draw(response.tickets_all.all);
                    $('#total_ticket').text(response.total_ticket);
                    $('#missed_resolvable').html(response.fcr_reso_missed+"/"+response.fcr_reso_total);
                }
            });
});
</script>
<!-- Tickets Per Hour Chart -->
<script language="JavaScript">
    var data_temp = JSON.parse('<?php echo json_encode($tickets_all); ?>');
    $("#product").change(function() {
        var v=$(this).val();
        if(v=='all'){
            draw(data_temp.all);
        }else{
            draw(data_temp.product[v]);
        }
    });
    draw(data_temp.all);
</script>
<!-- End Change date range -->
<!-- End Tickets Per Hour Chart -->
<script type="text/javascript">
    //Applying select2
    $(".select2").select2();
    //Date range picker
    $('#reservation').daterangepicker({format: 'YYYY/MM/DD'});
    
</script>
<!-- Bar Chart -->
<script type="text/javascript">
    var ci_names=JSON.parse('<?php echo json_encode($ci_names); ?>');
    var ci_data=JSON.parse('<?php echo json_encode($ci); ?>');
    drawBar('#container2',ci_names,'CI Usage','%',ci_data);

    var kb_names=JSON.parse('<?php echo json_encode($kb_names); ?>');
    var kb_data=JSON.parse('<?php echo json_encode($kb); ?>');
    drawBar('#container1',kb_names,'EKMS Usage','%',kb_data);

    var fcr_names=JSON.parse('<?php echo json_encode($fcr_names); ?>');
    var fcr_data=JSON.parse('<?php echo json_encode($fcr); ?>');
    drawBar('#container3',fcr_names,'FCR','%',fcr_data);

    var fcr_reso_names=JSON.parse('<?php echo json_encode($fcr_reso_names); ?>');
    var fcr_reso_data=JSON.parse('<?php echo json_encode($fcr_reso); ?>');
    drawBar('#container4',fcr_reso_names,'FCR Resolvable','%',fcr_reso_data);

    var ticket_ord_users=JSON.parse('<?php echo json_encode($ticket_ord_users); ?>');
    var ticket_ord_value=JSON.parse('<?php echo json_encode($ticket_ord_value); ?>');
    drawBar('#container5',ticket_ord_users,'Number of Ticket','',ticket_ord_value);
</script>
<!-- End Bar Chart -->
<!-- CSI Tracking -->
<script>
    $('.scrub-track').click(function () {
        $('.scrub-track.active').removeClass('active');
        $(this).addClass('active');
        var val = ($(this).text() == 'Current CSI') ? 0 : 1;
        var txt = $(this).text();
        var id=parseInt($('#agent').val())+1;
        $.ajax({
            url: 'reloadTrack',
            type: "get",
            data: {
                'scrub': val,
                'agent_id': id,
                'debut': $('#datedeb').text(),
                'fin': $('#datefin').text()
            },
            success: function (response) {
                console.log(response);
                csiTrack('csiTracking', response.csi_tracking);
            },error: function (response) {
                console.log(response.responseText);
            }
        });
    });
</script>
<!-- END CSI Tracking -->
<script>
    $(".customScroll").mCustomScrollbar();
</script>
@endsection