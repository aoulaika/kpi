@extends('signup/layout')
@section('title', ' Manage Users')
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
</style>
@endsection
@section('content')
<div class="container-fluid" ng-app="app" ng-controller="ctrl">
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
										<select ng-options="sub_account.name for sub_account in sub_accounts track by sub_account.id" ng-model="sub_account" name="sub_account[]" id="sub_-account" class="form-control sub-account">
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