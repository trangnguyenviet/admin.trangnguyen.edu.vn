<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ExamData.php';
//require_once '../model/UserAdminData.php';
header("Content-type: application/json;charset=utf-8");

if(!isset($_SESSION)){
	session_start();
}
//db.students.update( {}, { $rename: { 'create_at': 'created_at'}})
//update db: db.news.update({},{$set:{deleted:false,active:true,create_by:1,created_at:ISODate("2015-10-30T10:10:10.965Z")},{multi:true})
$jResponse = null;
$admin_info = null;

if(!isset($_SESSION[session_user])){
	require_once './LoginUtil.php';
	if(!ReloginAjax()){
		$jResponse = [
				'error' => 5,
				'message' => 'Đã hết phiên làm việc, bạn hãy đăng nhập lại'
		];
		echo json_encode($jResponse);
		die();
	}
}

$admin_info=$_SESSION[session_user];

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'exam',$action)){
		$bok = false;
		switch ($action){
			case 'info':
				$bok = Load_Info();
				break;
			case 'list':
				$bok = Load_List();
				break;
			case 'save':
				$bok = Save($admin_info);
				break;
			case 'delete':
				$bok = Delete();
				break;
			case 'exists':
				$bok = Exists($admin_info);
				break;
			case 'copy':
				$bok = CopyData($admin_info);
				break;
			default:
				$jResponse = [
					'error' => 81,
					'message' => 'Request not correct'
				];
				echo json_encode($jResponse);
				break;
		}
	}
	else{
		$jResponse = [
			'error' => 81,
			'message' => 'Bạn không có quyền thao tác hành động này'
		];
		echo json_encode($jResponse);
	}
}
else{
	$jResponse = [
		'error' => 80,
		'message' => 'Không có thông tin yêu cầu'
	];
	echo json_encode($jResponse);
}

function Load_Info(){
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$jResponse = [];
	$ExamData = ExamData::getInstance();
	$info = $ExamData->GetInfo($id);
	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'info' => $info
	];
	echo json_encode($jResponse);
}

function Load_List(){
	// $bok = false;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	
	$jResponse = [];
	
	if($type_id>0 && $class_id>0){
		$ExamData = ExamData::getInstance();
		$data = $ExamData->GetListShow($class_id, $type_id);
		
		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $data
		];
	}
	else{
		$jResponse = [
			'error' => 2,
			'message' => 'Chưa chọn danh mục'
		];
	}
	echo json_encode($jResponse);
	// return $bok;
}

function Save($admin_info){
// 	var_dump($admin_info);
	global $redis;
	$jResponse = [];
	
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$answers = isset($_POST['answers'])?$_POST['answers']:[];
	$content = isset($_POST['content'])?$_POST['content']:[];
	$time = isset($_POST['time'])?intval($_POST['time']):0;
	$game_id = isset($_POST['game_id'])?intval($_POST['game_id']):0;
	
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	$test = isset($_POST['test'])?intval($_POST['test']):0;
	
	//game chuot
	if($game_id==1){
		if(count($content)>0){
			$length = count($content);
			for($i=0;$i<$length;$i++){
				if($content[$i]['is_noisy']){
					$content[$i]['is_noisy']=$content[$i]['is_noisy']=='true';
				}
			}
		}
	}
	
	$ExamData = ExamData::getInstance();
	if($id>0){
		$data_update = $ExamData->Update($id,$game_id, $time, $answers, $content);
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$redis->delete(sprintf(exam_info,$type_id, $class_id, $round_id, $test));//update cache
			$redis->delete(sprintf("exam_x_%d_%d_%d_%d",$type_id, $class_id, $round_id, $test));//update cache baigiang
			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'id' => $id
			];
		}
		else{
			$jResponse = [
				'error' => 81,
				'message' =>'Dữ liệu chưa thay đổi'
			];
		}
	}
	else{
		$exam_info = $ExamData->GetInfo2($type_id, $class_id, $round_id, $test);
		if(isset($exam_info)){
			$id = intval($exam_info['_id']);
			$data_update = $ExamData->Update($id,$game_id, $time, $answers, $content);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$redis->delete(sprintf(exam_info,$type_id, $class_id, $round_id, $test));//update cache
				$redis->delete(sprintf("exam_x_%d_%d_%d_%d",$type_id, $class_id, $round_id, $test));//update cache
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'id' => $id
				];
			}
			else{
				$jResponse = [
					'error' => 81,
					'message' =>'Dữ liệu chưa thay đổi'
				];
			}
		}
		else{
			$create_by = $admin_info['_id'];
			$data_insert = $ExamData->Insert($type_id, $class_id, $game_id, $round_id, $test, $time, $answers, $content);
			if($data_insert['_id']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'id' => $data_insert['_id']
				];
			}
			else{
				$jResponse = [
					'error' => 82,
					'message' =>'Insert không thành công'
				];
			}
		}
	}
	//var_dump($_POST);
	
	echo json_encode($jResponse);
	// return $bok;
}

function Delete(){
	global $redis;
	
	// $bOk = false;
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$ExamData = ExamData::getInstance();
		$exam_info = $ExamData->GetInfo($id);
		if(isset($exam_info)){
			$type_id = intval($exam_info['type_id']);
			$class_id = intval($exam_info['class_id']);
			$round_id = intval($exam_info['round_id']);
			$test = intval($exam_info['test']);
			
			$redis->delete(sprintf(exam_info,$type_id, $class_id, $round_id, $test));//update cache
			$redis->delete(sprintf("exam_x_%d_%d_%d_%d",$type_id, $class_id, $round_id, $test));//update cache
			
			$data_delete = $ExamData->Delete($id);
			if($data_delete['n']>0){
				$jResponse = [
					'error' => 0,
					'message' => 'ok'
				];
			}
			else{
				$jResponse = [
					'error' => 83,
					'message' => 'Dữ liệu chưa thay đổi'
				];
			}
		}
		else{
			$jResponse = [
				'error' => 83,
				'message' => 'Dữ liệu chưa thay đổi'
			];
		}
	}
	else{
		$jResponse = [
			'error' => 80,
			'message' => 'Chưa có tham số id'
		];
	}
	echo json_encode($jResponse);
	// return $bOk;
}

function Exists($admin_info){
	//var_dump($admin_info);
	//global $redis;
	$jResponse = [];
	
	//$id = isset($_POST['id'])?intval($_POST['id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	$test = isset($_POST['test'])?intval($_POST['test']):0;
	
	$ExamData = ExamData::getInstance();
	$data_id = $ExamData->GetId($type_id, $class_id, $round_id, $test);
	//var_dump($data_id);
	if(isset($data_id)){
		$jResponse = [
			'error' => 0,
			'message' =>'ok',
			'id' => $data_id['_id'],
			'exists' => true
		];
	}
	else{
		$jResponse = [
			'error' => 0,
			'message' =>'ok',
			'id' => 0,
			'exists' => false
		];
	}
	
	echo json_encode($jResponse);
	// return $bok;
}

function CopyData($admin_info){
	//var_dump($admin_info);
	global $redis;
	$jResponse = [];
	
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$exists_id = isset($_POST['exists_id'])?intval($_POST['exists_id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	$test = isset($_POST['test'])?intval($_POST['test']):0;
	
	$ExamData = ExamData::getInstance();
	
	$data_copy = $ExamData->CopyData($id,$type_id, $class_id, $round_id, $test);
	if($data_copy){
		$jResponse = [
			'error' => 0,
			'message' =>'ok'
		];
		if($exists_id>0) $data_delete = $ExamData->Delete($exists_id);
		$redis->delete(sprintf(exam_info,$type_id, $class_id, $round_id, $test));//update cache
		$redis->delete(sprintf("exam_x_%d_%d_%d_%d",$type_id, $class_id, $round_id, $test));//update cache
	}
	else{
		$jResponse = [
			'error' => 0,
			'message' =>'copy không thành công'
		];
	}
	
	echo json_encode($jResponse);
	// return $bok;
}