<?php
require_once './model/ProvinceData.php';
$ProvinceData = ProvinceData::getInstance();
$list_data = $ProvinceData->GetList();
$list_province=$list_data['list'];
?>
<section class="content-header">
	<h1>
		Danh sách mã thi cấp quận/huyện
		<small></small>
	</h1>
</section>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content">
		<form role="form" class="form-horizontal">
			<div class="row">
				<div class="col-sm-5">
					<div class="form-group">
						<label for="ddl_province" class="col-sm-4 control-label">Tỉnh/thành phố:</label>
						<div class="col-sm-8">
							<select class="form-control" ng-model="province_id" style="font-weight: bold;">
								<?php
								if($list_province!=null && count($list_province)>0){
									foreach ($list_province as $province){
										echo '<option value="'.$province['_id'].'">'.$province['name'].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap district-data">
							<div class="row">
								<div class="col-sm-12">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
<!--											<th class="col-xs-1">STT</th>-->
											<th class="col-xs-2">ID</th>
											<th class="col-xs-2">Huyện</th>
											<th class="col-xs-6">Code</th>
											<th class="col-xs-2">Action</th>
										</tr>
										</thead>
										<tbody>
										<tr ng-repeat="district in districts track by $index">
<!--											<td class="text-center">{{$index+1}}</td>-->
											<td class="text-center">{{district._id}}</td>
											<td>{{district.name}}</td>
											<td>{{showCode(district.codes)}}</td>
											<td class="text-center">
												<a href="javascript:void(0);" ng-click="ViewCode(district)">Chi tiết</a>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!--<div class="box-footer">
								<button type="button" tn-action="add" class="btn btn-primary btn-add">Thêm</button>
								<div class="text-light-blue">(Bạn có quyền xem, thêm, sửa)</div>
							</div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div id="modal-detail" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form" role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Thông tin mã thi {{district_info.name}}</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover dataTable">
											<thead class="thead">
											<tr role="row">
												<th class="tnw-1">STT</th>
												<th class="tnw-3">Code</th>
												<th class="tnw-2">Lớp</th>
												<th class="tnw-4">SD từ</th>
												<th class="tnw-4">SD đến</th>
												<th class="tnw-2">Active</th>
												<th class="tnw-3">Ngày tạo</th>
												<th class="tnw-3">Người tạo</th>
												<th class="tnw-2">Action</th>
											</tr>
											</thead>
											<tbody>
											<tr role="row" ng-repeat="code_info in district_info.codes track by $index">
												<td class="text-center"><?php echo '{{$index+1}}'; ?></td>
												<td class="text-center">{{code_info._id}}</td>
												<td class="text-center">{{code_info.class_id || '&nbsp;'}}</td>
												<td class="text-center">{{SToDate(code_info.begin_use)}}</td>
												<td class="text-center">{{SToDate(code_info.end_use)}}</td>
												<td class="text-center">{{code_info.active}}</td>
												<td class="text-center">{{SToDate(code_info.created_at)}}</td>
												<td class="text-center">{{code_info.user_create}}</td>
												<td class="text-center">
													<a href="javascript:void(0)" ng-click="EditCode(code_info)">Edit</a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-sm-8 message">
								<label class="modal-message"></label>
							</div>
							<div class="col-sm-4 button">
								<button type="submit" ng-click="AddCode(district_info._id)" class="btn btn-primary btn-save">Add</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal-code" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="form" role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Thông tin mã thi</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label">Mã thi:</label>
								<div class="col-sm-9">
									<input type="text" ng-model="code_info._id" class="form-control" placeholder="(Hệ thống tự sinh)" disabled/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Lớp:</label>
								<div class="col-sm-9">
									<select class="form-control" ng-model="code_info.class_id" style="font-weight: bold;">
										<option value="0">Tất cả lớp</option>
										<option value="1">Lớp 1</option>
										<option value="2">Lớp 2</option>
										<option value="3">Lớp 3</option>
										<option value="4">Lớp 4</option>
										<option value="5">Lớp 5</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Sử dụng từ:</label>
								<div class="col-sm-9">
									<input type="text" ng-model="code_info.time_from" class="form-control date-range" placeholder="Thời gian bắt đầu">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Sử dụng đến:</label>
								<div class="col-sm-9">
									<input type="text" ng-model="code_info.time_to" class="form-control date-range" placeholder="Thời gian kết thúc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Active:</label>
								<div class="col-sm-9">
									<input type="checkbox" ng-model="code_info.active"/>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-sm-8 message">
								<label class="modal-message"></label>
							</div>
							<div class="col-sm-4 button">
								<button type="submit" ng-click="Save(district_info._id)" class="btn btn-primary btn-save">Save</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		//$('.number').ForceNumericOnly();

		$('.input-daterange input').each(function() {
			$(this).datepicker({
				format: "yyyy-mm-dd",
				autoclose: true,
				todayHighlight: true
			});
		});

		$('.date-range').daterangepicker({
			"singleDatePicker": true,
			"showDropdowns": true,
			"timePicker": true,
			"timePicker24Hour": true,
			"alwaysShowCalendars": true,
			"autoApply": true,
			"locale": {
				"format": 'YYYY-MM-DD HH:mm:ss',
				"separator": " -> ",
				"applyLabel": "Ok",
				"cancelLabel": "Hủy",
				"fromLabel": "Từ",
				"toLabel": "Đến",
				"customRangeLabel": "Tùy chọn",
				"daysOfWeek": ["CN","T2","T3","T4","T5","T6","T7"],
				"monthNames": ["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6","Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],
				"firstDay": 1
			},
			//"startDate": "11/10/2015",
			//"endDate": "11/16/2015",
			"opens": "center"
		}, function(start, end, label) {
			//console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
		});

		window.modal_detail = $('#modal-detail').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.modal_code = $('#modal-code').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});
	});

	var MsToDate = function($ms){
		if($ms && typeof $ms === 'number') return util.date2String(new Date($ms));
		return '';
	};

	var app = angular.module("adminApp", []);
	app.controller("mainController", function($scope, $http) {
		$scope._id = 0;
		$scope.name = '';
		$scope.date_from = '';
		$scope.date_to = '';
		$scope.districts = [];

		//$scope.MsToDate = function($ms){
		//    return MsToDate($ms);
		//};

		$scope.SToDate = function($ms){
			return MsToDate($ms*1000);
		};

		var LoadListDistrict = function(province_id){
			$http({
				method: "post",
				url: '/ajax/district_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action:'list', province_id: province_id})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						var list_district = data.content;
						LoadDataCode($scope.province_id, null, function(data_code){
							if(data_code.error==0){
								var content_code = data_code.content;
								if(content_code && content_code.length>0){
									for(var i=0, district_info; district_info = list_district[i]; i++){
										for(var j = 0, code_info; code_info = content_code[j]; j++){
											if(district_info._id == code_info.district_id){
												var codes = district_info.codes;
												if(!codes) codes = [];
												codes.push(code_info);
												district_info.codes = codes;
											}
										}
									}
								}
								$scope.districts = list_district;
							}
							else{
								$scope.error = data.message;
							}
						});
					}
					$scope.error = data.message;
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		$scope.showCode = function(codes){
			if(codes && codes.length>0){
				var s = '';
				for(var i=0, code_info; code_info = codes[i]; i++){
					if(s!='') s+=', ';
					s+=code_info._id;
				}
				return s;
			}
			return '';
		};

		$scope.EditCode = function(code_info){
			if(code_info){
				code_info.time_from = code_info.begin_use>0? MsToDate(code_info.begin_use * 1000): '';
				code_info.time_to = code_info.end_use>0? MsToDate(code_info.end_use * 1000): '';
			}
			code_info.class_id = code_info.class_id? String(code_info.class_id): '0';
			$scope.code_info = code_info;
			modal_code.modal('show');
		};

		var LoadDataCode = function(province_id, district_id, callback){
			$http({
				method: "post",
				url: '/ajax/example_code_cmd.php',
				//headers: {'Content-Type': 'application/json'},
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'list', type: 'district', province_id: province_id, district_id: district_id})
			})
				.then(function(res) {
					var data = res.data;
					if(data){
						callback(data);
					}
				}, function(res) {
					$scope.error = 'Status error code: ' + res.status;
				});
		};

		$scope.ViewCode = function(district_info){
			$scope.district_info = district_info;
			modal_detail.modal('show');
		};

		$scope.$watch('province_id', function (id) {
			if(id && id !== ''){
				LoadListDistrict(id);
			}
			else{
				$scope.districts = [];
			}
		});

		$scope.AddCode = function(district_id){
			$scope.code_info = {
				_id: '',
				class_id: '0',
				district_id: district_id,
				time_from: '',
				time_to: '',
				active: true
			};
			modal_code.modal('show');
		};

		$scope.Save = function(){
			var submit_data = {
				'action': 'save',
				_id: $scope.code_info._id,
				type: 'district',
				province_id: $scope.province_id,
				district_id: $scope.district_info._id,
				class_id: $scope.code_info.class_id,
				active: $scope.code_info.active
			};

			if($scope.code_info.class_id === '0') {
				Alert('Phải nhập lớp');
				return false;
			}

			if($scope.code_info.time_from && $scope.code_info.time_to){
				try{
					submit_data.begin_use = $scope.code_info.time_from!=''? $scope.code_info.time_from: null;
					submit_data.end_use = $scope.code_info.time_to!=''? $scope.code_info.time_to: null;
				} catch(e) {
					Alert(e);
					submit_data.begin_use = 0;
					submit_data.end_use = 0;
					return false;
				}
			} else {
				Alert('Phải nhập đầy đủ thời gian bắt đầu và kết thúc');
				return false;
			}

			$http({
				method: "post",
				url: '/ajax/example_code_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param(submit_data)
			})
				.then(function(res) {
					var data = res.data;
					if(data){
						if(data.error==0){
							$scope.code_info._id = data.code;
							LoadDataCode($scope.province_id, $scope.district_info._id, function(data_code){
								if(data_code.error==0){
									var content_code = data_code.content;
									$scope.district_info.codes = content_code;
								}
								else{
									$scope.error = data.message;
								}
							});
							setTimeout(function(){
								modal_code.modal('hide');
							},1000);
						}
						else{
							Alert(data.message);
						}
					}
				}, function(res) {
					Alert('ERROR: ' + res.status);
				});
		};
	});
</script>