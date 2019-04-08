<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 11/02/2017
 * Time: 21:30
 */
?>
<style>
	.select-action option {
		height: 20px;
		line-height: 20px;
		display: block;
	}
	.select-action option[value="example_school"] {
		color: #ff008f;
	}
	.select-action option[value="example_district"] {
		color: #ff8a00;
	}
	.select-action option[value="example_province"] {
		color: #ff5600;
	}
	.select-action option[value="example_national"] {
		color: #0055ff;
	}
	.select-action option[value="set_award"] {
		color: #00cfff;
	}
	.select-action option[value="set_active"] {
		color: #ff8809;
	}
</style>
<script>
	$(function(){
		$('.list-number').ForceListNumericOnly();

		window.modal_add_vip_result = $('#modal_add_vip_result').modal({
			show: false,
			keyboard: false,
			//backdrop: 'static'
		});

		window.modal_update_result = $('#modal_update_result').modal({
			show: false,
			keyboard: false,
			//backdrop: 'static'
		});
	});

	var app = angular.module("adminApp", []);
	app.controller("mainController", function($scope, $http) {
		$scope.action = "";
		$scope.list_id = "";
		$scope.award = "1";
		$scope.executing = false;
		$scope.pass_round = '11';

		$scope.format = function(){
			$scope.list_id = $scope.list_id.replace(/[^0-9,]/g, ',').replace(/\,{2,}/g, ',').replace(/^,(.+)$/, '$1').replace(/(.+),$/, '$1');
		};

		$scope.clearList = function(){
			$scope.list_id = '';
		};

		$scope.submit = function() {
			$scope.executing = true;
			var data_post = {
				action: $scope.action,
				list_id: $scope.list_id,
				money: util.parseInt($scope.vip_day),
				award: util.parseInt($scope.award),
				note: $scope.note,
				password: $scope.password,
				pass_round: $scope.pass_round
			};

			var listId = $scope.list_id.trim().split(/\,/g);

			var checkDuplicates = function() {
				var listDistinct = [];
				var listDuplicates = [];
				listId.forEach(id => {
					if(!listDistinct.includes(id)) {
						listDistinct.push(id);
					} else {
						listDuplicates.push(id);
					}
				});
				if(listDuplicates.length > 0) {
					$scope.error = 'ID trùng: ' + listDuplicates.join(', ');
					return true;
				}
				return false;
			};

			if(!checkDuplicates()) {
				$http({
					method: "post",
					url: '/ajax/user_action_cmd.php',
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					data: $.param(data_post)
				})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error === 0) {
								if(data_post.action === 'add_vip') {
									var results = data.results;
									var list_ok = '';
									var list_err = '';
									for(var i=0, result; result=results[i]; i++){
										if(result.ok && result.n > 0){
											if(list_ok !== '') list_ok+=', ';
											list_ok += result.id;
										} else {
											if(list_err !== '') list_err+=', ';
											list_err += result.id;
										}
									}
									$scope.vip_result = {
										ok: list_ok,
										err: list_err
									};
									modal_add_vip_result.modal('show');
								} else {
									$scope.update_result = data.results;
									modal_update_result.modal('show');
								}
								$scope.error='';
							} else {
								// ID không có trong hệ thống
								if(data.error === 1404) {
									var listIdExists = data.results;
									var list = [];
									listId.forEach(id => {
										if(id && !listIdExists.includes(id)) {
											list.push(id);
										}
									});
									$scope.error = data.message + ': ' + list.join(', ');
								} else {
									$scope.error = data.message;
								}
							}
						}
						$scope.executing = false;
					}, function(res) {
						$scope.error = 'Status error code: ' + res.status;
						$scope.executing = false;
					});
			}
		};
	});
</script>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content-header">
		<h1>
			Thao tác danh sách user
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
								<label class="col-sm-2 control-label">Hành động:</label>
								<div class="col-sm-8">
									<select class="form-control select-action" ng-model="action" required>
										<option value="">Chọn hành động</option>
										<option value="add_vip">Cộng ngày luyện tập</option>
										<option value="example_school">+ Duyệt thi cấp trường</option>
										<option value="un_example_school">- Hủy thi cấp trường</option>
										<option value="example_district">+ Duyệt thi cấp huyện</option>
										<option value="un_example_district">- Hủy thi cấp huyện</option>
										<option value="example_province">+ Duyệt thi cấp tỉnh</option>
										<option value="un_example_province">- Hủy thi cấp tỉnh</option>
										<option value="example_national">+ Duyệt thi cấp toàn quốc</option>
										<option value="un_example_national">- Hủy thi cấp toàn quốc</option>
										<option value="set_award">+ Set giải thưởng</option>
										<option value="unset_award">- Hủy giải</option>
										<option value="set_active">+ Set kích hoạt</option>
										<option value="set_inactive">- Hủy kích hoạt</option>
<!--										<option value="set_password">Đổi mật khẩu</option>-->
									</select>
								</div>
							</div>
							<div class="form-group" ng-show="action=='add_vip'">
								<label class="col-sm-2 control-label">Cộng số ngày:</label>
								<div class="col-sm-8">
									<!--<input type="number" ng-required="action=='add_vip'" ng-model="vip_day" class="form-control" placeholder="số ngày cộng thêm là số dương, trừ đi là số âm"/>-->
									<select class="form-control" ng-model="vip_day" ng-required="action=='add_vip'">
										<option value="">Chọn ngày học</option>
										<?php
											foreach($GLOBALS['vip_day'] as $index => $item) {
												echo '<option value="' . $index . '">' . $index . 'đ &#8680; ' . $item . ' ngày học </option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group" ng-show="action=='set_award'">
								<label class="col-sm-2 control-label">Giải:</label>
								<div class="col-sm-8">
									<!--<input type="number" ng-required="action=='set_award'" ng-model="award" class="form-control" placeholder="Giải thưởng sẽ bị ghi đè"/>-->
									<select class="form-control" ng-model="award" ng-required="action=='set_award'">
										<option value="1">Nhất Đình</option>
										<option value="2">Nhì Đình</option>
										<option value="3">Ba Đình</option>
										<option value="4">KK Đình</option>
										<option value="5">Nhất Hội</option>
										<option value="6">Nhì Hội</option>
										<option value="7">Ba Hội</option>
										<option value="8">KK Hội</option>
										<option value="9">Nhất Hương</option>
										<option value="10">Nhì Hương</option>
										<option value="11">Ba Hương</option>
										<option value="12">KK Hương</option>
										<option value="13">Hoàn thành xuất sắc vòng thi số 19</option>
									</select>
								</div>
							</div>
							<div class="form-group" ng-show="action=='set_password'">
								<label class="col-sm-2 control-label">Mật khẩu mới:</label>
								<div class="col-sm-8">
									<input type="text" ng-required="action=='set_password'" ng-model="password" class="form-control" placeholder="Mật khẩu mới từ 6 đến 30 ký tự"/>
								</div>
							</div>
							<div class="form-group" ng-show="['example_school', 'example_district', 'example_province'].indexOf(action) >= 0">
								<label class="col-sm-2 control-label">Bắt buộc HS phải qua vòng:</label>
								<div class="col-sm-8">
									<select class="form-control" ng-model="pass_round" ng-required="['example_school', 'example_district', 'example_province'].indexOf(action) >= 0">
										<option value="11">Vòng 11</option>
										<option value="12">Vòng 12</option>
										<option value="13">Vòng 13</option>
										<option value="14">Vòng 14</option>
										<option value="15">Vòng 15</option>
										<option value="16">Vòng 16</option>
										<option value="17">Vòng 17</option>
										<option value="18" disabled>Vòng 18</option>
										<option value="19" disabled>Vòng 19</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Ghi chú:</label>
								<div class="col-sm-8">
									<textarea ng-model="note" rows="3" class="form-control" placeholder="Viết ghi chú" ng-required="action=='add_vip'"></textarea>
								</div>
							</div>
							<div class="text-center">{{error}}</div>
						</div>
						<div class="box-footer text-center">
							<button ng-disabled="executing" type="submit" class="btn btn-primary btn-search">Áp dụng hành động</button>
							<button ng-click="clearList()"  type="button" class="btn btn-primary btn-search">Xóa trắng danh sách</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<div id="modal_add_vip_result" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Kết cộng ngày học trả về</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="form-group">
								<label for="tb_id_pass_edit" class="col-sm-3 control-label">ID thành công:</label>
								<div class="col-sm-9">
									<textarea rows="6" ng-value="vip_result.ok" class="form-control" readonly></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="tb_id_pass_edit" class="col-sm-3 control-label">ID lỗi:</label>
								<div class="col-sm-9">
									<textarea rows="6" ng-value="vip_result.err" class="form-control" readonly></textarea>
								</div>
							</div>
						</div>
					</div>
					<!--<div class="modal-footer">
					</div>-->
				</form>
			</div>
		</div>
	</div>

	<div id="modal_update_result" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Thông tin cập nhật</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="form-group">
								<label for="tb_id_pass_edit" class="col-sm-3 control-label">Thành công:</label>
								<div class="col-sm-9">
									<input type="text" ng-value="update_result.ok==1" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="tb_id_pass_edit" class="col-sm-3 control-label">Update user tồn tại:</label>
								<div class="col-sm-9">
									<input type="text" ng-value="update_result.updatedExisting" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="tb_id_pass_edit" class="col-sm-3 control-label">Số user cập nhật:</label>
								<div class="col-sm-9">
									<input type="text" ng-value="update_result.n" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="tb_id_pass_edit" class="col-sm-3 control-label">Lỗi:</label>
								<div class="col-sm-9">
									<input type="text" ng-value="update_result.err" class="form-control" disabled>
								</div>
							</div>
						</div>
					</div>
					<!--<div class="modal-footer">
					</div>-->
				</form>
			</div>
		</div>
	</div>
</div>