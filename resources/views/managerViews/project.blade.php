@extends('managerViews/layout')
@section('title', ' Manage Languages')
@section('title', ' Agents Dashboard')
@section('page_title')
    Projects
@endsection
@section('page_current')
    <li><a href="{{ route('project') }}"><i class="fa fa-dashboard"></i> Projects</a></li>
    <li class="active">Managing</li>
@endsection
@section('style')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title" style="padding-top:5px" id="title">
                    Projects
                </h3>
                <a class="btn btn-social btn-primary pull-right"  data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i> Add Project
                </a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-10 col-lg-offset-1">
                            @if(Session::has('success'))
                                <div class="callout callout-success">
                                    <h4>Yeah !</h4>
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                            @endif
                            <table class="table table-hover" style="border-collapse:collapse;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Logo</th>
                                        <th>Project Name</th>
                                        <th>Employee/project</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $project)
                                    <tr data-toggle="collapse" data-target="#{{ $project->id }}" class="accordion-toggle toggle-angle">
                                        <td class="idProj" style="vertical-align: middle;">{{ $project->id }}</td>
                                        <td style="vertical-align: middle;"><img style="height:50px;" src="{{ asset('/img/proj-img/'.$project->id.'.png') }}"></td>
                                        <td style="vertical-align: middle;">{{ $project->name }}</td>
                                        <td style="vertical-align: middle;">0</td>
                                        <td style="vertical-align: middle;">
                                            <button class="btn btn-danger removeProj">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button class="btn btn-primary">
                                                <i class="fa fa-info-circle"></i>
                                            </button>
                                        </td>
                                        <td class="angle" style="color:grey;vertical-align: middle;"><i class="fa fa-2x fa-angle-up"></i></td>
                                        <form id="{{ $project->id.'Delete' }}" action="{{ route('deleteProj') }}" method="post">
                                            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                            <input name="id" type="hidden" value="{{ $project->id }}" />
                                        </form>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="hiddenRow">
                                            <div class="accordian-body collapse" id="{{ $project->id }}">
                                                <div class="box" style="margin-bottom:0px;">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Sub-Projects</h4>
                                                        <a class="btn-xs btn-primary pull-right btn-add-sub"  data-toggle="modal" data-target="#subModal">
                                                            <i class="fa fa-plus"></i>
                                                            <input class="idSupProj" type="hidden" value="{{ $project->id }}">
                                                        </a>
                                                    </div><!-- /.box-header -->
                                                    <div class="box-body">
                                                        <div class="col-lg-10 col-lg-offset-1">
                                                            @if(in_array ($project->id,$projects_extended))
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Sub-project</th>
                                                                    <th>Employee/sub-project</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                                @foreach($sub_projects as $sub_project)
                                                                    @if($sub_project->fk_project == $project->id)
                                                                        <tr>
                                                                            <td>{{ $sub_project->name }}</td>
                                                                            <td>0</td>
                                                                            <td>
                                                                                <button class="btn btn-xs btn-danger removeSubProj">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                            <input class="idSubProj" type="hidden" value="{{ $sub_project->id }}"/>
                                                                        </tr>
                                                                        <form id="{{ $sub_project->id }}" action="{{ route('deleteSubProj') }}" method="post">
                                                                            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                                                            <input name="id" type="hidden" value="{{ $sub_project->id }}" />
                                                                        </form>
                                                                    @endif
                                                                @endforeach
                                                            </table>
                                                            @else
                                                            <p style="text-align:center;">None ! You can
                                                                <a class="btn-add-sub" style="cursor:pointer" data-toggle="modal" data-target="#subModal">
                                                                    add one
                                                                    <input class="idSupProj" type="hidden" value="{{ $project->id }}">
                                                                </a>
                                                            </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Project</h4>
                    </div>
                    <form action="{{ route('addProject') }}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="language">Project Name</label>
                                <input required class="form-control" name="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="photo">Logo</label>
                                <input required type="file" name="photo" class="form-control" >
                            </div>
                            <div class="form-group">
                                <strong style="color:darkred">Note :</strong> Logo must be .png image
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="subModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Sub Project</h4>
                    </div>
                    <form action="{{ route('addSubProj') }}" method="post" >
                        <div class="modal-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="language">Sub-Project Name</label>
                                <input required class="form-control" name="name" placeholder="Name">
                            </div>
                            <input name="idSupProject" value="" type="hidden"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $('.toggle-angle').on('click', function(e) {
        var icon = $(this).closest('tr').children('td.angle').children();
        icon.toggleClass("fa-angle-up fa-angle-down"); //you can list several class names
        e.preventDefault();
    });

    $('.removeProj').on('click', function(e) {
        var id=$(this).closest('tr').children('td.idProj').text();
        $('#'+id+'Delete').submit();
        e.preventDefault();
        e.stopPropagation();
    });

    $('.btn-add-sub').on('click',function(e){
        var id=$(this).children('input.idSupProj').val();
        console.log(id);
        $("input[name='idSupProject']").val(id);
        e.preventDefault();
    });

    $('.removeSubProj').on('click', function(e) {
        var id=$(this).closest('tr').children('input.idSubProj').val();
        $('#'+id).submit();
        e.preventDefault();
        e.stopPropagation();
    });
</script>
@endsection