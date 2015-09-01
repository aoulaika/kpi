@extends('managerViews/layout')
@section('title', ' Manage Languages')
@section('style')
<link rel="stylesheet" href="{{ asset('/css/xeditable.css') }}">
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
								<th>Employee/language</th>
								<th>Actions</th>
							</tr>
							<tr ng-repeat="language in languages">
								<td><% language.Id %></td>
								<td><a editable-text="language.language" onbeforesave="edit($data,language.Id)"><% language.language || 'Empty' %></a></td>
								<td><% language.language %></td>
								<td>
									<button class="btn btn-danger" ng-click="deleteLanguage(language.Id)">
										<i class="fa fa-trash"></i>
									</button>
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