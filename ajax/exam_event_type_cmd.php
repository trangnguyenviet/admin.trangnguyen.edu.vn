<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ExamEventTypeData.php';
require_once '../util/util.php';
header("Content-type: application/json;charset=utf-8");

if(!isset($_SESSION)){
	session_start();
}

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

if(isset($_POST['action'])) $action=$_POST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'lesson_type',$action)){
		$bok = false;
		switch ($action){
			case 'list':
				$bok = Load_List();
				break;
			case 'save':
				$bok = Save();
				break;
			case 'delete':
				$bok = Delete();
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

function Load_List(){
	// $bok = false;
	$jResponse = [];
	$ExamEventTypeData = ExamEventTypeData::getInstance();
	$list = $ExamEventTypeData->GetList();

	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $list
	];
	echo json_encode($jResponse);
	// return $bok;
}

function Save(){
	global $redis;

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';
	$name_ko_dau = isset($_POST['name_ko_dau'])?$_POST['name_ko_dau']:'';
	$time_begin = isset($_POST['time_begin'])?strtotime($_POST['time_begin']):0;
	$time_end = isset($_POST['time_end'])?strtotime($_POST['time_end']):0;
	$type = isset($_POST['type'])?intval($_POST['type']):0;
	$active = isset($_POST['active'])?$_POST['active']=='true': false;
	$name = util::ReplaceHTML($name);

	$ExamEventTypeData = ExamEventTypeData::getInstance();
	$exist = $ExamEventTypeData->CheckExistName($name,$id);
	if($exist==0){
		if($id>0){
			$data_update = $ExamEventTypeData->Update($id,$name,$name_ko_dau,$time_begin,$time_end,$type,$active);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$redis->delete(sprintf("exam_event_info_%d", $id));//update cache
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'id' => $id
				];
				//
			}
			else{
				$jResponse = [
					'error' => 81,
					'message' =>'Dữ liệu chưa thay đổi'
				];
			}
		}
		else{
			$data_insert = $ExamEventTypeData->Insert($name,$name_ko_dau,$time_begin,$time_end,$type,$active);
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
	else{
		$jResponse = [
			'error' => 1,
			'message' =>'Tên đã tồn tại'
		];
	}

	echo json_encode($jResponse);
	// return $bok;
}

function Delete(){
	global $redis;
	// $bOk = false;
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>=0){
		$ExamEventTypeData = ExamEventTypeData::getInstance();
		$data_delete = $ExamEventTypeData->Delete($id);
		if($data_delete['n']>0){
			$redis->delete(sprintf("exam_event_info_%d", $id));//update cache
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
			// $bOk=true;
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
			'message' => 'Không có thông tin yêu cầu'
		];
	}
	echo json_encode($jResponse);
	// return $bOk;
}