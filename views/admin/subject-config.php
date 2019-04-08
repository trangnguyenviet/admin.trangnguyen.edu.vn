<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 16/07/2017
 * Time: 18:53
 */
?>
<style>
	.item-round{
		margin-bottom: 10px;
	}
	.item-round:last-child{
		margin-bottom: 0;
	}
	.item-round .input-group-addon{
		min-width: 90px;
	}
</style>

<section class="content-header">
	<h1>Quản lý môn thi<small></small></h1>
</section>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content">
		<form class="form-horizontal" ng-submit="search()">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Tìm kiếm:</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">Môn học:</label>
						<div class="col-sm-10">
							<select ng-model="subject_id" class="form-control">
								<option value="4">Tiếng Việt</option>
								<option value="1">Toán</option>
								<option value="2">English</option>
								<option value="3">Luyện Tiếng Việt</option>
								<option value="5">Khoa học - tự nhiên</option>
								<option value="6">Sử - địa -xã hội</option>
								<option value="7">IQ - Toán tiếng anh</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Lớp:</label>
						<div class="col-sm-10">
							<select ng-model="class_id" class="form-control">
								<option value="0">Mẫu giáo</option>
								<option value="1">Lớp 1</option>
								<option value="2">Lớp 2</option>
								<option value="3">Lớp 3</option>
								<option value="4">Lớp 4</option>
								<option value="5">Lớp 5</option>
							</select>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="row text-center">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-lg btn-success"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<form id="detail" class="form-horizontal" ng-submit="submit()" ng-show="key!=''">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Thông tin vòng thi:</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tổng số vòng:</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" value="19" placeholder="total round" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Mở vòng:</label>
						<div class="col-sm-10">
							<input type="number" ng-model="info.current_round" min="0" max="19" class="form-control" placeholder="current round">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Thu phí từ vòng:</label>
						<div class="col-sm-10">
							<input type="number" ng-model="info.payment_round" min="0" max="20" class="form-control" placeholder="payment round">
						</div>
					</div>
					<!--table-->
					<div class="form-group">
						<label for="tb_current_round" class="col-sm-2 control-label">Tên vòng thi:</label>
						<div class="col-sm-10">
							<div class="item-round input-group" ng-repeat="(key, val) in info.rounds track by $index">
								<span class="input-group-addon"><label>Vòng {{key}}:</label></span>
								<input ng-model="info.rounds[key].name" type="text" class="form-control" placeholder="Tên vòng thi {{i}}"/>
								<span class="input-group-addon"><label><input ng-model="info.rounds[key].client" type="checkbox"> Client</label></span>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="row text-center">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-lg btn-success"><i class="fa fa-floppy-o"></i> Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	var app = angular.module("adminApp",[]);
	app.controller("mainController", function($scope, $http) {
		$scope.subject_id = '4';
		$scope.class_id = '0';
		$scope.info = {};
		$scope.info.current_round = 0;
		$scope.info.payment_round = 0;
		$scope.info.rounds=[];
		$scope.key = '';

		var defaultInfo = function(){
			var rounds = {};
			for(var i=1; i<=19; i++){
				rounds[i]={
					name: '',
					client: true
				};
			}
			$scope.info = {
				current_round: 0,
				payment_round: 0,
				rounds
			};
		};

		$scope.search = function(){
			$scope.key = 'subject_' + $scope.subject_id + '_' + $scope.class_id;
			$http({
				method: "post",
				url: '/ajax/variable_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'info',key: $scope.key})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						if(data.info){
							$scope.key = data.info._id;
							$scope.info = data.info.value;
						}
						else{
							defaultInfo();
						}
						GoScrollTo('#detail');
					}
					else{
						Alert(data.message);
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		$scope.submit = function(){
			$http({
				method: "post",
				url: '/ajax/variable_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'save',id: $scope.key, value: JSON.stringify($scope.info), type: 'json'})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						ShowMessError('Lưu dữ liệu thành công');
					}
					else{
						Alert(data.message);
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		//$scope.$watch('class_id', function (id) {
		//	console.log(id);
		//});
		//
		//$scope.$watch('subject_id', function (id) {
		//	console.log(id);
		//});
	});
</script>