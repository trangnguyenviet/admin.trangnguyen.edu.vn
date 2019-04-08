<?php
	include './model/ExamData.php';
	
	$id = isset($_GET['id'])?intval($_GET['id']):0;
	$info=null;
	if($id>0){
		$ExamData = ExamData::getInstance();
		$info = $ExamData->GetInfo($id);
	}
	else{
		$type_id = isset($_GET['type_id'])?intval($_GET['type_id']):0;
		$class_id = isset($_GET['class_id'])?intval($_GET['class_id']):0;
		$round_id = isset($_GET['round_id'])?intval($_GET['round_id']):0;
		$test_id = isset($_GET['test_id'])?intval($_GET['test_id']):0;
		if($type_id>0 && $class_id>0 && $round_id>0 && $test_id>0){
			$ExamData = ExamData::getInstance();
			$info = $ExamData->GetInfo2($type_id, $class_id, $round_id, $test_id);
			echo '<script>var type_id=' . $type_id . ',class_id=' . $class_id . ',round_id='. $round_id .',test_id='. $test_id .';</script>',PHP_EOL;
		}
		else{
			echo '<script>var is_exam_info=false;</script>',PHP_EOL;
		}
	}
	
	//if($info!=null) echo '<script>var exam_info=' . json_encode($info,JSON_FORCE_OBJECT) . ';</script>',PHP_EOL;
	if($info!=null) echo '<script>var exam_info=' . json_encode($info) . ';</script>',PHP_EOL;
	else echo '<script>var exam_info=null;</script>',PHP_EOL;
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
				<!-- <div class="box-header">
					<h3 class="box-title">Thông tin bài thi:</h3>
				</div> -->
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-xs-6 col-sm-3">
								<label for="tb_test" class="control-label">ID:</label>
								<input class="form-control text-right" id="tb_id" type="text" value="0" disabled/>
							</div>
							<div class="col-xs-6 col-sm-3">
								<label for="ddl_exam_type" class="control-label">Môn học:</label>
								<select id="ddl_exam_type" class="form-control" disabled>
									<option value="4">Tiếng Việt</option>
									<option value="1">Toán</option>
									<option value="2">English</option>
									<option value="3">Luyện Tiếng Việt</option>
									<option value="5">Toán bằng tiếng anh</option>
									<option value="6">Tự nhiên xã hội</option>
									<option value="7">Lịch sử địa lý</option>
									<option value="8">Âm nhạc - mỹ thuật</option>
									<option value="9">IQ thông minh</option>
									<option value="10">Trắc nghiệm vui</option>
								</select>
							</div>
							<div class="clearfix visible-xs"></div>
							<div class="col-xs-6 col-sm-3">
								<label for="tb_class_id" class="control-label">Lớp:</label>
								<input class="form-control text-right" id="tb_class_id" type="text" disabled/>
							</div>
							<div class="col-xs-6 col-sm-3">
								<label for="tb_round_id" class="control-label">Vòng thi:</label>
								<input class="form-control text-right" id="tb_round_id" type="text" disabled/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 col-sm-3">
								<label for="tb_test" class="control-label">Bài thi:</label>
								<input class="form-control text-right" id="tb_test" type="text" value="" disabled/>
							</div>
							<div class="col-xs-6 col-sm-3">
								<label for="tb_play" class="control-label">Số câu thi:</label>
								<input class="form-control text-right" id="tb_play" type="text" value="10" disabled/>
							</div>
							<div class="clearfix visible-xs"></div>
							<div class="col-xs-6 col-sm-3">
								<label for="tb_time" class="control-label">Thời gian thi (s):</label>
								<input class="form-control text-right" id="tb_time" type="text" value="1200"/>
							</div>
							<div class="col-xs-6 col-sm-3">
								<label for="ddl_game" class="control-label">Game:</label>
								<select class="form-control" id="ddl_game">
								<?php
									foreach($GLOBALS['list_game_info'] as $index => $item){
										echo '<option value="' . $index . '" ' . ($item['enable']? '': 'disabled') . '>' . $item['game_name'] . '</option>';
									}
								?>
								</select>
							</div>
						</div>
						<div class="row"><label class="control-label">&nbsp;</label></div>
						<div class="row text-center">
							<button type="button" id="bt_save" tn-action="save" class="btn btn-primary btn-save">Save</button> 
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

<?php
	//document: http://php.net/manual/en/function.json-encode.php
	//echo '<script>var total_round="'.total_round_js.'",current_round="'.current_round_js.'",game_type=' . json_encode($GLOBALS['list_game_info'],JSON_FORCE_OBJECT) . ';</script>',PHP_EOL;
	echo '<script>var total_round="'.total_round_js.'",current_round="'.current_round_js.'";</script>',PHP_EOL;
?>
<input type="hidden" id="tb_abc"/>
<script src="/js/latex.js"></script>
<!--<script type="text/javascript" src="http://latex.codecogs.com/editor3.js"></script>-->
<script src="/ckfinder/ckfinder.js"></script>
<script src="/js/game_tools/WebTools.js"></script>
<script src="/js/game_tools/ChuotTools.js"></script>
<script src="/js/game_tools/HoTools.js"></script>
<script src="/js/game_tools/MeoTools.js"></script>
<script src="/js/game_tools/TrauTools.js"></script>
<script src="/js/game_tools/GaTools.js"></script>
<script src="/js/game_tools/NguaTools.js"></script>
<script src="/js/exam_edit.js?v=1.0.0"></script>