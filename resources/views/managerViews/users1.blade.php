@extends('managerViews/layout')
@section('title', ' Manage Users')
@section('content')
<div ng-app="app" ng-controller="ctrl">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title" style="padding-top:5px" id="title">
				Employee Directory
			</h3>
			<a class="btn btn-social btn-primary pull-right" id="btn-add">
				<i class="fa fa-plus"></i> Add Employee
			</a>
		</div><!-- /.box-header -->
		<div class="box-body" id="directory-user">
			<div class="container-fluid">
				<header class="clearfix">
					<div class="form-inline pull-right">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-search"></i></span>
								<input type="text" class="form-control"  ng-model="searchText" type="search" placeholder="Type name, email, or department">
								<!-- <button ng-click="searchText = ''" ng-show="searchText != ''" type="button" class="close" aria-hidden="true">&times;</button> -->
								<span class="input-group-addon" ng-click="searchText = ''" ng-show="searchText != ''"><i class="fa fa-eraser"></i></span>
							</div>
						</div>
					</div>
				</header>
				<div class="row">
					<div ng-repeat="item in items | filter:searchText" class="col-lg-4 col-md-6 box-user">
						<div class="col-xs-5">
							<a class="image" ng-click="editItem(item)" data-toggle="modal" data-target="#myModal">
								<span class="rollover"></span>
								<img class="imgborder" alt="" src="{{ asset('img/default-user.png') }}">
							</a>
						</div>
						<div class="col-xs-7">
							<blockquote>
								<p><% item.firstname+' '+item.lastname %></p> <small><cite title="Source Title"><% item.role %></cite></small>
							</blockquote>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><strong><% selectedItem.firstname+' '+selectedItem.lastname %></strong></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6">
									<!-- Image -->
									<div class="media">
										<a class="pull-left" href="#">
											<img class="media-object dp img-circle" src="{{ asset('/img/user2-160x160.jpg') }}" style="width: 100px;height:100px;">
										</a>
										<div class="media-body">
											<h4 class="media-heading"><% selectedItem.firstname+' '+selectedItem.lastname %></h4>
											<h5><% selectedItem.role %> at <small>Eli lilly</small></h5>
											<hr style="margin:8px auto">
										</div>
									</div>
									<!-- End Image -->
									<div style="border-left:1px solid #E5E5E5;margin-left:57px;">
										<div style="margin-left:20px">
											<table class="table">
												<tr>
													<td><b>Employe Id</b></td>
													<td><% selectedItem.employee_id %></td>
												</tr>
												<tr>
													<td><b>Matricule</b></td>
													<td><% selectedItem.matricule %></td>
												</tr>
												<tr>
													<td><b>First Name</b></td>
													<td><% selectedItem.firstname %></td>
												</tr>
												<tr>
													<td><b>Last Name</b></td>
													<td><% selectedItem.lastname %></td>
												</tr>
												<tr>
													<td><b>E-mail</b></td>
													<td><% selectedItem.email %></td>
												</tr>
												<tr>
													<td><b>Phone</b></td>
													<td><% selectedItem.phone %></td>
												</tr>
												<tr>
													<td><b>Languages</b></td>
													<td><% selectedItem.L1+' '+selectedItem.L2+' '+selectedItem.L3+' '+selectedItem.L4 %></td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div id="chart-container" style="min-width: 400px; max-width: 600px; height: 400px; margin: 0 auto"></div>
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
				<form method="post" action="Add_User.php" class="form-horizontal">
					<legend>Personnal details</legend>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="Employee_id">Employe id</label>
								<input required type="text"  name="employee_id" placeholder="Employe id" class="form-control" >
							</div>
							<div class="form-group">
								<label for="lastName">Last Name</label>
								<input required type="text"  name="lastname" placeholder="Last Name" class="form-control"  >
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input required type="email"  name="email" placeholder="Email" class="form-control" >
							</div>
							<div class="form-group">
								<label for="adresse">Adresse</label>
								<input type="adresse"  name="adresse" placeholder="Adresse" class="form-control" >
							</div>
						</div>
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="matricule">Matricule</label>
								<input required type="text"  name="matricule" placeholder="Matricule" class="form-control" >
							</div>
							<div class="form-group">
								<label for="firstname">First Name</label>
								<input required type="text"  name="firstname" placeholder="First Name" class="form-control" >
							</div>
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="phone"  name="phone" placeholder="phone" class="form-control" >
							</div>
							<div class="form-group">
								<label for="city">City</label>
								<input type="city"  name="city" placeholder="City" class="form-control" >
							</div> 
						</div>
					</div>
					<legend>Professional details</legend>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="seniority_level">Seniority_level</label>
								<select name="seniority_level" id="seniority_level" class="form-control">
									<option value="1">1</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select>
							</div>
							<div class="form-group">
								<label for="role">Role</label>
								<select name="role" id="role" class="form-control">
									<option value="Admin">Admin</option>
									<option value="Coach">Coach</option>
									<option value="Quality analyst">Quality analyst</option>
									<option value="SME">SME</option>
									<option value="Trainer">Trainer</option>
									<option value="Team leader">Team leader</option>
									<option value="Other">Other</option>
								</select>
							</div>
							<div class="form-group">
								<label for="integration_date">Integration date</label>
								<input type="date" name="integration_date" placeholder="integration_date" class="form-control"/>
							</div>
						</div>
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="job">Job</label>
								<select name="job" id="job" class="form-control">
									<option value="Director">Director</option>
									<option value="OPS Manager">OPS Manager</option>
									<option value="SDS">SDS</option>
									<option value="SME">SME</option>
									<option value="Agent">Agent</option>
								</select>
							</div>
							<div class="form-group">
								<label for="status">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
							<div class="form-group">
								<label for="language">Language</label><br>
								<select name="language" multiple="multiple" class="select2 form-control">   
									<option value="FRA">Français</option>
									<option value="GER">Germany</option>
									<option value="ARA">Arabe</option>
									<option value="ANG">Anglais</option>
									<option value="SPA">Spain</option>
									<option value="ITA">Italy</option>
									<option value="POR">Portogal</option>
								</select>
							</div>
						</div>
					</div>
					<legend>IDs and Tools</legend>
					<div class="row">
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="lilly_id">Lilly Code</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="lilly_id">
									</span>
									<input type="text" name="lilly_id" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="global_id">Global Id</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="global_id">
									</span>
									<input type="text" name="global_id" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="avaya_id">Avaya Id</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="avaya_id">
									</span>
									<input type="text" name="avaya_id" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="computer">Computer</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="computer">
									</span>
									<input type="text" name="computer" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="seniority">Seniority</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="seniority">
									</span>
									<input type="text" name="seniority" class="form-control" disabled>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-lg-offset-1">
							<div class="form-group">
								<label for="item_type">Item Type</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="item_type">
									</span>
									<input type="text" name="item_type" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="path">Path</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="path">
									</span>
									<input type="text" name="path" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="account">Account</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="account">
									</span>
									<input type="text" name="account" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="bcp">bcp</label>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" class="input_control" value="bcp">
									</span>
									<input type="text" name="bcp" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="tools">Tools</label><br>
								<select name="tools" multiple="multiple" class="select2 form-control">   
									<option value="global_id_request">global_id_request</option>
									<option value="nt_password">nt_password</option>
									<option value="lilly_system_id">lilly_system_id</option>
									<option value="lilly_onbaording_request">lilly_onbaording_request</option>
									<option value="acces_lillynet_search">acces_lillynet_search</option>
									<option value="computer">computer</option>
									<option value="citrix">citrix</option>
									<option value="vmware">vmware</option>
									<option value="safeboot">safeboot</option>
									<option value="boxstone">boxstone</option>
									<option value="ekms">ekms</option>
									<option value="lync">lync</option>
									<option value="ad">ad</option>
									<option value="sn">sn</option>
									<option value="ldap">ldap</option>
									<option value="mi">mi</option>            
									<option value="aicid">aicid</option>
									<option value="aic_installed">aic_installed</option>  
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-lg-offset-8">
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
<script src="{{ asset('/js/controller.js') }}"></script>
<script src="{{ asset('/js/highcharts.js') }}"></script>
<script src="{{ asset('/js/highcharts-more.js') }}"></script>
<script src="{{ asset('/js/exporting.js') }}"></script>
<script src="{{ asset('/js/showRadar.js') }}"></script>
<script src="{{ asset('/js/showRadar.js') }}"></script>
<script src="{{ asset('/plugins/select2/select2.full.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$('.select2').select2();
		$('#btn-add').click(function() {
			$('#directory-user').toggle();
			$('#add-user').toggle();
			$('#btn-add').hide();
			$('#title').text('New Employee');
		});
		$("input[value='Cancel']").click(function() {
			$('#directory-user').toggle();
			$('#add-user').toggle();
			$('#btn-add').show();
			$('#title').text('Employee Directory');
		});
		$('.input_control').attr('checked', false);
		$('.input_control').click(function(){
			if($('input[name='+ $(this).attr('value')+']').prop('disabled') == false){
				$('input[name='+ $(this).attr('value')+']').prop('disabled', true);
			}else{
				$('input[name='+ $(this).attr('value')+']').prop('disabled', false);
			}
		});
	});
</script>
@endsection