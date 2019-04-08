<link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
<section class="content-header">
	<h1>
		Chuyên đề bài giảng
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap category-data">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="ddl_parent">Chọn lớp: </label>
									<select class="form-control" id="ddl_parent" style="font-weight: bold;">
										<option value="1">Lớp 1</option>
										<option value="2">Lớp 2</option>
										<option value="3">Lớp 3</option>
										<option value="4">Lớp 4</option>
										<option value="5">Lớp 5</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="tnw-2">ID</th>
											<th class="tnw-8">Name</th>
											<!-- <th class="tnw-4">Parent</th> -->
											<th class="tnw-8">Rewrite</th>
											<th class="tnw-3">Sort</th>
											<th class="tnw-3">Action</th>
										</tr>
									</thead>
									<tbody class="tbody">
									</tbody>
								</table>
							</div>
						</div>
						<div class="box-footer">
							<button type="button" tn-action="add" class="btn btn-primary btn-add">Thêm</button>
							<div class="text-light-blue">(Bạn có quyền xem, thêm, sửa, xóa)</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="modal-category" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="form" role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Thêm/Sửa danh mục</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id" class="col-sm-2 control-label">ID</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_id" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_name" class="col-sm-2 control-label">Tên (<span class="text-red">*</span>)</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_name" placeholder="Tên chuyên đề" data-error="Hãy nhập tên chuyên đề" required>
								<!-- <div class="help-block with-errors"></div> -->
							</div>
						</div>
						<div class="form-group">
							<label for="tb_rewrite" class="col-sm-2 control-label">Rewrite</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_rewrite" placeholder="Rewrite" disabled>
							</div>
						</div>
<!-- 						<div class="form-group"> -->
<!-- 							<label for="DDL_Parent" class="col-sm-2 control-label">Parent</label> -->
<!-- 							<div class="col-sm-10"> -->
<!-- 								<select class="form-control" id="DDL_Parent" disabled> -->
<!-- 									<option value="0">Null</option> -->
<!-- 								</select> -->
<!-- 							</div> -->
<!-- 						</div> -->
						<div class="form-group">
							<label for="tb_sort" class="col-sm-2 control-label">Sort (<span class="text-red">*</span>)</label>
							<div class="col-sm-10">
								<input type="number" id="tb_sort" value="0" maxlength="4" class="form-control bfh-number" min="0" max="9999" data-error="Hãy nhập số có giá trị &ge; 0" placeholder="Sort" required>
								<!-- <div class="help-block with-errors"></div> -->
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
							<button type="submit" tn-action="save" class="btn btn-primary btn-save">Save</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--<script src="/dist/js/validator.min.js"></script>-->
<script src="/js/lesson_category.js"></script>