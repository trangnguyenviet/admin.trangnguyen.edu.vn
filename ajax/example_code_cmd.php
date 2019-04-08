<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 18/01/2017
 * Time: 00:53
 */
require_once '../util/util.php';
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ExampleCodeData.php';
//require_once '../model/UserAdminData.php';
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
	if(CheckPermission($admin_info['_id'],'exam',$action)){
		$bok = false;
		switch ($action){
			case 'list':
				$bok = Load_List();
				break;
			case 'save':
				$bok = Save($admin_info);
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
	$type = isset($_POST['type'])?$_POST['type']:'';
	$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$school_id = isset($_POST['school_id'])?intval($_POST['school_id']):0;

	$jResponse = [];

	if($type!=''){
		$ExampleCodeData = ExampleCodeData::getInstance();
		$list = $ExampleCodeData->GetList($type,$province_id, $district_id, $school_id);

		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $list
		];
	}
	else{
		$jResponse = [
			'error' => 2,
			'message' => 'Chưa chọn danh mục'
		];
	}
	echo json_encode($jResponse);
}

function Save($admin_info){
	global $redis;
	$jResponse = [];

	$id = isset($_POST['_id'])?$_POST['_id']:'';
	$type = isset($_POST['type'])?$_POST['type']:'free';
	$active = isset($_POST['active'])?$_POST['active']=='true':true;
	$begin_use = isset($_POST['begin_use'])?strtotime($_POST['begin_use']):0;
	$end_use = isset($_POST['end_use'])?strtotime($_POST['end_use']):0;

	$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$school_id = isset($_POST['school_id'])?intval($_POST['school_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;

	if($begin_use == 0 || $end_use == 0 || $class_id == 0) {
		$jResponse = [
			'error' => 1,
			'message' => 'Hãy nhập đầy đủ thông tin'
		];
	} else {
		$ExampleCodeData = ExampleCodeData::getInstance();
		if($id!=''){
			$date = new DateTime();
			$updated_at = $date->getTimestamp();
			$data_update = $ExampleCodeData->Update($id, $begin_use, $end_use, $active, $updated_at, $class_id);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'code' => $id
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
			//insert
			$code = util::RandomNumber(6);
			$date = new DateTime();
			$created_at = $updated_at = $date->getTimestamp();
			$data_insert = $ExampleCodeData->Insert($code,$type,$province_id, $district_id, $school_id, $class_id,$begin_use, $end_use, $admin_info['username'], $created_at, $updated_at, $active);

			if($data_insert['ok']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'code' => $code
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

	echo json_encode($jResponse);
}