<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 11/12/2016
 * Time: 22:44
 */
	include './model/ExamAnswersData.php';
	include './model/ExamAnswerTypeData.php';

	$id = isset($_GET['id'])?intval($_GET['id']):0;
	$type_id = isset($_GET['type_id'])?intval($_GET['type_id']):0;
	$class_id = isset($_GET['class_id'])?intval($_GET['class_id']):0;

	$info=null;
	if($id>0){
		$ExamAnswersData = ExamAnswersData::getInstance();
		$info = $ExamAnswersData->GetInfo($id);
		$type_id = $info['type_id'];
		$class_id = $info['class_id'];
		$id = $info['_id'];
	}
	else{
		if($type_id>0 && $class_id>0){
			$ExamAnswersData = ExamAnswersData::getInstance();
			$info = $ExamAnswersData->GetInfo2($type_id, $class_id);
			$id = $info['_id'];
		}
		else{
			echo '<script>var is_exam_info=false;</script>',PHP_EOL;
		}
	}

	$ExamAnswerTypeData = ExamAnswerTypeData::getInstance();
	$type_info = $ExamAnswerTypeData->GetInfo($type_id);

	echo '<script>';
	echo 'var class_id=' . $class_id . ',type_id='.$type_id.';';
	if($info!=null) echo 'var exam_info=' . json_encode($info) . ';',PHP_EOL;
	else echo 'var exam_info=null;',PHP_EOL;
	echo '</script>';
?>
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckfinder/ckfinder.js"></script>
<script src="/plugins/ng-ckeditor/src/scripts/02-directive.js"></script>
<link rel="stylesheet" href="/plugins/ng-ckeditor/ng-ckeditor.css">
<section class="content-header">
	<h1>
		Thông tin bài thi
		<small></small>
	</h1>
</section>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-body">
						<form class="form-horizontal">
							<div class="row">
								<div class="col-xs-6 col-sm-4">
									<label for="tb_test" class="control-label">ID:</label>
									<input class="form-control text-right" ng-model="_id" id="tb_id" type="text" value="<?php echo $id;?>" disabled/>
								</div>
								<div class="col-xs-6 col-sm-4">
									<label for="tb_round_id" class="control-label">Sự kiện:</label>
									<input class="form-control text-right" value="<?php echo isset($type_info)? $type_info["name"]:"";?>" type="text" disabled/>
									<input type="hidden" ng-model="type_id" value="<?php echo $type_id;?>"/>
								</div>
								<div class="clearfix visible-xs"></div>
								<div class="col-xs-6 col-sm-4">
									<label for="tb_class_id" class="control-label">Lớp:</label>
									<input class="form-control text-right" ng-value="class_id" type="text" disabled/>
								</div>
								<div class="col-xs-6 col-sm-4">
									<label for="tb_play" class="control-label">Số câu thi:</label>
									<input class="form-control text-right" ng-model="play" type="number" min="1" value="100" required/>
								</div>
								<div class="clearfix visible-xs"></div>
								<div class="col-xs-6 col-sm-4">
									<label for="tb_time" class="control-label">Thời gian thi (s):</label>
									<input class="form-control text-right" ng-model="time" type="number" min="1" value="1800" required/>
								</div>
								<div class="col-xs-6 col-sm-4">
									<label for="ddl_game" class="control-label">Game:</label>
									<input class="form-control text-right" type="text" value="Trắc nghiệm" disabled/>
									</select>
								</div>
							</div>
							<div class="row"><label class="control-label">&nbsp;</label></div>
							<div class="row text-center">
								<button type="button" id="bt_save" ng-click="save()" class="btn btn-primary">Save</button>
								<button type="button" id="bt_back" ng-click="back()" class="btn btn-primary">Back</button>
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
						<form class="form-horizontal">
							<div class="questions">
								<div ng-show="isReady" ng-repeat="info in content track by $index " class="well well-sm questions-item">
									<div class="form-group">
										<label class="col-sm-1 control-label">Câu hỏi {{$index+1}}:</label>
										<div class="col-xs-11" ng-cloak>
											<textarea ckeditor="editorQuestion" ng-model="info.question" rows="4" class="form-control" placeholder="Nội dung câu hỏi"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">Đáp án:</label>
										<div class="col-xs-11">
											<div class="col-xs-6 col-sm-3">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answer"  name="s{{$index}}" value="0" type="radio"></span>
													<input ng-model="info.answers[0]" class="form-control input-sm" type="text" placeholder="Đáp án 1">
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answer" name="s{{$index}}" value="1" type="radio"></span>
													<input ng-model="info.answers[1]" class="form-control input-sm" type="text" placeholder="Đáp án 2">
												</div>
											</div>
											<div class="clearfix visible-xs"></div>
											<div class="col-xs-6 col-sm-3">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answer" name="s{{$index}}" value="2" type="radio"></span>
													<input ng-model="info.answers[2]" class="form-control input-sm" type="text" placeholder="Đáp án 3">
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
												<div class="input-group">
													<span class="input-group-addon"><input ng-model="info.answer" name="s{{$index}}" value="3" type="radio"></span>
													<input ng-model="info.answers[3]" class="form-control input-sm" type="text" placeholder="Đáp án 4">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">Giả đáp:</label>
										<div class="col-xs-11" ng-cloak>
											<textarea ckeditor="editorTheAnswer"  ng-model="info.the_answer" class="form-control" rows="4" placeholder="Hướng dẫn giải"></textarea>
										</div>
									</div>
									<div class="row text-center">
										<div class="col-xs-12"><a ng-click="delete($index)" class="btn btn-danger"><i class="fa fa-remove"></i> Xóa câu hỏi {{$index+1}}</a></div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="box-footer text-center">
						<div class="row">
							Số câu hỏi: {{count}}/{{play}}
						</div>
						<div class="row">
							<button class="btn btn-success" ng-click="add()" type="button"><i class="fa fa-plus"></i> Thêm câu hỏi</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="/js/exam_answer_edit.js?v=1.0.0"></script>