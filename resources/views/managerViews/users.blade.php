@extends('managerViews/layout')
@section('title', ' Manage Users')
@section('page_title')
    Employees
    <small>Directory</small>
@endsection
@section('page_current')
    <li><a href="{{ route('users') }}"><i class="fa fa-dashboard"></i> Employees</a></li>
    <li class="active">Managing</li>
@endsection
@section('style')
<link href="{{ asset('/css/style3.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('/css/xeditable.css') }}">
<link href="{{ asset('/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<style>
	.modal-header{
		padding: 0;
	}
	.title-user{
		margin-top: 25px;
	}
	.modal-body{
		padding: 0;
	}
	a.editable-click {
		color: black;
		border-bottom: none;
	}
	a.editable-click:hover {
		border-bottom-color: #47a447;
	}
	.details td{
		width: 50%;
	}
	input[name="account_id[]"]{
		margin-left: 5px;
		margin-right: 5px;
		width: 97%;
	}
	.span{
		font-size: 1.3em;
	}
</style>
@endsection
@section('content')
<div ng-app="app" ng-controller="ctrl">
	<div class="box box-default">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-lg-4">
					<img ng-show="project.id || false" style="height:60px" src="{{ asset('/img/proj-img/<% project.id+".png" %>') }}" alt="">
				</div>
				<div class="col-lg-4">
				<h2 ng-show="team.text">Team <span ng-bind="team.text"></span></h2>
				</div>
				<div class="col-lg-4">
					<a class="btn btn-social btn-primary pull-right" id="btn-add">
						<i class="fa fa-plus"></i> Add Employee
					</a>
				</div>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body" id="directory-user">
			<div class="container-fluid">
				<header class="clearfix">
					<div class="form-inline pull-left">
						<select ng-model="project" ng-options="project.name for project in accounts track by project.id" class="form-control">
							<option value="">All</option>
						</select>
						<select ng-model="team" ng-options="team.text for team in teams | filter:{'value':'!'+0} track by team.value" class="form-control">
							<option value="">All</option>
						</select>
					</div>
					<div class="form-inline pull-right">
						<div class="form-group">
							<div style="width:350px" class="input-group">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
								<input style="width:350px" type="text" class="form-control"  ng-model="searchText" type="search" placeholder="Type name, email, role, job ... etc">
								<span class="input-group-addon" ng-click="searchText = ''" ng-show="searchText != ''"><i class="fa fa-eraser"></i></span>
							</div>
						</div>
					</div>
				</header>
				<div class="row">
					<div ng-repeat="item in items | filter:{'project_id':project.id} | filter:{'team':team.value} | filter:searchText" class="col-lg-4 col-md-6 box-user">
						<div class="col-xs-5">
							<a class="image" ng-click="editItem(item)" data-toggle="modal" data-target="#myModal">
								<span class="rollover"></span>
								<img class="imgborder" alt="" src="{{ asset('img/default-user.png') }}">
							</a>
						</div>
						<div class="col-xs-7">
							<blockquote>
								<img src="{{ asset('/img/<% item.status %>.png') }}" alt="">
								<span ng-bind="item.firstname+' '+item.lastname"></span>
								<small><cite title="Source Title"><% item.role %></cite></small>
							</blockquote>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog" ng-style="(selectedItem.role=='N1'||selectedItem.role=='N2')?{width:'75%'}:{width:'50%'}">
					<div class="modal-content">
						<div class="modal-header">
							<div class="row">
								<div class="col-lg-2">
									<div class="media">
										<a class="pull-left" href="#">
											<img class="media-object dp img-circle" ng-src="{{ asset('/images/<% selectedItem.picture %>') }}" style="width: 100px;height:100px;">
										</a>
									</div>
								</div>
								<div class="col-lg-10 title-user">
									<h4>
										<strong><% selectedItem.firstname+' '+selectedItem.lastname %></strong>
									</h4>
									<h5>
										<% selectedItem.role %>
									</h5>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div ng-class="(selectedItem.role=='N1'||selectedItem.role=='N2')?'col-lg-6':'col-lg-12'">
									<div class="nav-tabs-custom">
										<ul class="nav nav-tabs">
											<li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="true">Personal details</a></li>
											<li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Professional details</a></li>
											<li class=""><a href="#tab_3-3" data-toggle="tab" aria-expanded="false">Tools</a></li>
											<li class=""><a href="#tab_4-4" data-toggle="tab" aria-expanded="false">Projects</a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab_1-1">
												<table class="table details">
													<tr>
														<td><b>Employe Id</b></td>
														<td>
															<a editable-text="selectedItem.employe_id" onbeforesave="editUser(selectedItem.Id,'employe_id',$data)">
																<% selectedItem.employe_id || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Matricule</b></td>
														<td>
															<a editable-text="selectedItem.matricule" onbeforesave="editUser(selectedItem.Id,'matricule',$data)">
																<% selectedItem.matricule || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Last Name</b></td>
														<td>
															<a editable-text="selectedItem.lastname" onbeforesave="editUser(selectedItem.Id,'lastname',$data)">
																<% selectedItem.lastname || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>First Name</b></td>
														<td>
															<a editable-text="selectedItem.firstname" onbeforesave="editUser(selectedItem.Id,'firstname',$data)">
																<% selectedItem.firstname || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>E-mail</b></td>
														<td>
															<a editable-email="selectedItem.email" onbeforesave="editUser(selectedItem.Id,'email',$data)">
																<% selectedItem.email || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>City</b></td>
														<td>
															<a editable-text="selectedItem.city" onbeforesave="editUser(selectedItem.Id,'city',$data)">
																<% selectedItem.city || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Adress</b></td>
														<td>
															<a editable-text="selectedItem.adress" onbeforesave="editUser(selectedItem.Id,'adress',$data)">
																<% selectedItem.adress || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Phone</b></td>
														<td>
															<a editable-text="selectedItem.phone" onbeforesave="editUser(selectedItem.Id,'phone',$data)">
																<% selectedItem.phone || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Languages</b></td>
														<td>
															<a editable-select="user_languages" e-multiple e-ng-options="l.Id as l.name for l in languages" onbeforesave="editUser(selectedItem.Id,'language',$data)">
																<% showLanguages() %>
															</a>
														</td>
													</tr>
												</table>
											</div><!-- /.tab-pane -->
											<div class="tab-pane" id="tab_2-2">
												<table class="table details">
													<tr>
														<td><b>Job</b></td>
														<td>
															<a href="#" editable-select="selectedItem.job" e-ng-options="j.value as j.text for j in jobes" onbeforesave="editUser(selectedItem.Id,'job',$data)">
																<% selectedItem.job || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Role</b></td>
														<td>
															<a href="#" editable-select="selectedItem.role" e-ng-options="r.value as r.text for r in roles" onbeforesave="editUser(selectedItem.Id,'role',$data)">
																<% selectedItem.role || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Team</b></td>
														<td>
															<a href="#" editable-select="selectedItem.team" e-ng-options="t.value as t.text for t in teams" onbeforesave="editUser(selectedItem.Id,'team',$data)">
																<% selectedItem.team_name || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Grade</b></td>
														<td>
															<a href="#" editable-select="selectedItem.grade" e-ng-options="g.value as g.text for g in grades" onbeforesave="editUser(selectedItem.Id,'grade',$data)">
																<% selectedItem.grade || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Integration date</b></td>
														<td>
															<a href="#" editable-text="selectedItem.integration_date" onbeforesave="editUser(selectedItem.Id,'integration_date',$data)">
																<% selectedItem.integration_date || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Status</b></td>
														<td>
															<a href="#" editable-select="selectedItem.status" e-ng-options="s.value as s.text for s in status" onbeforesave="editUser(selectedItem.Id,'status',$data)">
																<% selectedItem.status || 'Empty' %>
															</a>
														</td>
													</tr>
													<tr>
														<td><b>Computer</b></td>
														<td>
															<a href="#" editable-select="selectedItem.computer" e-ng-options="c.value as c.text for c in computers" onbeforesave="editUser(selectedItem.Id,'computer',$data)">
																<% selectedItem.computer || 'Empty' %>
															</a>
														</td>
													</tr>
												</table>
											</div><!-- /.tab-pane -->
											<div class="tab-pane" id="tab_3-3">
												<table class="table">
													<tr>
														<td><b>Tools</b></td>
														<td>
															<a editable-select="user_tools" class="selectpicker" e-multiple e-ng-options="t.Id as t.name for t in tools" onbeforesave="editUser(selectedItem.Id,'tool',$data)">
																<% showTools() %>
															</a>
														</td>
													</tr>
												</table>
											</div><!-- /.tab-pane -->
											<div class="tab-pane" id="tab_4-4">
											</div>
										</div><!-- /.tab-content -->
									</div>
								</div>
								<div ng-class="(selectedItem.role=='N1'||selectedItem.role=='N2')?'col-lg-6':'col-lg-0'" ng-show="(selectedItem.role=='N1'||selectedItem.role=='N2')">
									<div class="row text-center">
										<div class="btn-group" role="group" aria-label="...">
											<button type="button" class="btn btn-default">Left</button>
											<button type="button" class="btn btn-default">Middle</button>
											<button type="button" class="btn btn-default">Right</button>
										</div>
									</div>
									<div class="row">
										<div id="chart-container" style="width: 100%; width: 93%; margin: 0 auto"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div><!-- End Modal Content -->
				</div>
			</div><!-- End Modal -->
		</div><!-- /.box-body -->
		<div class="box-body" id="add-user" style="display:none;">
			<div class="container-fluid">
				<form method="post" action="{{ route('adduser') }}" class="form-horizontal" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<legend>Personnal details</legend>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="Employee_id">Employe ID <span class="text-danger">*</span></label>
								<input required type="text" name="employe_id" placeholder="Employe id" class="form-control" >
							</div>
							<div class="form-group">
								<label for="lastName">Last Name <span class="text-danger">*</span></label>
								<input required type="text" name="lastname" placeholder="Last Name" class="form-control"  >
							</div>
							<div class="form-group">
								<label for="firstname">First Name <span class="text-danger">*</span></label>
								<input required type="text" name="firstname" placeholder="First Name" class="form-control" >
							</div>
							<div class="form-group">
								<label for="email">Email <span class="text-danger">*</span></label>
								<input required type="email" name="email" placeholder="Email" class="form-control" >
							</div>
							<div class="form-group">
								<label for="gender">Gender</label>
								<select name="gender" id="gender" class="form-control">
									<option value="Male" selected>Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="matricule">AgirhID/Matricule <span class="text-danger">*</span></label>
								<input required type="text" name="matricule" placeholder="AgirhID/Matricule" class="form-control" >
							</div>
							<div class="form-group">
								<label for="city">City <span class="text-danger">*</span></label>
								<input required type="city" name="city" placeholder="City" class="form-control" >
							</div>
							<div class="form-group">
								<label for="adress">Adress <span class="text-danger">*</span></label>
								<input required type="adress" name="adress" placeholder="adress" class="form-control" >
							</div>
							<div class="form-group">
								<label for="phone">Phone <span class="text-danger">*</span></label>
								<input required type="text" name="phone" placeholder="phone" class="form-control" >
							</div>
							<div class="form-group">
								<label for="picture">Picture</label>
								<input type="file" name="picture" class="form-control" >
							</div>
						</div>
					</div>
					<legend>Professional details</legend>
					<div class="container">
						<legend>Accounts</legend>
					</div>
					<div id="frm-content">
						<div class="frm-element">
							<div class="row">
								<div class="col-lg-1">
									<span class="span pull-right"># <span class="number">1</span></span>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="account">Account <span class="text-danger">*</span></label>
										<select required ng-options="account.name for account in accounts track by account.id" ng-model="account" name="account[]" id="account" class="form-control account">
											<option value="">Choose an account</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="id">ID <span class="text-danger">*</span></label>
										<input required type="text" id="id" name="account_id[]" class="form-control">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label for="sub-account">Sub-Account</label>
										<select ng-options="sub_account.name for sub_account in sub_accounts track by sub_account.id" ng-model="sub_account" name="sub_account[]" id="sub_account" class="form-control sub-account">
											<option value="">Choose a sub-account</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-lg-offset-10">
							<a class="btn btn-info" id="add"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="job">Job</label>
								<select name="job" id="job" class="form-control">
									<option value="Director" selected>Director</option>
									<option value="OPS Manager">OPS Manager</option>
									<option value="SDS">SDS</option>
									<option value="SME">SME</option>
									<option value="Agent">Agent</option>
								</select>
							</div>
							<div class="form-group">
								<label for="role">Role</label>
								<select name="role" id="role" class="form-control">
									<option value="Coach" selected>Coach</option>
									<option value="CSR">CSR</option>
									<option value="KA">KA</option>
									<option value="N1">N1</option>
									<option value="N2">N2</option>
									<option value="QA">QA</option>
									<option value="SDM">SDM</option>
									<option value="SDS">SDS</option>
									<option value="SME">SME</option>
									<option value="TL">TL</option>
									<option value="Trainer">Trainer</option>
									<option value="WFA">WFA</option>
								</select>
							</div>
							<div class="form-group">
								<label for="team">Team</label>
								<select name="team" id="team" class="form-control" ng-options="t.value as t.text for t in teams track by t.value" ng-model="selectedTeam">
								</select>
							</div>
							<div class="form-group">
								<label for="grade">Grade</label>
								<select name="grade" id="grade" class="form-control">
									<option value="A" selected>A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="integration_date">Integration date <span class="text-danger">*</span></label>
								<input required type="date" name="integration_date" placeholder="integration_date" class="form-control"/>
							</div>
							<div class="form-group">
								<label for="status">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="Active" selected>Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
							<div class="form-group">
								<label for="computer">Computer</label><br>
								<select name="computer" class="form-control">
									<option value="Desktop">Desktop</option>   
									<option value="Both">Both</option>
									<option value="Laptop">Laptop</option>
								</select>
							</div>
							<div class="form-group">
								<label for="language">Language <span class="text-danger">*</span></label><br>
								<select required name="language[]" multiple class="selectpicker" data-width="100%">   
									@foreach($languages as $language)
									<option value="{{ $language->Id }}">{{ $language->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<legend>IDs and Tools</legend>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="global_id">Global ID</label>
								<input type="text" name="global_id" class="form-control" placeholder="Global ID">
							</div>
							<div class="form-group">
								<label for="bcp">bcp</label><br>
								<select name="bcp" class="form-control">
									<option value="Yes" selected>Yes</option>   
									<option value="No">No</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="avaya_id">Avaya ID</label>
								<input type="text" name="avaya_id" class="form-control" placeholder="Avaya ID">
							</div>
							<div class="form-group">
								<label for="tools">Tools</label><br>
								<select name="tools[]" multiple class="selectpicker" data-width="100%">   
									@foreach($tools as $tool)
									<option value="{{ $tool->Id }}">{{ $tool->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-7 col-lg-offset-1">
							<span class="text-danger">Please assign required fields</span>
						</div>
						<div class="col-lg-2">
							<input type="reset" class="btn btn-default" value="Cancel">
							<input type="submit" class="btn btn-info" value="Add User">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div><!-- /.box -->
</div><!-- /.modal -->
@endsection
@section('script')
<script src="{{ asset('/js/angular.min.js') }}"></script>
<script src="{{ asset('/js/userCtrl.js') }}"></script>
<script src="{{ asset('/js/highcharts.js') }}"></script>
<script src="{{ asset('/js/highcharts-more.js') }}"></script>
<script src="{{ asset('/js/exporting.js') }}"></script>
<script src="{{ asset('/js/showRadar.js') }}"></script>
<script src="{{ asset('/js/xeditable.js') }}"></script>
<script src="{{ asset('/js/bootstrap-select.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$('.selectpicker').selectpicker();
		$('#btn-add').click(function() {
			$('#directory-user').toggle(1000)
			$('#add-user').toggle(1000);
			$('#btn-add').hide(1000);
			$('#title').text('New Employee');
		});
		$("input[value='Cancel']").click(function() {
			$('#directory-user').toggle(1000);
			$('#add-user').toggle(1000);
			$('#btn-add').show(1000);
			$('#title').text('Employee Directory');
		});
		var i=0;
		var r="<a class='btn btn-danger' id='remove'><i class='fa fa-minus'></i></a>";
		$(document.body).on('click', '#add', function() {
			var a = $('.number').last().text();
			i++;
			if(i==1){
				$('#add').before(r);
			}
			var elm = "<div id='r"+i+"' class='frm-element'>"+$("#frm-content").children('.frm-element').last().html()+"</div>";
			$('#frm-content').append(elm);
			$('.number').last().text(parseInt(a)+1);
		});
		$(document.body).on('click', '#remove', function() {
			$('#r'+i).remove();
			i--;
			if(i==0){
				$('#remove').remove();
			}
		});
	});
</script>
@endsection