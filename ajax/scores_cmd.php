<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ScoreData.php';
require_once '../model/UserData.php';
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
	if(CheckPermission($admin_info['_id'],'score',$action)){
		$bok = false;
		switch ($action){
			case 'list-user':
				Load_Use();
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

function Load_Use(){
	//global $redis;
	$jResponse = [];
	$list_id = isset($_POST['list_id'])?$_POST['list_id']:'';
	$round = isset($_POST['round'])?intval($_POST['round']):0;
	$list_ids = [];
	$arr_id = explode(',', $list_id);
	foreach ($arr_id as $id){
		$id = intval($id);
		array_push($list_ids, $id);
	}

	$ScoreData = ScoreData::getInstance();
	$list = $ScoreData->GetListUse($list_ids, $round);

	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $list
	];
	echo json_encode($jResponse);
}