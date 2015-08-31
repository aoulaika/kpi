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
		<a class="btn btn-social btn-primary pull-right" id="btn-add">
			<i class="fa fa-plus"></i> Add Language
		</a>
	</div><!-- /.box-header -->
	<div class="box-body" id="directory-user">
		<div class="container-fluid">
			<div class="row">
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
@endsection

@section('script')

@endsection