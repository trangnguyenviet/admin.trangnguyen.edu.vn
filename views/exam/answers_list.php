<link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
<link href="/plugins/datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/plugins/angular/angular.min.js"></script>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content-header">
		<h1>Thi và đáp án<small></small></h1>
	</section>
	<section class="content">
		<div class="row" id="input-detail">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Thông tin đề thi:</h3>
					</div>
					<div class="box-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label for="tb_id" class="col-sm-2 control-label">ID:</label>
								<div class="col-sm-10">
									<input type="number" ng-value="_id" class="form-control input-sm" id="tb_id" value="0" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="tb_name" class="col-sm-2 control-label">Tên bài thi:</label>
								<div class="col-sm-10">
									<input type="text" ng-model="name" class="form-control input-sm" placeholder="Tên bài thi" required>
								</div>
							</div>
							<div class="form-group">
								<label for="tb_name" class="col-sm-2 control-label">Thời gian:</label>
								<div class="col-sm-10">
									<div class="input-group input-daterange">
										<input ng-model="date_from" placeholder="YYYY-MM-DD" class="form-control" type="text" required>
										<span class="input-group-addon">đến</span>
										<input ng-model="date_to" placeholder="YYYY-MM-DD" class="form-control" type="text" required>
									</div>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-sm-12 message">{{error}}</div>
							</div>
							<div class="row text-center">
								<button type="button" ng-click="add()" class="btn btn-primary btn-add">Add</button>
								<button type="button" ng-click="save()" class="btn btn-primary btn-save">Save</button>
								<div class="text-light-blue">(Bạn có quyền xem, sửa, xóa, thêm)</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="content" ng-show="list.length > 0">
		<div class="row" ng-repeat="info in list track by $index">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">{{info.name}}</h3>&nbsp;
                        <small>({{MsToDate(info.date_from)}} - {{MsToDate(info.date_to)}})</small>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
							<div class="row">
								<div class="col-sm-12">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
											<th class="tnw-4" ng-repeat="class_id in [1,2,3,4,5,6]">Lớp {{class_id}}</th>
										</tr>
										</thead>
										<tbody class="tbody">
										<tr class="text-center">
											<td ng-repeat="class_id in [1,2,3,4,5,6]">
												<div ng-show="info.detail && info.detail[class_id]">
													<div class="tn-exam-info">Câu hỏi: <strong>.../...</strong> | Thời gian: <strong>...</strong>s</div>
													<div>
														<a ng-click="EditExam(info._id,class_id)" href="javascript:void(0)">Sửa</a> |
														<a ng-click="CopyExam(info._id,class_id)" href="javascript:void(0)">Copy</a> |
														<a ng-click="DeleteExam(info._id,class_id)" href="javascript:void(0)">Xóa</a>
													</div>
												</div>
												<div ng-show="!info.detail">
													<div class="tn-exam-info">(chưa có dữ liệu)</div>
													<div>
														<a ng-click="EditExam(info._id,class_id)" href="javascript:void(0)">Sửa</a>
													</div>
												</div>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row text-center">
								<button type="button" ng-click="EditType(info._id)" class="btn btn-warning btn-edit">Sửa</button>
								<button type="button" ng-click="DeleteType(info._id)" class="btn btn-danger btn-del">Xóa</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div id="modal_copy" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Copy đề thi</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<input type="hidden" class="form-control" id="tb_id_copy" value="0">
							<div class="form-group">
								<label for="ddl_exam_type_copy" class="col-sm-3 control-label">Tên sự kiện:</label>
								<div class="col-sm-7">
									<select id="ddl_exam_event_copy" class="form-control">
										<option value="0">Chọn sự kiện</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="ddl_class_copy" class="col-sm-3 control-label">Lớp:</label>
								<div class="col-sm-7">
									<select id="ddl_class_copy" class="form-control">
										<option value="1">Lớp 1</option>
										<option value="2">Lớp 2</option>
										<option value="3">Lớp 3</option>
										<option value="4">Lớp 4</option>
										<option value="5">Lớp 5</option>
										<option value="6">Lớp 6</option>
									</select>
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
								<button type="submit" class="btn btn-primary btn-copy">Copy</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/js/exam_answers_list.js?v=1.0.0"></script>