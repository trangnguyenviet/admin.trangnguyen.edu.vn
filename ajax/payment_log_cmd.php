<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/PaymentLogData.php';
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

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'exam',$action)){
		$bok = false;
		switch ($action){
			case 'list':
				$bok = Load_List();
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
	$user_id = isset($_POST['user_id'])? intval($_POST['user_id']): 0;
	$jResponse = [];
	
	if($user_id > 0) {
		$PaymentLogData = PaymentLogData::getInstance();
		$data = $PaymentLogData->GetListByUser($user_id);
		
		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $data['list'],
			'row_count' => $data['count']
		];
	} else {
		$jResponse = [
			'error' => 2,
			'message' => 'chưa chọn user'
		];
	}

	echo json_encode($jResponse);
}