<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 21/03/2017
 * Time: 21:41
 */
?>
<section class="content-header">
	<h1>
		Tra cứu thông tin thẻ nạp Trạng Nguyên
		<small></small>
	</h1>
</section>
<div ng-app="mainApp" ng-controller="mainController">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<form class="form-horizontal">
						<div class="box-header">
							<h3 class="box-title">Thống kê:</h3>
						</div>
						<div class="box-body">
							<div class="form-group" ng-repeat="info in report">
								<div class="col-sm-6" ng-repeat="i in info.detail">
									<label ng-show="i.type" class="col-sm-4 control-label">Thẻ {{info.money}} đã sử dụng:</label>
									<label ng-show="!i.type" class="col-sm-4 control-label">Thẻ {{info.money}} chưa sử dụng:</label>
									<div class="col-sm-8">
										<input type="text" ng-value="i.n" class="form-control" disabled>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<form class="form-horizontal" accept-charset="UTF-8" class="form-horizontal" data-toggle="validator" ng-submit="search($event)">
					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title">Tìm kiếm thông tin:</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-2 control-label">Số serial:</label>
								<div class="col-sm-2">
									<select ng-model="search_type" class="form-control" >
										<option value="=">=</option>
										<option value="like">Có chứa</option>
										<option value="start">Bắt đầu</option>
										<option value="end">Kết thúc</option>
									</select>
								</div>
								<div class="col-sm-6">
									<input type="text" ng-model="key_search" class="form-control number" placeholder="Tìm kiếm theo số serial" maxlength="12">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Trạng thái:</label>
								<div class="col-sm-2">
									<select ng-model="is_used" class="form-control" >
										<option value="">Tất cả</option>
										<option value="true">Đã sử dụng</option>
										<option value="false">Chưa sử dụng</option>
									</select>
								</div>
							</div>
						</div>
						<div class="box-footer text-center">
							<div class="row text-center">
								{{error}}
							</div>
							<div class="row">
								<button class="btn btn-info" type="submit">Search</button>
							</div>
						</div>
						<div class="box-body" ng-show="is_search" id="search_content">
							<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover dataTable">
											<thead>
											<tr role="row">
												<th class="tnw-2">STT</th>
												<th class="tnw-5">Serial</th>
												<th class="tnw-3">Mệnh giá</th>
												<th class="tnw-3">Ngày học</th>
												<th class="tnw-3">Người dùng</th>
												<th class="tnw-5">Ngày dùng</th>
												<th class="tnw-3">Trạng thái dùng</th>
											</tr>
											</thead>
											<tbody>
											<tr role="row" ng-repeat="info in list track by $index">
												<th class="text-center">{{(page.currentPage -1) * page.pageSize + $index + 1}}</th>
												<th class="text-center">{{info.serial}}</th>
												<th class="text-center">{{info.money}}</th>
												<th class="text-center">{{info.day}}</th>
												<th class="text-center">{{info.user_used}}</th>
												<th class="text-center">{{S2Time(info.used_at)}}</th>
												<th class="text-center">{{info.is_used?'đã dùng':''}}</th>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row" ng-show="list.length>0">
									<div class="col-sm-5">
										<div class="dataTables_info text-info">Từ {{(page.currentPage -1) * page.pageSize + 1}} đến {{(page.currentPage - 1) * page.pageSize + list.length}} của {{page.total}} bản ghi</div>
									</div>
									<div class="col-sm-7">
										<div class="dataTables_paginate">
											<paging
												page="page.currentPage"
												page-size="page.pageSize"
												total="page.total"
												disabled="page.disabled"
												hide-if-empty="true"
												show-prev-next="true"
												show-first-last="true"
												text-first-class="fa fa-fast-backward"
												text-prev-class="fa fa-step-backward"
												text-next-class="fa fa-step-forward"
												text-last-class="fa fa-fast-forward"
												paging-action="GoPage(page, pageSize, total)">
											</paging>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
<script>
	$(function(){
		$('.number').ForceNumericOnly();
	});
	var mainApp = angular.module("mainApp", ['bw.paging']);
	mainApp.controller("mainController", function($scope, $http) {
		$scope.is_search = false;
		$scope.key_search = '';
		$scope.list = [];
		$scope.report = [];
		$scope.search_type = '=';
		$scope.is_used = '';
		$scope.page_index = 0;

		$http({
			method: "post",
			url: '/ajax/card_cmd.php',
			//headers: {'Content-Type': 'application/json'},
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			data: $.param({action: 'report'})
		})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						var content = data.content.result;
						var cards = {};
						var info_list = [];
						for(var i=0, info; info = content[i]; i++){
							var _id = info._id;
							if(!cards[_id.money]) cards[_id.money] = {};
							cards[_id.money][_id.is_used] = info.count;
						}
						for(var money in cards){
							var info = cards[money];
							info_list.push({
								money: money,
								detail: [
									{n: info['false']? info['false']: 0, type: false},
									{n: info['true']? info['true']: 0, type: true}
								]
							});
						}
						$scope.report = info_list;
						$scope.error = '';
					}
					else{
						$scope.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});

		function LoadData() {
			$http({
				method: "post",
				url: '/ajax/card_cmd.php',
				//headers: {'Content-Type': 'application/json'},
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'search', page_index: $scope.page_index, search_type: $scope.search_type, key_search: $scope.key_search, is_used: $scope.is_used})
			})
				.then(function(res) {
					var data = res.data;
					if(data){
						if(data.error==0){
							$scope.list = data.content;
							$scope.page={
								disabled: false,
								currentPage: data.page_index + 1,
								pageSize: data.page_size,
								total: data.count
							};
							$scope.error = '';
							GoScrollTo('#search_content');
						}
						else{
							$scope.list = [];
							$scope.error = data.message;
						}
					}
				}, function(res) {
					$scope.error = 'Status error code: ' + res.status;
				});
		}

		$scope.S2Time = function(s){
			if(s && s!='') return util.Ms2DateTime(parseInt(s)*1000);
			return '';
		};

		$scope.GoPage = function(page_index){
			$scope.page_index = page_index - 1;
			LoadData(true);
		};

		$scope.search = function(){
			$scope.is_search = true;
			$scope.page_index = 0;
			LoadData();
		};
	});
</script>
