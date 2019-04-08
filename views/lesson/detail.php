<?php
require_once './model/LessonTypeData.php';
//$LessonTypeData = LessonTypeData::getInstance();

// $list_category = $LessonTypeData->GetListActive();

$UserAdminData=UserAdminData::getInstance();
$list_admin = $UserAdminData->GetListForView();
echo '<script>var list_admin='. json_encode($list_admin) .';</script>';
?>
<section class="content-header">
	<h1>
		Bài giảng
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
							<div class="col-sm-6">
								<div class="form-group">
									<label for="ddl_parent">Chủ đề: </label>
									<select class="form-control" id="ddl_parent" style="font-weight: bold;">
										<?php
											for($i=0;$i<=5;$i++){
												echo '<optgroup label="Lớp '. $i .'">';
												//$list_category = $LessonTypeData->GetListActive($i);
												//if($list_category!=null && count($list_category)>0){
												//	foreach ($list_category as $category){
												//		echo '<option data-class="'. $i .'" value="'.$category['_id'].'">'.$category['name'].'</option>';
												//	}
												//}
												for($j=1;$j<=3;$j++){
													$leson_name;
													if($j==1) $leson_name='Toán';
													if($j==2) $leson_name='Tiếng Anh';
													if($j==3) $leson_name='Tiếng Việt';
													echo '<option data-class="'. $i .'" value="'.$j.'">Lớp '. $i . ' - ' .$leson_name.'</option>';
												}
												echo '</optgroup>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="dataTables_filter">
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
											<th class="tnw-6">Name</th>
											
											<th class="tnw-7">Video URL</th>
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
							<div class="col-sm-7">
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
							<label for="tb_id" class="col-sm-2 control-label">Video type:</label>
							<div class="col-sm-10">
								<select id="ddl_video_type" class="form-control">
									<option value="youtube">Youtube</option>
									<option value="mp4">MP4</option>
									<option value="m3u8">M3U8</option>
									<option value="webm" disabled>WEBM</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_id" class="col-sm-2 control-label">URL:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="tb_url" value="" placeholder="URL" data-error="Hãy nhập url" required>
							</div>
							<div class="col-sm-4 ">
								<button type="button" id="bt_browser" class="btn btn-default" disabled>Duyệt file</button> 
								<button type="button" id="bt_load_info" class="btn btn-default">Get video info</button>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-10 col-sm-offset-2">
								<div class="col-xs-3">
									<div class="form-group">
										<input type="text" class="form-control" id="tb_duration_view" value="00:00" placeholder="Duration" disabled>
										<input type="hidden" id="hd_duration" value="0">
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<input type="text" class="form-control" id="tb_size_view" value="0x0" placeholder="video size" disabled>
										<input type="hidden" id="hd_width" value="0">
										<input type="hidden" id="hd_height" value="0">
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<input type="text" class="form-control" id="tb_format_name" value="" placeholder="Format name" disabled>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<input type="text" class="form-control" id="tb_codec_name" value="" placeholder="Codec name" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_id" class="col-sm-2 control-label">Thumb:</label>
							<div class="col-sm-2">
								<img class="thumb img-thumbnail" id="img_thumb" alt="Ảnh thumb" src="/images/no_photo.jpg">
								<input id="hd_thumb" type="hidden" value="">
								<input id="hd_no_image" type="hidden" value="/images/no_photo.jpg">
								<div><label id="lb_thumb_size" for="bt_change_thumb" class="control-label">(size)</label></div>
							</div>
							<div class="col-sm-8">
								<div><button type="button" id="bt_change_thumb" class="btn btn-default">Đổi ảnh</button></div>
								<div><button type="button" id="bt_delete_thumb" class="btn btn-default">Xóa ảnh</button></div>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_name" class="col-sm-2 control-label">Tên (<span class="text-red">*</span>):</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_name" placeholder="Tên bài giảng" data-error="Hãy nhập tên bài giảng" required>
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
								<textarea id="tb_description" rows="3" class="form-control" placeholder="Mô tả bài giảng"></textarea>
							</div>
						</div>
						<div class="form-group hide">
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
<!--<script src="dist/js/validator.min.js"></script>-->
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script src="/js/youtube.js"></script>
<script src="/js/media.js"></script>
<script src="/js/lesson_detail.js?v=1.1"></script>