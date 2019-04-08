<section class="content-header">
	<h1>
		Sự kiện thi
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Thông tin sự kiện:</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="tb_id" class="col-sm-2 control-label">ID:</label>
							<div class="col-sm-10">
								<input type="number" min="0" class="form-control input-sm" id="tb_id" value="0" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_name" class="col-sm-2 control-label">Tên sự kiện:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control input-sm" id="tb_name" value="" placeholder="Tên sự kiện">
							</div>
						</div>
						<div class="row text-center">
							<button type="button" id="bt_add" tn-action="add" class="btn btn-primary btn-add">Add</button>
							<button type="button" id="bt_save" tn-action="save" class="btn btn-primary btn-save">Save</button>
							<div class="text-light-blue">(Bạn có quyền xem, sửa, xóa, thêm)</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="content" id="content_info"></section>

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

<script src="/js/event_list.js?v=1.0.1"></script>