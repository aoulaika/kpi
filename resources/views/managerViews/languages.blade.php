@extends('managerViews/layout')
@section('title', ' Manage Languages')
@section('title', ' Agents Dashboard')
@section('page_title')
    Languages
@endsection
@section('page_current')
    <li><a href="{{ route('language') }}"><i class="fa fa-dashboard"></i> Languages</a></li>
    <li class="active">Managing</li>
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('/css/xeditable.css') }}">
<style>
	td{
		vertical-align: middle;
	}
	.hiddenRow {
		padding: 0 !important;
	}
	.fa-2x{
		color: grey;
	}
</style>
@endsection
@section('content')
<div class="container-fluid" ng-app="languageApp" ng-controller="languageCtrl">
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
						<table class="table table-hover">
							<tr>
								<th>#</th>
								<th>Languages</th>
								<th>Actions</th>
							</tr>
							<tr ng-repeat-start="language in languages" data-toggle="collapse" ng-click="angle('#i'+language.Id)" data-target="<% '#'+language.Id %>" class="accordion-toggle">
								<td><% language.Id %></td>
								<td><a editable-text="language.name" onbeforesave="edit($data,language.Id)"><% language.name || 'Empty' %></a></td>
								<td>
									<button class="btn btn-danger" ng-click="deleteLanguage(language.Id)">
										<i class="fa fa-trash"></i>
									</button>
								</td>
								<td>
									<i class="fa fa-2x fa-angle-down" id="<% 'i'+language.Id %>"></i>
								</td>
							</tr>
							<tr ng-repeat-end>
								<td colspan="3" class="hiddenRow">
									<div class="accordian-body collapse" id="<% language.Id %>">
										50 Employee/language
									</div>
								</td>
							</tr>
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
					<h4 class="modal-title">New Language</h4>
				</div>
				<form>
					<div class="modal-body">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" ng-model="language.token">
						<div class="form-group">
							<label for="language">Language</label>
							<input required class="form-control" id="language" placeholder="Language" ng-model="language.name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="submit" ng-click="addLanguage(language)" class="btn btn-primary">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script src="{{ asset('/js/angular.min.js') }}"></script>
<script src="{{ asset('/js/xeditable.js') }}"></script>
<script src="{{ asset('/js/languageCtrl.js') }}"></script>
@endsection