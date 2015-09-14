@extends('managerViews/layout')
@section('title', ' Errors Tracking')
@section('page_title')
    Errors
    <small>Tracking</small>
@endsection
@section('page_current')
    <li><a href="{{ route('errors') }}"><i class="fa fa-dashboard"></i> Errors</a></li>
    <li class="active">Tracking</li>
@endsection
@section('content')
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Errors Tracking</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="form-group form-inline">
                    <div class="input-group">
                        <button class="dt-button" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-search"></i> Advanced Filter
                        </button>
                    </div>

                    <div class="input-group">
                        <button class="dt-button daterange-btn" id="daterange-btn">
                            <i class="fa fa-calendar"></i> Choose a Date Range
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div><!-- /.form group -->
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="no-sort" >
                                <i class="fa fa-check"></i>
                            </th>
                            <th class="no-sort">Number</th>
                            <th>Date</th>
                            <th class="no-sort">Short_Descr</th>
                            <th class="no-sort">Ag_ID</th>
                            <th >Ag_Name</th>
                            <th>THT</th>
                            <th class="no-sort">Err_Type</th>
                            <th class="no-sort">RCA/Ag_Comment</th>
                            <th class="no-sort">Action</th>
                            <th class="no-sort">Remarks</th>
                            <th class="no-sort">Team</th>
                            <th class="no-sort">Accounted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($errors as $v)
                        <tr>
                            <input type="hidden" class="id" value="{{ $v->Id }}">
                            <td>
                                <input type="checkbox" class="check" {{ ($v->checked)?'checked':'' }}>
                            </td>
                            <td>{{ $v->Number }}</td>
                            <td>{{ $v->Created }}</td>
                            <td>{{ $v->Short_Description }}</td>
                            <td>{{ $v->Code }}</td>
                            <td>{{ $v->Name }}</td>
                            <td>{{ $v->hdl_time }}</td>
                            <td class="errType">{{ $v->error_type }}</td>
                            <td>
                                <input type="hidden" value="rca_ag_comment">
                                <a href="#" class="edit">{{ $v->rca_ag_comment }}</a>
                            </td>
                            <td>
                                <input type="hidden" value="action">
                                <a href="#" class="edit">{{ $v->action }}</a>
                            </td>
                            <td>
                                <input type="hidden" value="remarks">
                                <a href="#" class="edit">{{ $v->remarks }}</a>
                            </td>
                            <td>Sadki, Rania</td>
                            <td class="center">
                                <input type="checkbox" class="accounted" {{ ($v->checked)?'checked':'' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document" style="width:300px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Advanced Search</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <p><label>Error type :</label></p>
                            <select class="form-control">
                                <option>All</option>
                                <option>FCR</option>
                                <option>CI</option>
                                <option>KB</option>
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <p><label>Team :</label></p>
                            <select class="form-control">
                                <option>All</option>
                                <option>Sadki, Rania</option>
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <p><label>Checked :</label></p>
                            <label class="radio-inline">
                                <input type="radio" name="checked" id="yes" value="yes" class="check">
                                Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="checked" id="no" value="no" class="check">
                                No
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="checked" id="all" value="all" class="check" checked>
                                All
                            </label>
                        </div>
                        <div class="form-group text-center">
                            <p><label>Accounted :</label></p>
                            <label class="radio-inline">
                                <input type="radio" name="accounted" id="yes" value="yes" class="accounted">
                                Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="accounted" id="no" value="no" class="accounted">
                                No
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="accounted" id="all" value="all" class="accounted" checked>
                                All
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="update-id" value="">
        <input type="hidden" id="update-column" value="">
        <input type="hidden" id="errorType" value="">
@endsection
@section('script')
    <script src="{{ asset('js/bootstrap-editable.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/extensions/Buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/extensions/Buttons/js/buttons.colVis.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/extensions/Buttons/js/buttons.flash.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables/extensions/jszip.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/iCheck/icheck.js') }}" type="text/javascript"></script>
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
        $('.edit').click(function(){
            var id = $(this).closest('tr').children('input.id').val();
            var column = $(this).prev().val();
            var errorType = $(this).closest('tr').children('td.errType').text();
            $('#update-id').val(id);
            $('#update-column').val(column);
            $('#errorType').val(errorType);
        });
        $('.edit').editable({
            type: 'textarea',
            title: 'Enter text',
            rows: 6,
            showbuttons : 'bottom',
            success: function(response, newValue) {
                $.ajax({
                    url: 'updateErrors',
                    type: "get",
                    data: {
                        'id': $('#update-id').val(),
                        'column': $('#update-column').val(),
                        'newValue':newValue,
                        'errorType': $('#errorType').val()
                    },
                    success: function (response) {

                    }
                });
            }
        });


        $('.accounted').iCheck({
            checkboxClass: 'icheckbox_flat-red',
            radioClass: 'iradio_flat-red'
        });

        $('.check').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });

        var table = $('#example1').DataTable({
            dom : 'Bfrtlip',
            "aaSorting": [],
            "aoColumnDefs" : [ {
                "bSortable" : false,
                "aTargets" : [ "no-sort" ]
            } ],
            buttons: [
                'colvis','excel'
            ]
        });

        $('#daterange-btn').daterangepicker(
                {
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
        );
    </script>
@endsection