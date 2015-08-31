@extends('managerViews/layout')
@section('title', ' Manage Languages')
@section('style')
@endsection
@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title" style="padding-top:5px" id="title">
			Languages
		</h3>
		<a class="btn btn-social btn-primary pull-right" id="btn-add"  data-toggle="modal" data-target="#myModal">
			<i class="fa fa-plus"></i> Add Language
		</a>
	</div><!-- /.box-header -->
	<div class="box-body" id="directory-user">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-10 col-lg-offset-1">
					<table class="table">
						<tr>
							<th>#</th>
							<th>Languages</th>
							<th>Employee/language</th>
							<th>Actions</th>
						</tr>
						@foreach($languages as $language)
						<tr>
							<td>{{$language->Id}}</td>
							<td>{{$language->language}}</td>
							<td>{{$language->language}}</td>
							<td><button class="btn btn-danger">Delete</button></td>
						</tr>
						@endforeach
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
				<h4 class="modal-title">Add Language</h4>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" role="form">
					<legend>New Language</legend>
					<div class="form-group">
						<label for="">Language</label>
						<input type="text" class="form-control" id="" placeholder="Language">
					</div>
					<button type="submit" class="btn btn-primary">Add</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')

@endsection