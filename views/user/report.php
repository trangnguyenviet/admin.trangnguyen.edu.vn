<?php
require_once './model/ProvinceData.php';
$ProvinceData = ProvinceData::getInstance();
$list_data = $ProvinceData->GetList();
$list_province=$list_data['list'];
?>
<section class="content-header">
	<h1>
		Thống kê dữ liệu điểm
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Tìm kiếm thông tin:</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="ddl_province" class="col-sm-2 control-label">Địa chỉ:</label>
									<div class="col-sm-3">
										<select class="form-control" id="ddl_province">
											<option value="0">Tất cả tỉnh/TP</option>
											<?php 
												if(count($list_province)>0){
													foreach ($list_province as $item){
														echo '<option value="'.$item['_id'].'">'.$item['name'].'</option>';
													}
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<select class="form-control" id="ddl_district" disabled="disabled">
											<option value="0">Tất cả huyện</option>
										</select>
									</div>
									<div class="col-sm-3">
										<select class="form-control" id="ddl_school" disabled="disabled">
											<option value="0">Tất cả trường</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="ddl_class" class="col-sm-2 control-label">Lớp:</label>
									<div class="col-sm-3">
										<select class="form-control" id="ddl_class">
											<option value="0">Tất cả lớp</option>
											<option value="1">Lớp 1</option>
											<option value="2">Lớp 2</option>
											<option value="3">Lớp 3</option>
											<option value="4">Lớp 4</option>
											<option value="5">Lớp 5</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="tb_round" class="col-sm-2 control-label">Đã qua vòng:</label>
									<div class="col-sm-3">
										<input type="number" class="form-control" id="tb_round" value="16"/>
									</div>
								</div>
							</div>
						</div> -->
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="tb_limit" class="col-sm-2 control-label">Top:</label>
									<div class="col-sm-3">
										<input type="number" class="form-control" id="tb_limit" value="30"/>
									</div>
								</div>
							</div>
						</div>
						<div class="row text-center">
							<button type="button" id="bt_search" tn-action="search" class="btn btn-primary btn-search">Search</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="content" id="content_info" style="display:none">
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
											<th class="tnw-1">STT</th>
											<th class="tnw-2">ID</th>
											<th class="tnw-3">Username</th>
											<th class="tnw-3">Fullname</th>
											<th class="tnw-2">Birthday</th>
											<th class="tnw-1">Class</th>
											<th class="tnw-2">Class name</th>
											<th class="tnw-4">Trường</th>
											<th class="tnw-2">Score</th>
											<th class="tnw-2">Time</th>
											<th class="tnw-2">Round</th>
										</tr>
									</thead>
									<tbody class="tbody"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="modal_score_detail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Điểm bài thi</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_score_edit" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_id_score_edit" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_score_edit" class="col-sm-3 control-label">Username:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_username_score_edit" value="" placeholder="username" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="ddl_exam_type" class="col-sm-3 control-label">Exam type:</label>
							<div class="col-sm-7">
								<select id="ddl_exam_type" class="form-control">
									<option value="4">Tiếng Việt</option>
									<option value="1">Toán</option>
									<option value="2">English</option>
									<option value="3">Cuối Tuần</option>
								</select>
							</div>
						</div>
						<div class="row">
							<table class="table table-bordered table-hover dataTable">
								<thead>
									<tr role="row">
										<th class="tnw-2">Vòng</th>
										<th class="tnw-2">Lần</th>
										<th class="tnw-4">Điểm</th>
										<th class="tnw-6">Thời gian</th>
										<th class="tnw-6">Ngày thi</th>
										<th class="tnw-4">Action</th>
									</tr>
								</thead>
								<tbody class="tbody"></tbody>
								<tfoot>
									<tr>
										<td colspan="6">
											<div class="col-sm-6" id="rank_score_national">Xếp hạng toàn quốc:</div>
											<div class="col-sm-6" id="rank_score_province">Xếp hạng tỉnh/thành phố:</div>
											<div class="col-sm-6" id="rank_score_district">Xếp hạng quận/huyện:</div>
											<div class="col-sm-6" id="rank_score_school">Xếp hạng trường:</div>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-sm-8 message">
							<label class="modal-message"></label>
						</div>
						<div class="col-sm-4 button">
							<button type="submit" class="btn btn-primary btn-update">Update</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="/js/user_score.js"></script>