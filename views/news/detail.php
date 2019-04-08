<?php
require_once './model/CategoryData.php';
$CategoryData = CategoryData::getInstance();
$list_category = $CategoryData->GetListActive();

$UserAdminData=UserAdminData::getInstance();
$list_admin = $UserAdminData->GetListForView();
echo '<script>var list_admin='. json_encode($list_admin) .';</script>';
echo '<script>var list_category='. json_encode($list_category) .';</script>'
?>
<section class="content-header">
	<h1>
		Bài viết
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
<!-- 				<div class="box-header"> -->
<!-- 					<h3 class="box-title">Danh sách tin:</h3> -->
<!-- 				</div> -->
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group">
									<label for="ddl_parent">Danh mục: </label>
									<select class="form-control" id="ddl_parent" style="font-weight: bold;">
										<?php
											/*if($list_category!=null && count($list_category)>0){
												foreach ($list_category as $category){
													echo '<option value="'.$category['_id'].'">'.$category['name'].'</option>';
												}
											}*/
										?>
									</select>&nbsp;&nbsp;
									<label for="ddl_parent">Chuyên mục: </label>
									<select class="form-control" id="ddl_sub_category" style="font-weight: bold; ">
										<option value="0">&nbsp;</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="dataTables_filter pull-right">
									<!-- <form action="" onsubmit="return false;" method="get">
										<label for="tb_search">Tìm kiếm: </label> -->
										<div class="input-group">
											<input id="tb_search" type="text" class="form-control" placeholder="Nhập từ khóa">
											<span class="input-group-btn">
												<button type="submit" name="search" id="bt_search" class="btn btn-flat"><i class="fa fa-search"></i></button>
											</span>
										</div>
									<!-- </form> -->
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="tnw-2">Thumb</th>
											<th class="tnw-1">ID</th>
											<th class="tnw-10">Name</th>
											<th class="tnw-3">Schedule</th>
											<th class="tnw-1">Sort</th>
											<th class="tnw-2">Create by</th>
											<th class="tnw-2">Create at</th>
											<th class="tnw-1">Active</th>
											<th class="tnw-3">Action</th>
										</tr>
									</thead>
									<tbody class="tbody"></tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<div class="dataTables_info"></div>
							</div>
							<div class="col-sm-7 text-right">
								<div class="dataTables_paginate"></div>
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

<div id="modal_detail" class="modal fade bs-example-modal-lg" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="form" role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Thêm/Sửa tin tức</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id" class="col-sm-2 control-label">ID:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_id" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_id" class="col-sm-2 control-label">Thumb:</label>
							<div class="col-sm-10">
								<img class="thumb img-thumbnail" id="img_thumb" alt="Ảnh thumb" src="/images/no_photo.jpg">
								<input id="hd_thumb" type="hidden" value="">
								<input id="hd_no_image" type="hidden" value="https://placehold.it/220x124?text=no+image">
								<div><label id="lb_thumb_size" for="bt_change_thumb" class="control-label">(size)</label></div>
								<div><label class="control-label text-danger">(Kích thước cho phép: 220x124px)</label></div>
								<div><button type="button" id="bt_change_thumb" class="btn btn-default">Đổi ảnh</button> <button type="button" id="bt_delete_thumb" class="btn btn-default">Xóa ảnh</button></div>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_name" class="col-sm-2 control-label">Tên (<span class="text-red">*</span>):</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_name" placeholder="Tên bản tin" data-error="Hãy nhập tên danh mục" required>
							</div>
						</div>
						<div class="form-group hide">
							<label for="tb_rewrite" class="col-sm-2 control-label">Rewrite:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_rewrite" placeholder="Rewrite" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_description" class="col-sm-2 control-label">Mô tả:</label>
							<div class="col-sm-10">
								<textarea id="tb_description" rows="3" class="form-control" placeholder="Mô tả bản tin"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="cb_publish_date" class="col-sm-2 control-label">Đặt lịch:</label>
							<div class="col-sm-10">
								<div class="input-group">
									<span class="input-group-addon">
										<input id="cb_publish_date" type="checkbox" class="minimal">
									</span>
									<input id="tb_publish_date" type="text" class="form-control">
									<input id="hd_date_from" type="hidden" value="">
									<input id="hd_date_to" type="hidden" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_tags" class="col-sm-2 control-label">Tags:</label>
							<div class="col-sm-10">
								<input type="text" data-role="tagsinput" class="form-control" id="tb_tags" placeholder="tags">
							</div>
						</div>
						<div class="form-group">
							<label for="tb_sort" class="col-sm-2 control-label">Sort (<span class="text-red">*</span>):</label>
							<div class="col-sm-10">
								<input type="number" id="tb_sort" value="0" maxlength="4" class="form-control bfh-number" min="0" max="9999" data-error="Hãy nhập số có giá trị &ge; 0" placeholder="Sort" required>
							</div>
						</div>
						<div class="form-group">
							<label for="cb_active" class="col-sm-2 control-label">Active:</label>
							<div class="col-sm-10">
								<input id="cb_active" type="checkbox" class="minimal">
							</div>
						</div>
						<div class="form-group">
							<label for="tb_content" class="col-sm-2 control-label">Nội dung tin:</label>
							<div class="col-sm-10">
								<textarea id="tb_content" class="form-control" placeholder="Nội dung tin"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="cb_answer" class="col-sm-2 control-label">Câu hỏi:</label>
							<div class="col-sm-10">
								<input id="cb_answer" type="checkbox" class="minimal">&nbsp;&nbsp;
								<label for="rb_1"><input type="radio" class="minimal" name="group1" id="rb_1" value="1" /> Trắc nghiệm</label>&nbsp;&nbsp;
								<label for="rb_2"><input type="radio" class="minimal" name="group1" id="rb_2" value="2" /> Điền</label>
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
							<button type="submit" class="btn btn-primary btn-save">Save</button>
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
			<form role="form" class="form-horizontal" data-toggle="validator" onsubmit="return exeCopy()">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Copy tin tức</strong></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-2 control-label"> Danh mục:</label>
						<div class="col-xs-10">
							<select class="form-control col-xs-12" id="ddl_parent_copy_id" style="font-weight: bold;">
								<?php
								if($list_category!=null && count($list_category)>0){
									foreach ($list_category as $category){
										echo '<option value="'.$category['_id'].'">'.$category['name'].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-sm-8 message">
							<label class="modal-message"></label>
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
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script src="/js/news_detail.js?v=1.1.2"></script>