<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 07/11/2018
 * Time: 09:28
 */

require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/AreaData.php';
require_once '../model/UserAdminData.php';
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

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'lesson',$action)){
		switch ($action){
			case 'list':
				Load_List();
				break;
			case 'save':
				Save($admin_info);
				break;
			case 'delete':
				Delete();
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

function Load_List() {
	$AreaData = AreaData::getInstance();
	$results = $AreaData->GetList();
	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $results['list'],
		'row_count' => $results['count']
	];
	echo json_encode($jResponse);
}

function Save($admin_info){
	$jResponse = [];

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';

	$AreaData = AreaData::getInstance();
	if($id > 0){
		$data_update = $AreaData->Update($id, $name);
		if($data_update['updatedExisting'] && $data_update['n'] > 0) {
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
		$data_insert = $AreaData->Insert($name);
		if($data_insert['_id'] > 0) {
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

	echo json_encode($jResponse);
}

function Delete(){
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$AreaData = AreaData::getInstance();
		$data_delete = $AreaData->Delete($id);
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
			'error' => 80,
			'message' => 'Không có thông tin yêu cầu'
		];
	}
	echo json_encode($jResponse);
}