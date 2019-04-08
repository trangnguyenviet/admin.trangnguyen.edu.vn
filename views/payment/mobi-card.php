<section class="content-header">
	<h1>
		Tra cứu thẻ mobile
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
									<label for="tb_id" class="col-sm-2 control-label">Mã thẻ:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="tb_number" value="" placeholder="Nhập mã thẻ để tìm kiếm">
									</div>
								</div>
							</div>
						</div>
						<div class="row text-center">
							<button type="button" id="bt_search" tn-action="search" class="btn btn-primary btn-search">Search</button>
							<!-- <div class="text-light-blue">(Bạn có quyền xem, ban, delete)</div> -->
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
					<!-- <strong class="lb-info-search">1024 bản ghi phù hợp</strong> -->
			</div>
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="tnw-3">User</th>
											<th class="tnw-4">Mã thẻ</th>
											<th class="tnw-4">Serial</th>
											<th class="tnw-3">Mạng</th>
											<th class="tnw-3">Số tiền</th>
											<th class="tnw-2">Trạng thái</th>
											<th class="tnw-5">Thời gian</th>
										</tr>
									</thead>
									<tbody class="tbody"></tbody>
								</table>
							</div>
						</div>
						<!--
						<div class="row">
							<div class="col-sm-5">
								<div class="dataTables_info"></div>
							</div>
							<div class="col-sm-7">
								<div class="dataTables_paginate"></div>
							</div>
						</div>
						 -->
						<!-- <div class="box-footer">
							<button type="button" tn-action="add" class="btn btn-primary btn-add">Thêm</button>
							<div class="text-light-blue">(Bạn có quyền xem, thêm, sửa, xóa)</div>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="/js/mobi-card.js"></script>