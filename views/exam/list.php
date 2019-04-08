<section class="content-header">
	<h1>
		Thông tin đề thi
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Thông tin vòng thi:</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="ddl_exam_type" class="col-sm-2 control-label">Môn học:</label>
							<div class="col-sm-10">
								<select id="ddl_exam_type" class="form-control">
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
							<label for="tb_total_round" class="col-sm-2 control-label">Tổng số vòng:</label>
							<div class="col-sm-10">
								<input type="number" min="0" class="form-control input-sm" id="tb_total_round" value="0" placeholder="total round" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_current_round" class="col-sm-2 control-label">Mở vòng:</label>
							<div class="col-sm-10">
								<input type="number" min="0" class="form-control input-sm" id="tb_current_round" value="0" placeholder="current round">
							</div>
						</div>
						<div class="form-group">
							<label for="tb_current_round" class="col-sm-2 control-label">Thu phí từ vòng:</label>
							<div class="col-sm-10">
								<input type="number" min="0" class="form-control input-sm" id="tb_payment_round" value="0" placeholder="payment round">
							</div>
						</div>
						<div class="row text-center">
							<button type="button" id="bt_save" tn-action="search" class="btn btn-primary btn-save">Save</button>
							<div class="text-light-blue">(Bạn có quyền xem, sửa, xóa, thêm)</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="content" id="content_info">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Thông tin đề thi:</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="tb_total_round" class="col-sm-2 control-label">Chọn lớp:</label>
							<div class="col-sm-2">
								<select class="form-control" id="ddl_class_id">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
						</div>
					</form>
					<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="tnw-3">Vòng thi</th>
											<th class="tnw-7">Bài 1</th>
											<th class="tnw-7">Bài 2</th>
											<th class="tnw-7">Bài 3</th>
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
							<label for="ddl_exam_type_copy" class="col-sm-3 control-label">Môn học:</label>
							<div class="col-sm-7">
								<select id="ddl_exam_type_copy" class="form-control">
									<option value="0">Chọn môn</option>
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
						<div class="form-group">
							<label for="tb_round_copy" class="col-sm-3 control-label">Vòng:</label>
							<div class="col-sm-7">
								<input type="number" min="1" max="35" class="form-control input-sm" id="tb_round_copy" value="1">
							</div>
						</div>
						<div class="form-group">
							<label for="tb_test_copy" class="col-sm-3 control-label">Bài thi số:</label>
							<div class="col-sm-7">
								<input type="number" min="1" max="3" class="form-control input-sm" id="tb_test_copy" value="1">
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

<!--
<ul id="contextMenu" class="dropdown-menu" role="menu" style="display:none" >
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='info'>Xem/Sửa thông tin</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='score'>Xem điểm thi</a></li>
	<li class="divider"></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='change_password'>Đổi password</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='delete_avatar'>Xóa avatar</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='delete'>Xóa accout</a></li>
	<li class="disabled"><a tabindex="-1" href="javascript:void(0)" tn-ct-action='ban'>Ban accout</a></li>
	<li class="divider"></li>
	<li class="disabled"><a tabindex="-1" href="javascript:void(0)" tn-ct-action='send_email'>Send email</a></li>
	<li class="divider"></li>
	<li class="disabled"><a tabindex="-1" href="javascript:void(0)" tn-ct-action='log_login'>Nhật ký truy cập</a></li>
	<li class="disabled"><a tabindex="-1" href="javascript:void(0)" tn-ct-action='log_payment'>Nhật ký nạp tiền</a></li>
</ul>
-->
<?php
	//document: http://php.net/manual/en/function.json-encode.php
	echo '<script>var total_round="'.total_round_js.'",current_round="'.current_round_js.'",payment_round="'.payment_round_js.'",game_type=' . json_encode($GLOBALS['list_game_info'],JSON_FORCE_OBJECT) . ';</script>',PHP_EOL;
?>
<!--<script src="dist/js/validator.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="plugins/daterangepicker/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tagsinput/bootstrap-tagsinput.js"></script>
<script src="ckeditor/ckeditor.js"></script>
<script src="ckfinder/ckfinder.js"></script>
<script src="js/youtube.js"></script>
<script src="js/media.js"></script>
<script src="dist/js/contextmenu.js"></script> -->
<script type="text/javascript" src="https://rawgit.com/SheetJS/js-xlsx/master/shim.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/eligrey/Blob.js/master/Blob.js"></script>
<!--<script type="text/javascript" src="https://rawgit.com/eligrey/FileSaver.js/master/FileSaver.js"></script>-->
<script type="text/javascript" src="/plugins/file-saver/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/swfobject/2.2/swfobject.min.js"></script>
<script src="/js/downloadify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Base64/1.0.1/base64.min.js"></script>
<script src="/js/exam_list.js"></script>