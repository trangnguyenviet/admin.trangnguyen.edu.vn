<section class="content-header">
	<h1>
		Danh mục tin tức
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
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="col-xs-1">ID</th>
											<th class="col-xs-4">Name</th>
											<th class="col-xs-2">Parent</th>
											<th class="col-xs-3">Rewrite</th>
											<th class="col-xs-1">Sort</th>
											<th class="col-xs-1">Action</th>
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
					<h4 class="modal-title text-info"><strong>Thêm/Sửa danh mục tin</strong></h4>
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
								<input type="text" class="form-control" id="tb_name" placeholder="Tên danh mục" data-error="Hãy nhập tên danh mục" required>
								<!-- <div class="help-block with-errors"></div> -->
							</div>
						</div>
						<div class="form-group">
							<label for="tb_rewrite" class="col-sm-2 control-label">Rewrite</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_rewrite" placeholder="Rewrite" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="DDL_Parent" class="col-sm-2 control-label">Parent</label>
							<div class="col-sm-10">
								<select class="form-control" id="DDL_Parent">
									<option value="0">Null</option>
								</select>
							</div>
						</div>
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
<!--<script src="dist/js/validator.min.js"></script>-->
<script src="/js/news_category.js"></script>