<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 12/12/2016
 * Time: 23:11
 */
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ExamAnswerTypeData.php';
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

//$_POST = json_decode(file_get_contents('php://input'),true);
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
	$ExamAnswerTypeData = ExamAnswerTypeData::getInstance();
	$list = $ExamAnswerTypeData->GetList();

	$jResponse = [
		'error' => 0,
		'message' => '',
		'content' => $list
	];
	echo json_encode($jResponse);
	//return $bok;
}

function Save(){
	$id = isset($_POST['_id'])?intval($_POST['_id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';
	$name_ko_dau = isset($_POST['name_ko_dau'])?$_POST['name_ko_dau']:'';
	$date_from = isset($_POST['date_from'])? doubleval($_POST['date_from']):0;
	$date_to = isset($_POST['date_to'])? doubleval($_POST['date_to']):0;
	$active = true;

	$ExamAnswerTypeData = ExamAnswerTypeData::getInstance();
	if($id>0){
		$data_update = $ExamAnswerTypeData->Update($id,$name,$name_ko_dau,$date_from,$date_to,$active);
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$jResponse = [
				'error' => 0,
				'message' =>'thêm mới thành công',
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
			$data_insert = $ExamAnswerTypeData->Insert($name,$name_ko_dau,$date_from,$date_to,$active);
			if($data_insert['_id']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'Update thành công',
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

	echo json_encode($jResponse);
	// return $bok;
}

function Delete(){
	// $bOk = false;
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$ExamAnswerTypeData = ExamAnswerTypeData::getInstance();
		$data_delete = $ExamAnswerTypeData->Delete($id);
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