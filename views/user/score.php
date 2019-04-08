<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 17/04/2017
 * Time: 20:44
 */
?>
<script>
	var app = angular.module("adminApp", []);
	app.controller("mainController", function($scope, $http) {
		$scope.list = "";
		$scope.round = '16';
		$scope.executing = false;

		$scope.format = function(){
			$scope.list_id = $scope.list_id.replace(/\n/g,',').replace(/ /g,'').replace(/[,]{2,}/g,',');
		};

		$scope.sToTime = function(s){
			return util.date2String(new Date(s*1000));
		};

		$scope.submit = function(){
			$scope.executing = true;
			var data_post = {
				action: 'list-user',
				list_id: $scope.list_id,
				round: $scope.round
			};
			$http({
				method: "post",
				url: '/ajax/scores_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param(data_post)
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						$scope.list = data.content;
						$scope.error='';
					}
					else{
						$scope.error = data.message;
					}
				}
				$scope.executing = false;
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
				$scope.executing = false;
			});
		};
	});
</script>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content-header">
		<h1>
			Tra cứu điểm thi
			<small></small>
		</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<!--<div class="box-header">
						<h3 class="box-title">Danidh sách :</h3>
					</div>-->
					<form class="form-horizontal" ng-submit="submit()">
						<div class="box-body">
							<div class="form-group">
								<label for="rb_id" class="col-sm-2 control-label">Danh sách ID:</label>
								<div class="col-sm-8">
									<textarea ng-blur="format()" ng-model="list_id" rows="10" class="form-control list-number" placeholder="danh sách ID cách nhau bởi dấu phẩy ','" required></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Vòng:</label>
								<div class="col-sm-8">
									<select class="form-control" ng-model="round" required>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
									</select>
								</div>
							</div>
							<div class="text-center">{{error}}</div>
						</div>
						<div class="box-footer text-center">
							<button ng-disabled="executing" type="submit" class="btn btn-primary btn-search">Tra cứu</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Kết quả tìm kiếm:</h3>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
							<div class="row">
								<div class="col-sm-12">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
											<th class="tnw-3">STT</th>
											<th class="tnw-4">ID</th>
											<th class="tnw-4">Điểm</th>
											<th class="tnw-3">Thời gian</th>
											<th class="tnw-3">Lượt</th>
											<th class="tnw-3">Code</th>
											<th class="tnw-4">Ngày thi</th>
										</tr>
										</thead>
										<tbody>
										<tr ng-repeat="info in list track by $index">
											<td class="text-center">{{$index+1}}</td>
											<td class="text-center">{{info.user_id}}</td>
											<td class="text-center">{{info.score}}</td>
											<td class="text-center">{{info.time}}</td>
											<td class="text-center">{{info.luot}}</td>
											<td class="text-center">{{info.code}}</td>
											<td class="text-center">{{sToTime(info.created_at)}}</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>