<?php
include './model/ExamEventData.php';
include './model/ExamEventTypeData.php';

$id = isset($_GET['id'])?intval($_GET['id']):0;
$type_id = isset($_GET['type_id'])?intval($_GET['type_id']):0;
$class_id = isset($_GET['class_id'])?intval($_GET['class_id']):0;

$info=null;
if($id>0){
	$ExamEventData = ExamEventData::getInstance();
	$info = $ExamEventData->GetInfo($id);
	$type_id = $info['type_id'];
	$class_id = $info['class_id'];
	$id = $info['_id'];
}
else{
	if($type_id>0 && $class_id>0){
		$ExamEventData = ExamEventData::getInstance();
		$info = $ExamEventData->GetInfo2($type_id, $class_id);
		$id = $info['_id'];
	}
	else{
		echo '<script>var is_exam_info=false;</script>',PHP_EOL;
	}
}

$ExamEventTypeData = ExamEventTypeData::getInstance();
$type_info = $ExamEventTypeData->GetInfo($type_id);

echo '<script>';
echo 'var class_id=' . $class_id . ',type_id='.$type_id.';';
if($info!=null) echo 'var exam_info=' . json_encode($info) . ';',PHP_EOL;
else echo 'var exam_info=null;',PHP_EOL;
echo '</script>';
?>
<section class="content-header">
	<h1>
		Thông tin bài thi
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-xs-6 col-sm-4">
								<label for="tb_test" class="control-label">ID:</label>
								<input class="form-control text-right" id="tb_id" type="text" value="<?php echo $id;?>" disabled/>
							</div>
							<div class="col-xs-6 col-sm-4">
								<label for="tb_round_id" class="control-label">Sự kiện:</label>
								<input class="form-control text-right" value="<?php echo isset($type_info)? "[" . $type_info["_id"] . "] " . $type_info["name"]:"";?>" type="text" disabled/>
								<input type="hidden" id="tb_type_id" value="<?php echo $type_id;?>"/>
							</div>
							<div class="clearfix visible-xs"></div>
							<div class="col-xs-6 col-sm-4">
								<label for="tb_class_id" class="control-label">Lớp:</label>
								<input class="form-control text-right" id="tb_class_id" type="number" disabled/>
							</div>
							<div class="col-xs-6 col-sm-4">
								<label for="tb_play" class="control-label">Số câu thi:</label>
								<input class="form-control text-right" id="tb_play" type="number" value="100" placeholder="Số câu thi" required/>
							</div>
							<div class="clearfix visible-xs"></div>
							<div class="col-xs-6 col-sm-4">
								<label for="tb_time" class="control-label">Thời gian thi (s):</label>
								<input class="form-control text-right" id="tb_time" type="number" value="1800" placeholder="Thời gian thi (s)" required/>
							</div>
							<div class="col-xs-6 col-sm-4">
								<label for="ddl_game" class="control-label">Điểm mỗi câu:</label>
								<input class="form-control text-right" id="tb_spq" type="number" value="10" placeholder="Điểm mỗi câu" required/>
							</div>
						</div>
						<div class="row"><label class="control-label">&nbsp;</label></div>
						<div class="row text-center">
							<button type="button" id="bt_save" tn-action="save" class="btn btn-primary btn-save">Save</button>
							<button type="button" id="bt_import" tn-action="save" class="btn btn-primary btn-save">Import</button>
							<button type="button" id="bt_back" tn-action="back" class="btn btn-primary btn-save">Back</button>
							<div class="text-light-blue">(Bạn có quyền xem, sửa, xóa, thêm)</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Nội dung bài thi:</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal" id="content_tools"></form>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="modal_import" class="modal fade bs-example-modal-lg" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="form" role="form" class="form-horizontal" data-toggle="validator">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-info">
						<strong>Import câu hỏi từ file xlsx</strong>
					</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-2 control-label col-sm-offset-1">Chọn file (xlsx):</label>
						<div class="col-xs-4">
							<input id="file_input" type="file" class="file">
						</div>
					</div>
					<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover dataTable">
									<thead>
									<tr role="row">
										<th class="tnw-1">STT</th>
										<th class="tnw-9">Câu hỏi</th>
										<th class="tnw-3">Đáp án 1</th>
										<th class="tnw-3">Đáp án 2</th>
										<th class="tnw-3">Đáp án 3</th>
										<th class="tnw-3">Đáp án 4</th>
										<th class="tnw-2">Trả lời</th>
									</tr>
									</thead>
									<tbody id="tBody"></tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<button class="btn btn-lg btn-success" type="submit"><i class="fa fa-upload"></i> Import</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>-->
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/game_tools/WebTools.js"></script>
<!--<script src="/js/exam_event_edit.js?v=1.0.3"></script>-->
<script>
	'use strict';
	var ExamEdit;
	var GameTool;
	$(function() {
		var modal_import = $('#modal_import').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		$('#file_input').change(function(e){
			var files = e.target.files;
			var i, f;
			for (i = 0, f = files[i]; i != files.length; ++i) {
				var reader = new FileReader();
				//var name = f.name;
				reader.onload = function (e) {
					var data = e.target.result;

					/* if binary string, read with type 'binary' */
					var result;
					var workbook = XLSX.read(data, { type: 'binary' });

					/* DO SOMETHING WITH workbook HERE */
					workbook.SheetNames.forEach(function (sheetName) {
						var roa = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
						if (roa.length > 0) {
							result = roa;
						}
					});

					setDataImport(result);
				};
				reader.readAsArrayBuffer(f);
			}
		});

		var tBody = $('#tBody');
		var dataImport = null;

		ExamEdit={
			div_content:'#content_tools',
			game_id:0,
			tb_class_id: $('#tb_class_id'),
			tb_id:$('#tb_id'),
			tb_type_id: $('#tb_type_id'),
			tb_play: $('#tb_play'),
			tb_time:$('#tb_time'),
			tb_spq:$('#tb_spq'),
			bt_save:$('#bt_save'),
			bt_import:$('#bt_import'),
			bt_back: $('#bt_back'),
			content_tools:$('#content_tools')
		};

		var query_string = Urllib.GetQueryString();

		if(exam_info){
			ExamEdit.tb_class_id.val(exam_info.class_id);
			ExamEdit.tb_play.val(exam_info.play);
			ExamEdit.tb_time.val(exam_info.time);
			ExamEdit.tb_spq.val(exam_info.spq?exam_info.spq: 10);
		}
		else{
			ExamEdit.tb_class_id.val(query_string['class_id']);
			ExamEdit.tb_play.val(100);
			ExamEdit.tb_time.val(1800);
			ExamEdit.tb_spq.val(10);
		}

		//init data
		ExamEdit.InitTools = function(game_id,div,callback){
			new WEBTOOLS(div,1,null,callback);
		};
		ExamEdit.InitTools(ExamEdit.game_id,ExamEdit.div_content,function(game_tools){
			GameTool = game_tools;
			GameTool.SetData(exam_info);
		});
		//end init data

		//event
		ExamEdit.bt_back.click(function(){
			window.history.go(-1);
		});
		ExamEdit.bt_save.click(function(){
			if(GameTool){
				var validate_info = GameTool.Validate();
				if(validate_info.error !== 0){
					ShowMessError(validate_info.message);
				}
				else{
					var data_info = GameTool.GetData();
					if(validate_info.error === 0){
						ExamEdit.Save(data_info);
					}
				}
			}
			else{
				Alert('Không hỗ trợ game này hoặc đang phát triển!');
			}
		});

		ExamEdit.bt_import.click(function() {
			$('#file_input').val('');
			tBody.empty();
			modal_import.modal('show');
		});

		ExamEdit.Save=function(data_info){
			var data = data_info.data;
			data.id = ExamEdit.tb_id.val();
			data.type_id = ExamEdit.tb_type_id.val();
			data.class_id = ExamEdit.tb_class_id.val();
			//data.round_id = ExamEdit.tb_round_id.val();
			//data.test = ExamEdit.tb_test.val();
			data.time = ExamEdit.tb_time.val();
			data.play = ExamEdit.tb_play.val();
			data.spq = ExamEdit.tb_spq.val();
			data.action='save';
			//console.log(data);
			//return;
			$.ajax({
				url: "/ajax/exam_event_cmd.php",
				type:'POST',
				dataType: "json",
				data:data,
				success:function(data){
					if(data.error === 0){
						ShowMessError('Lưu thành công');
						ExamEdit.tb_id.val(data.id);
					}
					else{
						if(data.error === 5){
							bootbox.alert({
								message:data.message,
								size: 'small',
								callback: function() {
									window.location.href = '/login.php';
								}
							});
						}
					}
				}
			});
		};

		var setDataImport = function(data) {
			tBody.empty();
			dataImport = data;
			if(data && data.length > 0) {
				data.forEach(function(_info, index) {
					var tr = $('<tr>');
					tr.append('<td>' + (index + 1) + '</td>');
					tr.append('<td>' + _info.cauhoi + '</td>');
					tr.append('<td>' + _info.dapan1 + '</td>');
					tr.append('<td>' + _info.dapan2 + '</td>');
					tr.append('<td>' + _info.dapan3 + '</td>');
					tr.append('<td>' + _info.dapan4 + '</td>');
					tr.append('<td>' + _info.traloi + '</td>');
					tBody.append(tr);
				});
			}
		};

		$('#form').submit(function(e){
			e.preventDefault();
			if(dataImport && dataImport.length > 0) {
				for(const _info of dataImport) {
					GameTool.AddItem(1, {
						type: 1,
						question: _info.cauhoi,
						ok: (parseInt(_info.traloi) - 1).toString(),
						answer: [_info.dapan1, _info.dapan2, _info.dapan3, _info.dapan4]
					}, function() {
						//render done
					});
				}
			}

			modal_import.modal('hide');
		});

		ExamEdit.SetLatex = function(text){
			console.log("latex:",text);
		};
	});
</script>