<?php
require_once './model/ProvinceData.php';
$ProvinceData = ProvinceData::getInstance();
$list_data = $ProvinceData->GetList();
$list_province=$list_data['list'];
?>
<style>
	.pointer {
		cursor: hand;
		cursor: pointer;
	}
</style>
<section class="content-header">
	<h1>
		Thông tin User
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
									<div class="col-sm-2">
										<input type="radio" id="rb_id" control-focus="tb_id" name="select-search-type" class="flat-red">
										<label for="rb_id" class="control-label">ID:</label>
									</div>
									<div class="col-sm-8">
										<input type="number" min="0" class="form-control input-sm" id="tb_id" value="" placeholder="ID">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_list_id" control-focus="tb_list_id" name="select-search-type" class="flat-red">
										<label for="rb_list_id" class="control-label">List id:</label>
									</div>
									<div class="col-sm-8">
										<textarea cols="3" class="form-control input-sm" id="tb_list_id" value="" placeholder="Danh sách id"></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_username" control-focus="tb_username" name="select-search-type" class="flat-red" select-search-type>
										<label for="rb_username" class="control-label">Username:</label>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control input-sm" id="tb_username" value="" placeholder="username">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_fullname" control-focus="tb_fullname" name="select-search-type" class="flat-red">
										<label for="rb_fullname" class="control-label">Fullname:</label>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control input-sm" id="tb_fullname" value="" placeholder="fullname">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_email" control-focus="tb_email" name="select-search-type" class="flat-red">
										<label for="rb_email" class="control-label">Email:</label>
									</div>
									<div class="col-sm-8">
										<input type="email" class="form-control input-sm" id="tb_email" value="" placeholder="email">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_mobile" control-focus="tb_mobile" name="select-search-type" class="flat-red">
										<label for="rb_mobile" class="control-label">Mobile</label>
									</div>
									<div class="col-sm-8">
										<input type="number" class="form-control input-sm" id="tb_mobile" value="" placeholder="mobile">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_address" control-focus="ddl_province" name="select-search-type" class="flat-red">
										<label for="rb_address" class="control-label">Address:</label>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_province">
											<option value="0">Chọn tỉnh/TP</option>
											<?php
											if(count($list_province)>0){
												foreach ($list_province as $item){
													echo '<option value="'.$item['_id'].'">'.$item['name'].'</option>';
												}
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_district" disabled="disabled">
											<option value="0">Tất cả huyện</option>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_school" disabled="disabled">
											<option value="0">Tất cả trường</option>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_class" disabled="disabled">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_award" control-focus="tb_award" name="select-search-type" class="flat-red">
										<label for="rb_award" class="control-label">Giải thưởng:</label>
									</div>
									<div class="col-sm-2">
										<!--<input type="number" class="form-control input-sm" id="tb_award" value="" placeholder="Giải thưởng">-->
										<select class="form-control" id="tb_award">
											<option value="1">Nhất Đình</option>
											<option value="2">Nhì Đình</option>
											<option value="3">Ba Đình</option>
											<option value="4">KK Đình</option>
											<option value="5">Nhất Hội</option>
											<option value="6">Nhì Hội</option>
											<option value="7">Ba Hội</option>
											<option value="8">KK Hội</option>
											<option value="9">Nhất Hương</option>
											<option value="10">Nhì Hương</option>
											<option value="11">Ba Hương</option>
											<option value="12">KK Hương</option>
											<option value="13">Hoàn thành xuất sắc vòng thi số 19</option>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_award_province">
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
									<div class="col-sm-2">
										<select class="form-control" id="ddl_award_class">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_exam_district" control-focus="ddl_exam_district_level_province" name="select-search-type" class="flat-red">
										<label for="rb_exam_district" class="control-label">Thi cấp Huyện:</label>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_district_level_province">
											<option value="0">Chọn tỉnh/TP</option>
											<?php
											if(count($list_province)>0){
												foreach ($list_province as $item){
													echo '<option value="'.$item['_id'].'">'.$item['name'].'</option>';
												}
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_district_level_district" disabled="disabled">
											<option value="0">Tất cả huyện</option>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_district_level_class">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_exam_province" control-focus="ddl_exam_province" name="select-search-type" class="flat-red">
										<label for="rb_exam_province" class="control-label">Thi cấp tỉnh:</label>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_province">
											<option value="0">Chọn tỉnh/TP</option>
											<?php
											if(count($list_province)>0){
												foreach ($list_province as $item){
													echo '<option value="'.$item['_id'].'">'.$item['name'].'</option>';
												}
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_class">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_exam_national" control-focus="ddl_exam_national" name="select-search-type" class="flat-red">
										<label for="rb_exam_national" class="control-label">Thi cấp QG:</label>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_national">
											<option value="0">Chọn tỉnh/TP</option>
											<?php
											if(count($list_province)>0){
												foreach ($list_province as $item){
													echo '<option value="'.$item['_id'].'">'.$item['name'].'</option>';
												}
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_exam_national_class">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_score" control-focus="tb_score_code" name="select-search-type" class="flat-red">
										<label for="rb_score" class="control-label">Điểm thi:</label>
									</div>
									<div class="col-sm-2">
										<input type="number" class="form-control input-sm" id="tb_score_code" value="" placeholder="Mã thi">
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_code_class">
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-sm-2">
										<input type="radio" id="rb_payment" control-focus="ddl_payment_province" name="select-search-type" class="flat-red">
										<label for="rb_payment" class="control-label">Đã nộp học phí:</label>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="ddl_payment_province">
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
								</div>
							</div>
						</div>
						<div class="row text-center">
							<button type="button" id="bt_search" tn-action="search" class="btn btn-primary btn-search">Search</button>
							<div class="text-light-blue">(Bạn có quyền xem, ban, delete)</div>
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
					<strong class="lb-info-search">1024 bản ghi phù hợp</strong>
				</div>
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
									<tr role="row">
										<!-- <th class="tnw-2">Avatar</th> -->
										<th class="tnw-1">STT</th>
										<th class="tnw-2">ID/Username</th>
										<th class="tnw-3">Tên</th>
										<th class="tnw-3">Lớp/NSinh</th>
										<th class="tnw-3">Email/ĐT/Ngày tạo</th>
										<th class="tnw-4">Địa chỉ</th>
										<!-- <th class="tnw-2">Create at</th>
										<th class="tnw-1">Money</th> -->
										<th class="col-ext hide tnw-2" style="background: #EE0;">Điểm</th>
										<th class="col-ext hide tnw-2" style="background: #EE0;">TGian</th>
										<th class="col-ext hide tnw-2" style="background: #EE0;">Vòng</th>
										<th class="tnw-2">Thi</th>
										<th class="tnw-3">Học phí</th>
										<th class="tnw-3">K.hoạt | T.cấp</th>
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
							<button type="button" tn-action="export" class="btn btn-primary btn-export">export to excel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<ul id="contextMenu" class="dropdown-menu" role="menu" style="display:none" >
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='info'>Xem/Sửa thông tin</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='score'>Xem điểm thi</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='delete-score-cache'>Xóa dữ liệu thi</a></li>
	<li class="divider"></li>
<!--	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='change_password'>Đổi password</a></li>-->
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='delete_avatar'>Xóa avatar</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='delete'>Xóa account</a></li>
<!--	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='ban'>Khóa account</a></li>-->
<!--	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='unban'>Bỏ khóa account</a></li>-->
	<li class="divider"></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='send_email'>Send email</a></li>
	<li class="disabled"><a tabindex="-1" href="javascript:void(0)" tn-ct-action='send_sms'>Send SMS</a></li>
	<li class="divider"></li>
	<li class="disabled"><a tabindex="-1" href="javascript:void(0)" tn-ct-action='log_login'>Nhật ký truy cập</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='log_payment'>Nhật ký nạp tiền điện thoại</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='log_payment_hand'>Nhật ký nạp tiền cộng tay</a></li>
	<li><a tabindex="-1" href="javascript:void(0)" tn-ct-action='add-expire-day'>Cộng ngày học</a></li>
</ul>

<input id="hd_no_image" type="hidden" value="/images/no_photo.jpg">

<div id="modal_detail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="form_detail" role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Xem/Sửa thông tin user</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_edit" class="col-sm-2 control-label">ID:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_id_edit" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_edit" class="col-sm-2 control-label">Username:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_username_edit" value="" placeholder="username" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_name_edit" class="col-sm-2 control-label">Họ tên (<span class="text-red">*</span>):</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_name_edit" placeholder="Họ tên" data-error="Hãy nhập họ tên" required>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_email_edit" class="col-sm-2 control-label">Email:</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="tb_email_edit" placeholder="Email" data-error="Hãy nhập email">
							</div>
						</div>
						<div class="form-group">
							<label for="tb_mobile_edit" class="col-sm-2 control-label">Mobile:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_mobile_edit" maxlength="10" placeholder="Mobile" data-error="Hãy nhập số điện thoại">
								<input type="text" class="form-control" id="tb_mobile_edit_show" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_birthday_edit" class="col-sm-2 control-label">Birthday:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_birthday_edit" placeholder="birthday" required>
							</div>
						</div>
						<div class="form-group">
							<label for="ddl_province_edit" class="col-sm-2 control-label">Province:</label>
							<div class="col-sm-10">
								<select class="form-control" id="ddl_province_edit" required>
									<option value="0">Chọn tỉnh/TP</option>
									<?php
									if(count($list_province)>0){
										foreach ($list_province as $item){
											echo '<option value="'.$item['_id'].'">'.$item['name'].'</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="ddl_district_edit" class="col-sm-2 control-label">District:</label>
							<div class="col-sm-10">
								<select class="form-control" id="ddl_district_edit" required>
									<option value="0">Chọn huyện/thị trấn</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="ddl_school_edit" class="col-sm-2 control-label">School:</label>
							<div class="col-sm-10">
								<select class="form-control" id="ddl_school_edit" required>
									<option value="0">Chọn trường</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_class_name_edit" class="col-sm-2 control-label">Class:</label>
							<div class="col-sm-2">
								<!-- <input type="text" class="form-control" id="tb_class_id_edit" value="" placeholder="class" disabled> -->
								<select class="form-control" id="tb_class_id_edit">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="tb_class_name_edit" value="" placeholder="class">
							</div>
						</div>
						<div class="form-group">
							<label for="cb_active_edit" class="col-sm-2 control-label">Active:</label>
							<div class="col-sm-10">
								<input id="cb_active_edit" type="checkbox" class="flat-red"/>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_create_edit" class="col-sm-2 control-label">Create at:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_create_at_edit" value="" placeholder="create at" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_update_at_edit" class="col-sm-2 control-label">Update at:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_update_at_edit" value="" placeholder="update at" disabled>
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

<div id="modal_change_password" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Đổi password</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_pass_edit" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="tb_id_pass_edit" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_pass_edit" class="col-sm-3 control-label">Username:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="tb_username_pass_edit" value="" placeholder="username" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_password_edit" class="col-sm-3 control-label"> New password:</label>
							<div class="col-sm-9">
								<input type="password" class="form-control" id="tb_password_edit" placeholder="New password" data-error="Hãy nhập mật khẩu" required>
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
									<option value="3">Luyện Tiếng Việt</option>
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
									<th class="tnw-4">Thời gian</th>
									<th class="tnw-4">Mã</th>
									<th class="tnw-4">Ngày thi</th>
									<th class="tnw-4">Action</th>
								</tr>
								</thead>
								<tbody class="tbody"></tbody>
								<tfoot>
								<tr>
									<td colspan="7">
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
							<button type="button" class="btn btn-primary btn-add">Add</button>
							<button type="submit" class="btn btn-primary btn-update">Update</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_score_add" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Thêm điểm thi</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<!-- <div class="form-group">
							<label for="tb_id_score_cache" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_id_score_add" value="0" placeholder="ID" disabled>
							</div>
						</div> -->
						<div class="form-group">
							<label for="ddl_exam_type_cache" class="col-sm-3 control-label">Môn:</label>
							<div class="col-sm-7">
								<select id="ddl_exam_type_add" class="form-control" disabled>
									<option value="4">Tiếng Việt</option>
									<option value="1">Toán</option>
									<option value="2">English</option>
									<option value="3">Luyện Tiếng Việt</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Ngày thi(<span class="text-red">*</span>):</label>
							<div class="col-sm-7">
								<input type="text" id="tb_round_date" maxlength="20" class="form-control bfh-number" data-error="Hãy nhập giá trị" placeholder="Ngày thi" required>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Vòng thi(<span class="text-red">*</span>):</label>
							<div class="col-sm-7">
								<input type="number" id="tb_round_add" maxlength="4" class="form-control bfh-number" min="1" max="19" data-error="Hãy nhập giá trị" placeholder="nhập vòng thi" required>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Điểm(<span class="text-red">*</span>):</label>
							<div class="col-sm-7">
								<input type="number" id="tb_round_score" maxlength="4" class="form-control bfh-number" min="0" max="300" data-error="Hãy nhập giá trị" placeholder="nhập điểm thi" required>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Thời gian thi(<span class="text-red">*</span>):</label>
							<div class="col-sm-7">
								<input type="number" id="tb_round_time" maxlength="4" class="form-control bfh-number" min="1" max="3600" data-error="Hãy nhập giá trị" placeholder="nhập thời gian thi (s)" required>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Lượt thi(<span class="text-red">*</span>):</label>
							<div class="col-sm-7">
								<input type="number" value="1" id="tb_round_luot" maxlength="4" class="form-control bfh-number" min="1" max="1000" data-error="Hãy nhập giá trị" placeholder="nhập lượt thi" required>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Mã thi:</label>
							<div class="col-sm-7">
								<input type="text" id="tb_round_code" maxlength="8" class="form-control bfh-number" data-error="Hãy nhập giá trị" placeholder="Mã thi">
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

<div id="modal_exam_detail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Chi tiết bài thi</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<h5>Id: <span class="user-id" style="font-weight: bold"></span></h5>
						<h5>Vòng: <span class="round-id" style="font-weight: bold"></span></h5>
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab1">Bài 1</a></li>
							<li><a data-toggle="tab" href="#tab2">Bài 2</a></li>
							<li><a data-toggle="tab" href="#tab3">Bài 3</a></li>
						</ul>
						<div class="tab-content">
							<div id="tab1" class="tab-pane fade in active">
								<p>Điểm: <span class="score" style="font-weight: bold"></span></p>
								<p>Thời gian thi: <span class="time" style="font-weight: bold"></span></p>
								<p>Bài thi: <span class="game-name" style="font-weight: bold"></span></p>
								<p>Bắt đầu thi: <span class="created-at" style="font-weight: bold"></span></p>
								<p>Kết thúc thi: <span class="updated-at" style="font-weight: bold"></span></p>
								<div class="row">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
											<th class="tnw-2">STT</th>
											<th class="tnw-5">&nbsp;</th>
											<th class="tnw-5">&nbsp;</th>
											<th class="tnw-6">Ghi chú</th>
											<th class="tnw-4">Đúng/sai</th>
											<th class="tnw-2">Thời gian</th>
										</tr>
										</thead>
										<tbody class="tbody"></tbody>
									</table>
								</div>
							</div>
							<div id="tab2" class="tab-pane fade">
								<p>Điểm: <span class="score" style="font-weight: bold"></span></p>
								<p>Thời gian thi: <span class="time" style="font-weight: bold"></span></p>
								<p>Bài thi: <span class="game-name" style="font-weight: bold"></span></p>
								<p>Bắt đầu thi: <span class="created-at" style="font-weight: bold"></span></p>
								<p>Kết thúc thi: <span class="updated-at" style="font-weight: bold"></span></p>
								<div class="row">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
											<th class="tnw-2">STT</th>
											<th class="tnw-5">&nbsp;</th>
											<th class="tnw-5">&nbsp;</th>
											<th class="tnw-6">Ghi chú</th>
											<th class="tnw-4">Đúng/sai</th>
											<th class="tnw-2">Thời gian</th>
										</tr>
										</thead>
										<tbody class="tbody"></tbody>
									</table>
								</div>
							</div>
							<div id="tab3" class="tab-pane fade">
								<p>Điểm: <span class="score" style="font-weight: bold"></span></p>
								<p>Thời gian thi: <span class="time" style="font-weight: bold"></span></p>
								<p>Bài thi: <span class="game-name" style="font-weight: bold"></span></p>
								<p>Bắt đầu thi: <span class="created-at" style="font-weight: bold"></span></p>
								<p>Kết thúc thi: <span class="updated-at" style="font-weight: bold"></span></p>
								<div class="row">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
											<th class="tnw-2">STT</th>
											<th class="tnw-5">&nbsp;</th>
											<th class="tnw-5">&nbsp;</th>
											<th class="tnw-6">Ghi chú</th>
											<th class="tnw-4">Đúng/sai</th>
											<th class="tnw-2">Thời gian</th>
										</tr>
										</thead>
										<tbody class="tbody"></tbody>
									</table>
								</div>
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
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_score_cache" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Xóa dữ liệu thi lưu tạm</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_score_cache" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_id_score_cache" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_score_cache" class="col-sm-3 control-label">Username:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_username_score_cache" value="" placeholder="username" disabled>
							</div>
						</div>
						<!-- <div class="form-group">
							<label for="tb_connect_cache" class="col-sm-3 control-label">Kết nối:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tawardb_connect_cache" value="" placeholder="số kết nối" disabled>
							</div>
						</div> -->
						<div class="form-group">
							<label for="ddl_exam_type_cache" class="col-sm-3 control-label">Exam type:</label>
							<div class="col-sm-7">
								<select id="ddl_exam_type_cache" class="form-control">
									<option value="4">Tiếng Việt</option>
									<option value="1">Toán</option>
									<option value="2">English</option>
									<option value="3">Cuối Tuần</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_round_cache" class="col-sm-3 control-label">Vòng:</label>
							<div class="col-sm-7">
								<input type="number" id="tb_round_cache" value="1" maxlength="4" class="form-control bfh-number" min="1" max="35" data-error="Hãy nhập giá trị" placeholder="nhập vòng" required>
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
							<!-- <button type="submit" class="btn btn-primary btn-delete-connect">Xóa kết nối</button> -->
							<button type="submit" class="btn btn-primary btn-delete">Xóa dữ liệu</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_payment_detail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Lịch sử nạp thẻ</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_payment_edit" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_id_payment_edit" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_payment_edit" class="col-sm-3 control-label">Username:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_username_payment_edit" value="" placeholder="username" disabled>
							</div>
						</div>
<!--						<div class="form-group">-->
<!--							<label for="ddl_done" class="col-sm-3 control-label">Trạng thái:</label>-->
<!--							<div class="col-sm-7">-->
<!--								ừ-->
<!--							</div>-->
<!--						</div>-->
						<div class="row">
							<table class="table table-bordered table-hover dataTable">
								<thead>
								<tr role="row">
									<th class="tnw-5">Mã thẻ</th>
									<th class="tnw-5">Serial</th>
									<th class="tnw-2">Mạng</th>
									<th class="tnw-2">Trạng thái</th>
									<th class="tnw-3">Số tiền</th>
									<th class="tnw-5">Thời gian</th>
								</tr>
								</thead>
								<tbody class="tbody"></tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<div class="dataTables_info"></div>
							</div>
							<div class="col-sm-7">
								<div class="dataTables_paginate"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-sm-8 message">
							<!-- <label class="modal-message"></label> -->
						</div>
						<div class="col-sm-4 button">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_payment_hand_detail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Lịch sử cộng tay</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_payment_edit" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_id_payment_hand_edit" value="0" placeholder="ID" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_payment_edit" class="col-sm-3 control-label">Username:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_username_payment_hand_edit" value="" placeholder="username" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="ddl_done" class="col-sm-3 control-label">Ngày học đến:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="tb_payment_hand_expire" value="" placeholder="username" disabled>
							</div>
						</div>
						<div class="row">
							<table class="table table-bordered table-hover dataTable">
								<thead>
								<tr role="row">
									<th class="tnw-1">STT</th>
									<th class="tnw-3">Người cộng</th>
									<th class="tnw-3">Số ngày</th>
									<th class="tnw-3">Số tiền</th>
									<th class="tnw-11">Ghi chú</th>
									<th class="tnw-3">Thời gian</th>
								</tr>
								</thead>
								<tbody class="tbody"></tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<div class="dataTables_info"></div>
							</div>
							<div class="col-sm-7">
								<div class="dataTables_paginate"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-sm-8 message">
							<!-- <label class="modal-message"></label> -->
						</div>
						<div class="col-sm-4 button">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_add_vip" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Cộng ngày luyện tập</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="tb_id_pass_edit" class="col-sm-3 control-label">ID:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="tb_id_vip_edit" value="0" placeholder="ID" disabled/>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_username_vip_edit" class="col-sm-3 control-label">Username:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="tb_username_vip_edit" value="" placeholder="username" disabled/>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_vip_day_edit" class="col-sm-3 control-label">Học phí:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="tb_vip_day_edit" value="" placeholder="" disabled/>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_add_day_edit" class="col-sm-3 control-label"> Cộng thêm ngày:</label>
							<div class="col-sm-9">
								<!--<input type="number" id="tb_add_day_edit" value="1" maxlength="4" class="form-control bfh-number" min="-999999" max="999999" data-error="Hãy nhập giá trị" placeholder="Thêm ngày học" required/>-->
								<select class="form-control" id="tb_add_day_edit" required>
									<option selected value="">Chọn ngày học</option>
									<?php
										foreach($GLOBALS['vip_day'] as $index => $item) {
											echo '<option value="' . $index . '">' . $index . 'đ &#8680; ' . $item . ' ngày học </option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_add_day_edit" class="col-sm-3 control-label"> Ghi chú:</label>
							<div class="col-sm-9">
								<textarea id="tb_add_day_note" rows="3" maxlength="1000" class="form-control" data-error="Hãy nhập giá trị" placeholder="Nhập ghi chú" required></textarea>
								<a href="javascript:void(0)" onclick="addNoteVip('Tạm ứng')">Tạm ứng</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('COD')">COD</a> |
								<a href="javascript:void(0)" onclick="addNoteVip(',')">Dấu phẩy</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('.')">Dấu chấm</a> |
								<a href="javascript:void(0)" onclick="addNoteVip(false)">Xóa trắng</a>
								<br/>
								<a href="javascript:void(0)" onclick="addNoteVip('Chuyển khoản')">Chuyển khoản</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Agribank')">Agribank</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Vietcombank')">Vietcombank</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Vietinbank')">Vietinbank</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Techcombank')">Techcombank</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('BIDV')">BIDV</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('VPbank')">VPbank</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('TkCty')">TkCty</a>
								<br/>
								<a href="javascript:void(0)" onclick="addNoteVip('Cộng hộ')">Cộng giúp</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Trần Hiền')">Trần Hiền</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Lê Dung')">Lê Dung</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Trịnh Hương')">Trịnh Hương</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Đặng Hà')">Đặng Hà</a> |
								<a href="javascript:void(0)" onclick="addNoteVip('Mạc Tân')">Mạc Tân</a>
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

<div id="modal_email" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info"><strong>Send email</strong></h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label for="ddl_email_from" class="col-sm-2 control-label">Gửi từ:</label>
							<div class="col-sm-10">
								<select id="ddl_email_from" class="form-control" required>
									<option value="">Chọn người gửi</option>
									<option value="giaovien@trangnguyen.edu.vn">Giáo Viên Trạng Nguyên</option>
									<option value="hotro@trangnguyen.edu.vn">Hỗ Trợ Trạng Nguyên</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_email_to" class="col-sm-2 control-label">Gửi đến:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_email_to" value="" autocomplete="off" placeholder="danh sách email, cách nhau bởi dấu phẩy" required/>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_email_subject" class="col-sm-2 control-label">Tiêu đề:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_email_subject" value="" placeholder="Tiêu đề mail" autocomplete="off" required/>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_add_day_edit" class="col-sm-2 control-label">Nội dung:</label>
							<div class="col-sm-10">
								<input type="text" id="tb_email_body" value="1" maxlength="4" class="form-control bfh-number" autocomplete="off" data-error="Hãy nhập giá trị" placeholder="Nội dung email" required/>
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

<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script src="/dist/js/contextmenu.js"></script>
<script src="/js/user_list.js?v=1.23"></script>