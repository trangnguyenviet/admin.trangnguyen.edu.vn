<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/LessonTypeData.php';
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
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$LessonTypeData = LessonTypeData::getInstance();
	$list = $LessonTypeData->GetList($class_id);

	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $list
	];
	echo json_encode($jResponse);
	// return $bok;
}

function Save(){
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';
	$name_ko_dau = isset($_POST['name_ko_dau'])?$_POST['name_ko_dau']:'';
	$clas_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$sort = isset($_POST['sort'])?intval($_POST['sort']):0;
	$active = true;

	$LessonTypeData = LessonTypeData::getInstance();
	$exist = $LessonTypeData->CheckExistName($name,$id);
	if($exist==0){
		if($id>0){
			$data_update = $LessonTypeData->Update($id,$name,$name_ko_dau,$active,$sort);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$jResponse = [
						'error' => 0,
						'message' =>'ok'
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
			$data_insert = $LessonTypeData->Insert($clas_id,$name,$name_ko_dau,$active,$sort);
			if($data_insert['_id']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok'
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
	// $bOk = false;
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$LessonTypeData = LessonTypeData::getInstance();
		$data_delete = $LessonTypeData->Delete($id);
		if($data_delete['n']>0){
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