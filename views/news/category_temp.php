<!--<script src="/dist/js/validator.min.js"></script>-->
<!--Content Header (Page header) -->
<section class="content-header">
	<h1>
		Danh mục tin tức
		<small></small>
	</h1>
	<!-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Tables</a></li>
		<li class="active">Data tables</li>
	</ol> -->
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<!-- <div class="box-header">
					<h3 class="box-title">Hover Data Table</h3>
				</div> -->
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
										<tr role="row">
											<th class="col-xs-1">ID</th>
											<th class="col-xs-4">Name</th>
											<th class="col-xs-2">Parent name</th>
											<th class="col-xs-3">Rewrite</th>
											<th class="col-xs-1">Sort</th>
											<th class="col-xs-2">Action</th>
									</thead>
									<tbody>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>
												<a href="javascript:void(0);">Edit</a> | <a href="javascript:void(0);">Delete</a>
											</td>
										</tr>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>B</td>
										</tr>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>B</td>
										</tr>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>B</td>
										</tr>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>B</td>
										</tr>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>B</td>
										</tr>
										<tr>
											<td>Gecko</td>
											<td>Firefox 1.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>1.7</td>
											<td>A</td>
											<td>B</td>
										</tr>
									</tbody>
									<!-- <tfoot></tfoot> -->
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<div class="dataTables_info">Từ 1 đến 10 của 57 bản ghi</div>
							</div>
							<div class="col-sm-7">
								<div class="dataTables_paginate">
									<ul class="pagination">
										<li><a href="#" data-dt-idx="0">&lt;&lt;</a></li>
										<li><a href="#" data-dt-idx="0">&lt;</a></li>
										<li class="active"><a href="#" data-dt-idx="1">1</a></li>
										<li><a href="#" data-dt-idx="2">2</a></li>
										<li><a href="#" data-dt-idx="3">3</a></li>
										<li><a href="#" data-dt-idx="4">4</a></li>
										<li><a href="#" data-dt-idx="5">5</a></li>
										<li><a href="#" data-dt-idx="7">&gt;</a></li>
										<li><a href="#" data-dt-idx="7">&gt;&gt;</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="button" class="btn btn-primary">Thêm</button>
							<button type="button" class="btn btn-primary">Xóa</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="/js/category.js"></script>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="form" role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
					
						<div class="box-body">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
								<div class="col-sm-10">
									<input type="email" class="form-control" id="inputEmail3" placeholder="Email" data-error="Bruh, that email address is invalid" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-2 control-label">Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="inputPassword3" placeholder="Password">
								</div>
							</div>
							<!-- <div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox"> Remember me
										</label>
									</div>
								</div>
							</div> -->
						</div>
						<!-- <div class="box-footer">
							<button type="submit" class="btn btn-default">Cancel</button>
							<button type="submit" class="btn btn-info pull-right">Sign in</button>
						</div> -->
					<!-- <div class="row">
						<div class="col-md-4">.col-md-4</div>
						<div class="col-md-4 col-md-offset-4">.col-md-4 .col-md-offset-4</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-md-offset-3">.col-md-3 .col-md-offset-3</div>
						<div class="col-md-2 col-md-offset-4">.col-md-2 .col-md-offset-4</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-md-offset-3">.col-md-6 .col-md-offset-3</div>
					</div>
					<div class="row">
						<div class="col-sm-9">
							Level 1: .col-sm-9
							<div class="row">
								<div class="col-xs-8 col-sm-6">
									Level 2: .col-xs-8 .col-sm-6
								</div>
								<div class="col-xs-4 col-sm-6">
									Level 2: .col-xs-4 .col-sm-6
								</div>
							</div>
						</div>
					</div> -->
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="myModal-2" class="modal fade bs-example-modal-lg" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modal Header</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4">.col-md-4</div>
					<div class="col-md-4 col-md-offset-4">.col-md-4 .col-md-offset-4</div>
				</div>
				<div class="row">
					<div class="col-md-3 col-md-offset-3">.col-md-3 .col-md-offset-3</div>
					<div class="col-md-2 col-md-offset-4">.col-md-2 .col-md-offset-4</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">.col-md-6 .col-md-offset-3</div>
				</div>
				<div class="row">
					<div class="col-sm-9">
						Level 1: .col-sm-9
						<div class="row">
							<div class="col-xs-8 col-sm-6">
								Level 2: .col-xs-8 .col-sm-6
							</div>
							<div class="col-xs-4 col-sm-6">
								Level 2: .col-xs-4 .col-sm-6
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="myModal-3" class="modal fade bs-example-modal-sm" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modal Header</h4>
			</div>
			<div class="modal-body">
				<p>Some text in the modal.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>