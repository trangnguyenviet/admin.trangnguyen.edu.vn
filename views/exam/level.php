<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script src="/plugins/ng-ckeditor/src/scripts/02-directive.js?v=1.0.0"></script>
<link rel="stylesheet" href="/plugins/ng-ckeditor/ng-ckeditor.css">
<section class="content-header">
	<h1>
		Danh sách bài thi level
		<small></small>
	</h1>
</section>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="form-horizontal" data-toggle="validator">
					<div class="box box-success">
						<div class="box-body">
							<div class="form-group">
								<div class="col-sm-6 col-xs-12" style="padding: 0">
									<label class="col-sm-2 col-xs-12 control-label">Lớp:</label>
									<div class="col-sm-4">
										<select class="form-control col-xs-12" ng-model="class_id" style="font-weight: bold;">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
										</select>
									</div>
									<label class="col-sm-2 col-xs-12 control-label">Môn:</label>
									<div class="col-sm-4">
										<select class="form-control col-xs-12" ng-model="subject_id" style="font-weight: bold;">
											<option value="1">Tiếng Việt</option>
											<option value="2">Toán</option>
											<option value="3">Tiếng Anh</option>
											<option value="4">Khoa học - tự nhiên</option>
											<option value="5">Sử - địa -xã hội</option>
											<option value="6">IQ - Toán tiếng anh</option>
										</select>
									</div>
								</div>
								<div class="clearfix col-sm-offset-3 col-sm-3 col-xs-12">
									<div class="dataTables_filter">
										<div class="input-group">
											<input ng-model="key_search" type="text" class="form-control" placeholder="Nhập từ khóa để tìm kiếm">
											<span class="input-group-btn">
												<button type="submit" ng-click="search()" class="btn btn-flat"><i class="fa fa-search"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover dataTable">
											<thead>
											<tr role="row">
												<th class="tnw-3">Thumb</th>
												<th class="tnw-2">ID</th>
												<th class="tnw-8">Tên chủ đề</th>
												<th class="tnw-2">Số level</th>
												<th class="tnw-3">Ngày tạo</th>
												<th class="tnw-3">Cập nhật</th>
												<th class="tnw-3">Thao tác</th>
											</tr>
											</thead>
											<tbody>
											<tr role="row" ng-repeat="info in content track by $index">
												<td class="tnw-2"><img ng-src="{{getThumb(info.thumb)}}" src="/images/no_photo.jpg" class="img-thumbnail"/></td>
												<td class="tnw-1 text-center">{{info._id}}</td>
												<td class="tnw-8">{{info.name}}</td>
												<td class="tnw-2 text-center">{{info.level}}</td>
												<td class="tnw-2 text-center">{{mongoDateShow(info.created_at)}}</td>
												<td class="tnw-2 text-center">{{mongoDateShow(info.updated_at)}}</td>
												<td class="tnw-3 text-center">
													<!--<a href="javascript:void(0)" ng-click="edit(info)">Sửa</a> | <a href="javascript:void(0)" ng-click="copy(info)">Copy</a> | <a href="javascript:void(0)" ng-click="delete(info)">Xóa</a>-->
													<a href="javascript:void(0)" ng-click="edit(info)">Sửa</a> | <a href="javascript:void(0)" ng-click="listLevel(info)">level</a> | <a href="javascript:void(0)" ng-click="delete(info)">Xóa</a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-5">
										<div class="dataTables_info">
											{{page_info}}
										</div>
									</div>
									<div class="col-sm-7">
										<div class="dataTables_paginate">
											{{paginate}}
										</div>
									</div>
								</div>
								<div class="box-footer">
									<div class="row text-center">
										{{error}}
									</div>
									<button class="btn btn-lg btn-success" ng-click="add()" type="button"><i class="fa fa-plus"></i> Thêm chủ đề</button>
									<div class="text-light-blue">(Bạn có quyền xem, thêm, sửa, xóa)</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
	<div id="modal_detail" class="modal fade bs-example-modal-lg" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form" role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info">
							<strong ng-show="modal.add">Thêm chủ đề</strong>
							<strong ng-show="!modal.add">Sửa chủ đề</strong>
						</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" ng-submit="save()">
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Thumb:</label>
								<div class="col-xs-6">
									<div class="well">
										<div class="media">
											<a class="pull-left" href="javascript:void(0)" ng-click="ChangeThumb()">
												<img class="media-object img-responsive" ng-src="{{getThumb(modal.thumb, true)}}">
											</a>
											<div class="media-body">
												<h4> <span class="media-heading label label-primary">{{modal.thumb || '(Không có ảnh)'}} {{modal.thumb_size}}</span></h4>
												<h4> <span class="label label-danger">Kích thước ảnh nên để là 150x150px</span></h4>
												<br><button type="button" ng-click="ChangeThumb()" class="btn btn-info"><i class="fa fa-refresh"></i> Đổi ảnh</button>
												<button type="button" ng-click="RemoveThumb()" class="btn btn-info"><i class="fa fa-times"></i> Xóa ảnh</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Tên chủ đề:</label>
								<div class="col-xs-6">
									<input type="text" ng-model="modal.name" class="form-control" placeholder="Tên chủ đề" required/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Lớp:</label>
								<div class="col-xs-6">
									<input type="number" ng-value="class_id" class="form-control" disabled/>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Môn:</label>
								<div class="col-sm-6">
									<select class="form-control" ng-model="subject_id" style="font-weight: bold;" disabled>
										<option value="1">Tiếng Việt</option>
										<option value="2">Toán</option>
										<option value="3">Tiếng Anh</option>
										<option value="4">Khoa học - tự nhiên</option>
										<option value="5">Sử - địa -xã hội</option>
										<option value="6">IQ - Toán tiếng anh</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Số level:</label>
								<div class="col-xs-6">
									<input type="number" ng-model="modal.level" min="1" class="form-control" placeholder="Level" required/>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Mô tả:</label>
								<div class="col-xs-6">
									<textarea ng-model="modal.description" rows="3" class="form-control" placeholder="Mô tả bài thi" required></textarea>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<div class="row text-center text-danger">
							{{modal.error}}
						</div>
						<div class="row text-center">
							<button class="btn btn-lg btn-success" ng-click="save()" type="button"><i class="fa fa-floppy-o"></i> Lưu lại</button>
							<button class="btn btn-lg btn-success" type="button"  data-dismiss="modal"><i class="fa fa-times"></i> Đóng cửa sổ</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal_copy" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator" ng-submit="execCopy()">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Copy bài thi</strong></h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-2 control-label"> Lớp:</label>
							<div class="col-xs-10">
								<select class="form-control col-xs-12" ng-model="class_copy_id" style="font-weight: bold;">
									<option value="0">Mẫu giáo</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Môn:</label>
							<div class="col-xs-10">
								<select class="form-control col-xs-12" ng-model="subject_copy_id" style="font-weight: bold;">
									<option value="1">Tiếng Việt</option>
									<option value="2">Toán</option>
									<option value="3">Tiếng Anh</option>
									<option value="4">Khoa học - tự nhiên</option>
									<option value="5">Sử - địa -xã hội</option>
									<option value="6">IQ - Toán tiếng anh</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-sm-8 message">
								<label class="modal-message">{{message_copy}}</label>
							</div>
							<div class="col-sm-4 button">
								<button type="submit" class="btn btn-primary">Copy</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal_level" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator" ng-submit="execCopy()">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Danh sách bài thi</strong></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead class="thead">
									<tr role="row">
										<th class="tnw-2">Level</th>
										<th class="tnw-8">Tên bài thi</th>
										<th class="tnw-6">Game</th>
										<th class="tnw-4">Câu hỏi</th>
										<th class="tnw-4">Action</th>
									</tr>
									</thead>
									<tbody>
									<tr role="row" ng-repeat="level_info in type_info.levels track by $index">
										<td class="text-center"><?php echo '{{$index+1}}'; ?></td>
										<td class="text-center">{{level_info.name}}</td>
										<td class="text-center">{{level_info.game_name}}</td>
										<td class="text-center">{{level_info.question_count || '&nbsp;'}}</td>
										<td class="text-center">
											<a href="level-edit.html?type={{level_info._id}}&level=<?php echo '{{$index+1}}'; ?>" ng-click="EditCode(level_info)">Edit</a>
										</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-sm-12 message">
								<label class="modal-message">{{message_copy}}</label>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="/ckfinder/ckfinder.js"></script>
<script>
	$(function() {
		$('body').addClass('sidebar-collapse');
		window.modal_detail = $('#modal_detail').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.modal_copy = $('#modal_copy').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.modal_level = $('#modal_level').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.finder = new CKFinder();
		finder.defaultLanguage = 'vi';
		finder.language = 'vi';
		finder.removePlugins = 'basket';
		finder.selectActionFunction = function(src) {
			var appElement = document.querySelector('[ng-app="adminApp"]');
			var appScope = angular.element(appElement).scope();
			//var controllerScope = appScope.$$childHead;
			//controllerScope.SetThumb(src);
			appScope.SetThumb(src);
		};
		finder.resourceType = 'Thumb_News';
		finder.tabIndex = 1;
		finder.startupPath = "Thumb_News:/";
		finder.callback = function(api){
			api.openFolder('Thumb_News', '/');
		};
	});

	var app = angular.module("adminApp", []);
	app.controller("mainController", function($scope, $http) {
		$scope.class_id = '1';
		$scope.subject_id = '1';
		$scope.key_search = '';
		$scope.page_info = '';
		$scope.paginate = '';
		$scope.error = '';
		$scope.page_index = 0;
		$scope.isReady = true;
		$scope.modal = {};

		$scope.SetThumb = function(src) {
			if(src && src!=''){
				$scope.modal.thumb = src;
				var img = new Image();
				img.src=src;
				img.onload = function() {
					$scope.modal.thumb_size = '(' + this.width + 'x' + this.height + ')';
					$scope.$apply();
				};
			}
		};

		$scope.getImgSize = function(src, cb) {
			if(src && src!=''){
				$scope.modal.thumb = src;
				var img = new Image();
				img.src=src;
				img.onload = function() {
					cb('(' + this.width + 'x' + this.height + ')');
				};
			}
			else{
				cb(null);
			}
		};

		$scope.getThumb = function(src, bsize) {
			if(src && src!=''){
				if(bsize){
					$scope.getImgSize(src, function(size) {
						if(size){
							$scope.modal.thumb_size = size;
							$scope.$apply();
						}
					});
				}
				return src;
			}
			else if(bsize){
				$scope.modal.thumb_size = '';
			}
			return '/images/no_photo.jpg';
		};

		$scope.ChangeThumb = function() {
			finder.popup('/ckfinder/',700,400);
		};

		$scope.RemoveThumb = function() {
			$scope.modal.thumb = null;
			$scope.modal.thumb_size = '';
		};

		$scope.mongoDateShow = function(obj) {
			if(obj){
				var sec = obj.sec;
				if(sec && typeof sec === 'number') {
					return util.date2String(new Date(sec*1000));
				}
			}
			return '';
		};

		$scope.add = function() {
			$scope.modal = {
				add: true,
				_id: 0,
				level: 1,
				name: '',
				description: '',
				thumb: '',
				thumb_size: '',
			};
			modal_detail.modal('show');
		};

		$scope.edit = function(info) {
			$scope.modal = info;
			modal_detail.modal('show');
		};

		$scope.copy = function(info) {
			$scope.copy_info = info;
			$scope.class_copy_id = $scope.class_id;
			$scope.subject_copy_id = $scope.subject_id;

			modal_copy.modal('show');
		};

		$scope.execCopy = function() {
			$http({
				method: "post",
				url: '/ajax/exam_level_type_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'copy', id: $scope.copy_info._id, class_id: $scope.class_copy_id, subject_id: $scope.subject_copy_id})
			})
			.then(function(res) {
				var data = res.data;
				if(data) {
					if(data.error==0){
						if($scope.class_copy_id == $scope.class_id && $scope.subject_copy_id == $scope.subject_id) {
							LoadData();
						}
						Alert('Copy thành công!');
						modal_copy.modal('hide');
					} else {
						$scope.message_copy = data.message;
					}
				}
			}, function(res) {
				$scope.message_copy = 'Status error code: ' + res.status;
			});
		};

		$scope.delete = function(info) {
			Confirm('Bạn có muốn xóa chủ đề này không?', function(result){
				if(result) {
					$http({
						method: "post",
						url: '/ajax/exam_level_type_cmd.php',
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						data: $.param({action: 'delete', id: info._id, class_id: $scope.class_id, subject_id: $scope.subject_id})
					})
					.then(function(res) {
						var data = res.data;
						if(data) {
							if(data.error === 0) {
								LoadData();
								Alert('Xóa thành công!');
							} else {
								$scope.error = data.message;
							}
						}
					}, function(res) {
						$scope.error = 'Status error code: ' + res.status;
					});
				}
			});
		};

		$scope.deleteQuestion = function($index) {
			Confirm('Bạn có muốn xóa chủ đề này không?', function(result) {
				if(result) {
					if($scope.modal.content && $scope.modal.content.length > 0) {
						$scope.modal.content.splice($index,1);
						$scope.$apply();
					}
				}
			});
		};

		$scope.save = function() {
			var data_post = {
				action: 'save',
				id: $scope.modal._id,
				name: $scope.modal.name,
				rewrite: write($scope.modal.name),
				class_id: $scope.class_id,
				subject_id: $scope.subject_id,
				level: $scope.modal.level,
				description: $scope.modal.description,
				thumb: $scope.modal.thumb,
			};

			if(!data_post.name || data_post.name === '') {
				Alert('Hãy nhập tên chủ đề');
				return;
			}

			if(util.parseInt(data_post.level, 0) < 1) {
				Alert('Hãy nhập số level');
				return;
			}

			$scope.modal.error = 'Đang xử lý...';

			$http({
				method: "post",
				url: '/ajax/exam_level_type_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param(data_post)
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error === 0) {
						$scope._id = data.id;
						LoadData();
						setTimeout(function() {
							modal_detail.modal('hide');
						}, 1000);
						$scope.modal.error = '';
					} else {
						$scope.modal.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		$scope.listLevel = function(info) {
			// var level = info.level;
			// info.levels = new Array(level).fill({name: '(chưa có dữ liệu)', game_name: '(chưa có dữ liệu)', question_count: '0/0'});
			// $scope.type_info = info;
			// modal_level.modal('show');

			$http({
				method: "post",
				url: '/ajax/exam_level_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'list', type: info._id})
			})
				.then(function(res) {
					var data = res.data;
					if(data) {
						if(data.error === 0){
							var level = info.level;
							info.levels = new Array(level).fill({name: '(chưa có dữ liệu)', game_name: '(chưa có dữ liệu)', question_count: '0/0'});
							$scope.type_info = info;
							modal_level.modal('show');
						} else {
							Alert(data.message,function() {
								if(data.error === 5){
									window.location.href = '/login.php';
								}
							});
							$scope.error = data.message;
						}
					}
				}, function(res) {
					$scope.error = 'Status error code: ' + res.status;
				});
		};

		var LoadData = function() {
			$scope.error = 'loading...';
			$http({
				method: "post",
				url: '/ajax/exam_level_type_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'list', search: $scope.key_search, page_index: $scope.page_index, class_id: $scope.class_id, subject_id: $scope.subject_id})
			})
			.then(function(res) {
				var data = res.data;
				if(data) {
					if(data.error === 0) {
						$scope.content = data.content;
						$scope.error = '';
					} else {
						Alert(data.message,function() {
							if(data.error === 5) {
								window.location.href = '/login.php';
							}
						});
						$scope.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		$scope.$watch('class_id', function (class_id) {
			$scope.page_index = 0;
			$scope.class_id = class_id;
			LoadData();
		});

		$scope.$watch('subject_id', function (subject_id) {
			$scope.page_index = 0;
			$scope.subject_id = subject_id;
			LoadData();
		});
	});
</script>