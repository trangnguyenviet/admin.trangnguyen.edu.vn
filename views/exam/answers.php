<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script src="/plugins/ng-ckeditor/src/scripts/02-directive.js?v=1.0.0"></script>
<link rel="stylesheet" href="/plugins/ng-ckeditor/ng-ckeditor.css">
<section class="content-header">
	<h1>
		Danh sách bài thi đáp án
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
											<option value="0">Mẫu giáo</option>
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
												<th class="tnw-6">Tên bài thi</th>
												<th class="tnw-2">Số câu hỏi</th>
												<th class="tnw-2">Thời gian thi</th>
												<th class="tnw-2">Ngày tạo</th>
												<th class="tnw-2">Cập nhật</th>
												<th class="tnw-2">Mở/Khóa</th>
												<th class="tnw-3">Thao tác</th>
											</tr>
											</thead>
											<tbody>
											<tr role="row" ng-repeat="info in content track by $index">
												<td class="tnw-2"><img ng-src="{{getThumb(info.thumb)}}" src="/images/no_photo.jpg" class="img-thumbnail"/></td>
												<td class="tnw-1 text-center">{{info._id}}</td>
												<td class="tnw-6">{{info.name}}</td>
												<td class="tnw-2 text-center">{{info.content_count}}/{{info.play}}</td>
												<td class="tnw-2 text-center">{{info.time}}s</td>
												<td class="tnw-2 text-center">{{mongoDateShow(info.created_at)}}</td>
												<td class="tnw-2 text-center">{{mongoDateShow(info.updated_at)}}</td>
												<td class="tnw-2 text-center">
													<a ng-show="info.active" href="javascript:void(0);" title="Khóa bài thi" ng-click="setActive(info._id, false)"><i class="fa fa-check-square-o text-green"></i></a>
													<a ng-show="!info.active" href="javascript:void(0);" title="Mở bài thi" ng-click="setActive(info._id, true)"><i class="fa fa-square-o text-red"></i></a>
												</td>
												<td class="tnw-3 text-center">
													<a href="javascript:void(0)" ng-click="edit(info)">Sửa</a> | <a href="javascript:void(0)" ng-click="copy(info)">Copy</a> | <a href="javascript:void(0)" ng-click="delete(info)">Xóa</a>
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
									<button class="btn btn-lg btn-success" ng-click="add()" type="button"><i class="fa fa-plus"></i> Thêm bài thi</button>
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
							<strong ng-show="modal.add">Thêm bài thi</strong>
							<strong ng-show="!modal.add">Sửa bài thi</strong>
						</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" ng-submit="save()">
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Thumb:</label>
								<div class="col-xs-6">
									<!--<img class="thumb img-thumbnail" ng-src="{{getThumb(modal.thumb, true)}}">
									<div><label class="control-label">{{modal.thumb_size}}</label></div>
									<div>
										<button type="button" ng-click="ChangeThumb()" class="btn btn-default">Đổi ảnh</button>
										<button type="button" ng-click="RemoveThumb()" class="btn btn-default">Xóa ảnh</button>
									</div>-->
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
								<label class="col-sm-2 control-label col-sm-offset-1">Tên bài thi:</label>
								<div class="col-xs-6">
									<input type="text" ng-model="modal.name" class="form-control" placeholder="Tên bài thi" required/>
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
								<label class="col-sm-2 control-label col-sm-offset-1">Số câu thi:</label>
								<div class="col-xs-6">
									<input type="number" ng-model="modal.play" min="1" class="form-control" placeholder="Số câu thi" required/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Thời gian thi:</label>
								<div class="col-xs-6">
									<input type="number" ng-model="modal.time" min="1" class="form-control" placeholder="Thời gian thi" required/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Mô tả:</label>
								<div class="col-xs-6">
									<textarea ng-model="modal.description" rows="3" class="form-control" placeholder="Mô tả bài thi" required></textarea>
								</div>
							</div>
							<div class="questions">
								<div ng-show="isReady" ng-repeat="info in modal.content track by $index " class="well well-sm questions-item">
									<div class="form-group">
										<label class="col-sm-1 control-label">Câu hỏi {{$index+1}}:</label>
										<div class="col-xs-11" ng-cloak>
											<textarea ckeditor="editorQuestion" ng-model="info.question" rows="4" class="form-control" placeholder="Nội dung câu hỏi"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">Đáp án:</label>
										<div class="col-xs-11" ng-show="info.type == 1">
											<div class="row">
											<div class="col-xs-6">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answered"  name="s{{$index}}" value="0" type="radio"></span>
													<input ng-model="info.answer[0]" class="form-control input-sm" type="text" placeholder="Đáp án 1" required/>
													<span class="input-group-addon" ng-click="editerAnswer($index, 0)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="col-xs-6">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answered" name="s{{$index}}" value="1" type="radio"></span>
													<input ng-model="info.answer[1]" class="form-control input-sm" type="text" placeholder="Đáp án 2" required/>
													<span class="input-group-addon" ng-click="editerAnswer($index, 1)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
												</div>
											</div>
											</div>
											<div class="row" style="margin-top: 10px;">
											<div class="clearfix visible-xs"></div>
											<div class="col-xs-6">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answered" name="s{{$index}}" value="2" type="radio"></span>
													<input ng-model="info.answer[2]" class="form-control input-sm" type="text" placeholder="Đáp án 3" required/>
													<span class="input-group-addon" ng-click="editerAnswer($index, 2)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="col-xs-6">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answered" name="s{{$index}}" value="3" type="radio"></span>
													<input ng-model="info.answer[3]" class="form-control input-sm" type="text" placeholder="Đáp án 4" required/>
													<span class="input-group-addon" ng-click="editerAnswer($index, 3)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
												</div>
											</div>
											</div>
										</div>
										<div class="col-xs-11" ng-show="info.type == 2">
											<input ng-model="info.answered" class="form-control input-sm" type="text" placeholder="Đáp án trả lời" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">Giải đáp:</label>
										<div class="col-xs-11" ng-cloak>
											<textarea ckeditor="editorTheAnswer" ng-model="info.the_answer" class="form-control" rows="4" placeholder="Hướng dẫn giải"></textarea>
										</div>
									</div>
									<div class="row text-center">
										<div class="col-xs-12"><a ng-click="deleteQuestion($index)" class="btn btn-danger"><i class="fa fa-remove"></i> Xóa câu hỏi {{$index+1}}</a></div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<div class="row text-center text-danger">
							{{modal.error}}
						</div>
						<div class="row text-info text-center">
							<h3><span class="label label-info">Số câu hỏi: {{modal.content.length}}/{{modal.play}}</span>
						</div>
						<div class="row text-center">
							<button class="btn btn-lg btn-success" ng-click="addQuestion(1)" type="button"><i class="fa fa-plus"></i> Thêm trắc nghiệm</button>
							<button class="btn btn-lg btn-success" ng-click="addQuestion(2)" type="button"><i class="fa fa-plus"></i> Thêm tự luận</button>
							<button class="btn btn-lg btn-success" ng-click="showImport()" type="button"><i class="fa fa-upload"></i> Import XLSX</button>
							<button class="btn btn-lg btn-success" ng-click="save()" type="button"><i class="fa fa-floppy-o"></i> Lưu bài thi</button>
							<button class="btn btn-lg btn-success" type="button"  data-dismiss="modal"><i class="fa fa-times"></i> Đóng cửa sổ</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal_import" class="modal fade bs-example-modal-lg" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form" role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info">
							<strong>Import câu hỏi từ file xlsx</strong>
						</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" ng-submit="import()">
							<div class="form-group">
								<label class="col-sm-2 control-label col-sm-offset-1">Chọn file (xlsx):</label>
								<div class="col-xs-4">
									<input id="file_input" type="file" class="file">
								</div>
							</div>
							<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover dataTable">
											<thead>
											<tr role="row">
												<th class="tnw-1">STT</th>
												<th class="tnw-2">Thể loại</th>
												<th class="tnw-6">Câu hỏi</th>
												<th class="tnw-2">Đáp án 1</th>
												<th class="tnw-2">Đáp án 2</th>
												<th class="tnw-2">Đáp án 3</th>
												<th class="tnw-2">Đáp án 4</th>
												<th class="tnw-1">Trả lời</th>
												<th class="tnw-6">Gợi ý</th>
											</tr>
											</thead>
											<tbody>
											<tr role="row" ng-repeat="info in imports track by $index">
												<td class="text-center">{{$index+1}}</td>
												<td>{{info.type == 1? 'Trắc nghiệm': 'Tự luận'}}</td>
												<td>{{info.cauhoi}}</td>
												<td>{{info.dapan1}}</td>
												<td>{{info.dapan2}}</td>
												<td>{{info.dapan3}}</td>
												<td>{{info.dapan4}}</td>
												<td class="text-center">{{info.traloi}}</td>
												<td>{{info.goiy}}</td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<button class="btn btn-lg btn-success" ng-click="exeImport()" type="button"><i class="fa fa-upload"></i> Import</button>
								</div>
							</div>
						</form>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal_editer" class="modal fade bs-example-modal-lg" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form" role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Sửa đáp án</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<textarea id="tb_editer_content" class="form-control" placeholder="Nội dung tin"></textarea>
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
								<button ng-click="doneAnswer()" type="submit" class="btn btn-primary">Done</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
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
								<label class="modal-message">{{message_copy}}</label>file_input
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

</div>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>-->
<!--<script src="/plugins/js-xlsx/dist/xlsx.core.min.js"></script>-->
<script src="/js/xlsx.core.min.js"></script>
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script>
	$(function(){
		//$('.number').ForceNumericOnly();
		window.editor = CKEDITOR.replace('tb_editer_content');

		$('body').addClass('sidebar-collapse');
		window.modal_detail = $('#modal_detail').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});
		window.modal_import = $('#modal_import').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.modal_editer = $('#modal_editer').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.modal_copy = $('#modal_copy').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		$('#file_input').change(function(e){
			var files = e.target.files;
			var i, f;
			for (i = 0, f = files[i]; i !== files.length; ++i) {
				var reader = new FileReader();
				//var name = f.name;
				reader.onload = function (e) {
					var data = e.target.result;

					/* if binary string, read with type 'binary' */
					var result;
					var workbook = XLSX.read(data, { type: 'binary' });

					/* DO SOMETHING WITH workbook HERE */
					workbook.SheetNames.forEach(function (sheetName) {
						var roa = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
						if (roa.length > 0) {
							result = roa;
						}
					});
					//console.log(JSON.stringify(result));

					//
					var appElement = document.querySelector('[ng-app=adminApp]');
					var appScope = angular.element(appElement).scope();
					var controllerScope = appScope.$$childHead;
					controllerScope.SetDataImport(result);
				};
				reader.readAsArrayBuffer(f);
			}
		});

		window.finder = new CKFinder();
		finder.defaultLanguage = 'vi';
		finder.language = 'vi';
		finder.removePlugins = 'basket';
		finder.selectActionFunction = function(src){
			var appElement = document.querySelector('[ng-app=adminApp]');
			var appScope = angular.element(appElement).scope();
			var controllerScope = appScope.$$childHead;
			controllerScope.SetThumb(src);
		};
		finder.resourceType = 'Thumb_News';
		finder.tabIndex = 1;
		finder.startupPath = "Thumb_News:/";
		finder.callback = function(api){
			api.openFolder('Thumb_News', '/');
		};
	});

	var DrawContent = function(q){
		if(q){
			//var matchs = q.match(/\{img:[a-zA-Z0-9\-\_\%\.\~]+\}/gi);
			var matchs = q.match(/\{img:.+\}/gi);
			if (matchs != null) {
				$.each(matchs, function (i, val) {
					var src = val.substr(5, val.length - 6);
					q = q.replace(val, '<img src="' + src + '"/>');
				});
			}
			matchs = q.match(/\$(-)?\d*\\\w+\{\d+\}(\{\d+\})?\$/g);
			if (matchs != null) {
				$.each(matchs, function (i, val) {
					var src = 'http://latex.codecogs.com/gif.latex?' + encodeURI(val.replace(/\$/g,''));
					q = q.replace(val, '<img src="' + src + '"/>');
				});
			}
		}
		return q;
	};

	var app = angular.module("adminApp", ['ngCkeditor']);
	app.controller("mainController", function($scope, $http) {
		$scope.class_id = '0';
		$scope.subject_id = '1';
		$scope.key_search = '';
		$scope.content = [];
		$scope.page_info = '';
		$scope.paginate = '';
		$scope.error = '';
		$scope.page_index = 0;
		$scope.isReady = true;
		$scope.modal = {};
		$scope.imports = [];

		$scope.editorQuestion = {
			language: 'vi',
			height: '200px',
			removePlugins: 'basket'
		};
		$scope.editorTheAnswer = {
			language: 'vi',
			height: '200px'
		};

		$scope.SetThumb = function(src) {
			if(src && src !== '') {
				$scope.modal.thumb = src;
				var img = new Image();
				img.src=src;
				img.onload = function() {
					$scope.modal.thumb_size = '(' + this.width + 'x' + this.height + ')';
					$scope.$apply();
				};
			}
		};

		$scope.SetDataImport = function(data) {
			$scope.imports = data;
			$scope.$apply();
		};

		//$scope.$on("ckeditor.ready", function(event) {
		//	$scope.isReady = true;
		//});

		$scope.getImgSize = function(src, cb) {
			if(src && src !== '') {
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

		$scope.getThumb = function(src, bsize){
			if(src && src !== '') {
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

		$scope.ChangeThumb = function(){
			finder.popup('/ckfinder/',700,400);
		};

		$scope.RemoveThumb = function(){
			$scope.modal.thumb = null;
			$scope.modal.thumb_size = '';
		};

		$scope.mongoDateShow = function(obj){
			if(obj){
				var sec = obj.sec;
				if(sec && typeof sec === 'number'){
					return util.date2String(new Date(sec*1000));
				}
			}
			return '';
		};

		$scope.add = function(){
			$scope.modal = {
				add: true,
				_id: 0,
				play: 100,
				name: '',
				time: 1800,
				description: '',
				thumb: '',
				thumb_size: '',
				content: [{
					type: 1,
					question: '',
					answered: '',
					answer: ['','','',''],
					the_answer: ''
				}]
				//content: []
			};
			modal_detail.modal('show');
		};

		$scope.edit = function(info){
			$http({
				method: "post",
				url: '/ajax/exam_answers_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'info', id: info._id})
			})
			.then(function(res) {
				var data = res.data;
				if(data) {
					if(data.error === 0) {
						if(data.info){
							//data.info.thumb_size = '';
							$scope.modal = data.info;
							modal_detail.modal('show');
						} else {
							Alert('Không tìm thấy thông tin bài thi');
						}
					} else {
						$scope.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		$scope.copy = function(info){
			$scope.copy_info = info;
			$scope.class_copy_id = $scope.class_id;
			$scope.subject_copy_id = $scope.subject_id;

			modal_copy.modal('show');
		};

		$scope.execCopy = function(){
			$http({
				method: "post",
				url: '/ajax/exam_answers_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'copy', id: $scope.copy_info._id, class_id: $scope.class_copy_id, subject_id: $scope.subject_copy_id})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error === 0) {
						if($scope.class_copy_id == $scope.class_id && $scope.subject_copy_id == $scope.subject_id){
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

		$scope.setActive = function(id, active) {
			// console.log(id, status);
			var msg = status? 'Bạn muốn kích hoạt bài này không?': 'Bạn muốn bỏ kích hoạt bài này?';
			Confirm(msg, function(result){
				if(result){
					$http({
						method: "post",
						url: '/ajax/exam_answers_cmd.php',
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						data: $.param({action: 'active', id, active})
					})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error === 0) {
								LoadData();
								Alert('Thành công!');
							} else{
								Alert(data.message);
							}
						}
					}, function(res) {
						$scope.error = 'Status error code: ' + res.status;
					});
				}
			});
		};

		$scope.delete = function(info){
			Confirm('Bạn có muốn xóa bài thi này không?', function(result){
				if(result){
					$http({
						method: "post",
						url: '/ajax/exam_answers_cmd.php',
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						data: $.param({action: 'delete', id: info._id, class_id: $scope.class_id, subject_id: $scope.subject_id})
					})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error==0){
								LoadData();
								Alert('Xóa thành công!');
							}
							else{
								$scope.error = data.message;
							}
						}
					}, function(res) {
						$scope.error = 'Status error code: ' + res.status;
					});
				}
			});
		};

		$scope.deleteQuestion = function($index){
			Confirm('Bạn có muốn xóa bài thi này không?', function(result){
				if(result){
					if($scope.modal.content && $scope.modal.content.length>0){
						$scope.modal.content.splice($index,1);
						$scope.$apply();
					}
				}
			});
		};

		$scope.addQuestion = function($type){
			if(!$scope.modal.content) $scope.modal.content = [];
			var new_question = {
				type: $type,
				question: '',
				answered: '',
				the_answer: ''
			};
			if($type==1){
				new_question.answer = ['','','',''];
			}
			$scope.modal.content.push(new_question);
			$scope.modal.count = $scope.content.length;
		};

		$scope.save = function(){
			var data_post = {
				action: 'save',
				id: $scope.modal._id,
				name: $scope.modal.name,
				rewrite: write($scope.modal.name),
				class_id: $scope.class_id,
				subject_id: $scope.subject_id,
				play: $scope.modal.play,
				time: $scope.modal.time,
				description: $scope.modal.description,
				thumb: $scope.modal.thumb,
				content: $scope.modal.content
			};

			if(!data_post.name || data_post.name==''){
				Alert('Hãy nhập tên bài thi');
				return;
			}

			if(util.parseInt(data_post.play, 0) < 1){
				Alert('Hãy nhập số câu thi');
				return;
			}

			if(util.parseInt(data_post.time, 0) < 1){
				Alert('Hãy nhập thời gian thi');
				return;
			}

			for(var i = 0; i < data_post.content.length; i++ ){
				var question_raw = data_post.content[i];
				if(!question_raw.question || question_raw.question==''){
					Alert('Phải nhập tên bài thi, câu: ' + (i+1));
					return;
				}
				if(!question_raw.type) question_raw.type = 1;
				if(question_raw.type==1){
					for(var j=0; j < 4; j++){
						if(question_raw.answer[j] == ''){
							Alert('Phải nhập đầy đủ đáp án trả lời, câu: ' + (i+1));
							return;
						}
					}
				}
				if(!question_raw.answered || question_raw.answered==''){
					Alert('Phải chọn đáp án trả lời, câu: ' + (i+1));
					return;
				}
			}

			if(data_post.content.length < data_post.play){
				Alert('Số câu hỏi phải ≥ số câu thi. Số câu hỏi mới được ' + data_post.content.length + '/' + data_post.play + ' câu');
				return;
			}

			$scope.modal.error = 'Đang xử lý...';

			$http({
				method: "post",
				url: '/ajax/exam_answers_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param(data_post)
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0) {
						$scope._id = data.id;
						LoadData();
						setTimeout(function(){
							modal_detail.modal('hide');
						}, 1000);
						$scope.modal.error = '';
					}
					else{
						$scope.modal.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
		};

		var LoadData = function(){
			$scope.error = 'loading...';
			$http({
				method: "post",
				url: '/ajax/exam_answers_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'list', search: $scope.key_search, page_index: $scope.page_index, class_id: $scope.class_id, subject_id: $scope.subject_id})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						$scope.content = data.content;
						$scope.error = '';
					}
					else{
						Alert(data.message,function(){
							if(data.error==5){
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

		$scope.editerAnswer = function(index, i){
			//Alert('Tính năng này hơi khó, đang nghiên cứu');
			var content = $scope.modal.content[index].answer[i];
			editor.config.filebrowserImageBrowseUrl = '/ckfinder/ckfinder.html?Type=Image_Game';
			editor.config.filebrowserImageUploadUrl = '/ckfinder/core/connctor/php/connector.php?command=QuickUpload&type=Image_Game';
			editor.setData(content);
			modal_editer.modal('show');
			$scope.editing={index: index, i: i};
		};

		$scope.doneAnswer = function(){
			var content = editor.getData();
			$scope.modal.content[$scope.editing.index].answer[$scope.editing.i] = content;
			modal_editer.modal('hide');
		};

		$scope.showImport = function(){
			$scope.imports = [];
			$('#file_input').val('');
			modal_import.modal('show');
		};

		$scope.exeImport = function(){
			if($scope.imports && $scope.imports.length > 0) {
				for(var i = 0, info; info = $scope.imports[i]; i++) {
					var type = util.parseInt(info.type);
					$scope.modal.content.push({
						type: type,
						question: DrawContent(info.cauhoi),
						answered: type === 1 ? (parseInt(info.traloi) - 1).toString(): info.traloi,
						the_answer: DrawContent(info.goiy),
						answer: [DrawContent(info.dapan1), DrawContent(info.dapan2), DrawContent(info.dapan3), DrawContent(info.dapan4)]
					});
				}
			}
			modal_import.modal('hide');
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

		$scope.back = function(){
			window.history.go(-1);
		};
	});
</script>