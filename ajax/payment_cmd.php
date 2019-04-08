<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/PaymentData.php';
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
			case 'search':
				$bok = Search();
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
	$user_id = isset($_POST['user_id'])?intval($_POST['user_id']):0;
	$page_index = isset($_POST['page_index'])?intval($_POST['page_index']):0;
	$done_status = isset($_POST['done_status'])?$_POST['done_status']:'';
	$jResponse = [];
	
	if($user_id>0){
		$PaymentData = PaymentData::getInstance();
		$data = $PaymentData->GetList($user_id, page_size_user_payment, $page_index,$done_status);
		
		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $data['list'],
			'row_count' => $data['count'],
			'page_index' => $page_index,
			'page_size' => page_size_user_payment,
		];
	}
	else{
		$jResponse = [
			'error' => 2,
			'message' => 'chưa chọn user'
		];
	}
	echo json_encode($jResponse);
	// return $bok;
}

function Search(){
	// $bok = false;
	$number = isset($_POST['number'])?$_POST['number']:'';
	$jResponse = [];
	
	if($number!=''){
		$PaymentData = PaymentData::getInstance();
		$data = $PaymentData->Search($number);
		
		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $data['list']
		];
	}
	else{
		$jResponse = [
			'error' => 2,
			'message' => 'hãy nhập mã thẻ'
		];
	}
	echo json_encode($jResponse);
}