<?php 
require_once './model/ProvinceData.php';
$ProvinceData = ProvinceData::getInstance();
$list_data = $ProvinceData->GetList();
$list_province=$list_data['list'];
?>
<section class="content-header">
	<h1>
		Danh sách trường học
		<small></small>
	</h1>
</section>
<section class="content">
	<form role="form" class="form-horizontal">
		<div class="row">
			<div class="col-sm-5">
				<div class="form-group">
					<label for="ddl_province" class="col-sm-4 control-label">Tỉnh/thành phố:</label>
					<div class="col-sm-8">
						<select class="form-control" id="ddl_province" style="font-weight: bold;">
							<?php
								if($list_province!=null && count($list_province)>0){
									foreach ($list_province as $province){
										echo '<option value="'.$province['_id'].'">'.$province['name'].'</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<label for="ddl_district" class="col-sm-4 control-label">quận/huyện:</label>
					<div class="col-sm-8">
						<select class="form-control" id="ddl_district" style="font-weight: bold;">
						</select>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap district-data">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="col-xs-2">ID</th>
											<th class="col-xs-6">Name</th>
											<th class="col-xs-4">Action</th>
										</tr>
									</thead>
									<tbody class="tbody"></tbody>
								</table>
							</div>
						</div>
						<div class="box-footer">
							<button type="button" tn-action="add" class="btn btn-primary btn-add">Thêm</button>
							<div class="text-light-blue">(Bạn có quyền xem, thêm, sửa)</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="modal-detail" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="form" role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Thêm/Sửa thông tin quận/huyện</strong></h4>
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
<script src="/js/school.js"></script>