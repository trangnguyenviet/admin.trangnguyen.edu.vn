<?php
require_once './permissionUtil.php';
require_once '../config/config.php';

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
	$bok = false;
	switch ($action){
		case 'get_permission':
			$bok = Get_Permission($admin_info);
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
		'error' => 80,
		'message' => 'Không có thông tin yêu cầu'
	];
	echo json_encode($jResponse);
}

function Get_Permission($admin_info){
	$page = isset($_POST['page'])?$_POST['page']:'';
	$jResponse = [
		'error' => 0,
		'message' => '',
		'permission' => GetPermission($admin_info['_id'],$page)
	];
	echo json_encode($jResponse);
}