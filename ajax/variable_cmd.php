<?php
require_once '../util/util.php';
require_once './permissionUtil.php';
require_once '../config/config.php';
//require_once '../model/VariableData.php';
//require_once '../model/LogAdminData.php';
////require_once '../model/UserAdminData.php';
header("Content-type: application/json;charset=utf-8");

function __autoload($classname) {
	$filename = "../model/". $classname .".php";
	include_once($filename);
}

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
	if(CheckPermission($admin_info['_id'],'param',$action)){
		$bok = false;
		switch ($action){
			case 'info':
				$bok = Info();
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

function Info(){
	// $bok = false;
	$key = isset($_POST['key'])?$_POST['key']:'';

	$jResponse = [];

	if($key!=''){
		$VariableData = VariableData::getInstance();
		$data = $VariableData->GetInfo($key);

		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'info' => $data
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

function Load_List(){
	// $bok = false;
	$keys = isset($_POST['keys'])?$_POST['keys']:'';
	
	$jResponse = [];
	
	if($keys!=''){
		$keys = explode(',', $keys);
		$VariableData = VariableData::getInstance();
		$data = $VariableData->GetList(
			['$in' => $keys]
		);
		
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
	
	$id = isset($_POST['id'])?$_POST['id']:'';
	$value = isset($_POST['value'])?$_POST['value']:'';
	$type = isset($_POST['type'])?$_POST['type']:'';
	
	if($id!=''){
		if($type=='json'){
			$value = json_decode($value, true);
		}

		$VariableData = VariableData::getInstance();
		$data_save = $VariableData->Save($id, $value);
		
		if($data_save['ok'] == 1 && $data_save['n']>0){
			//$redis->hmset(param_global,[$id=>$value]);//update cache
			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'results' => $data_save
			];

			$redis->publish('update-variable',json_encode([$id => $value]));

			$LogAdminData = LogAdminData::getInstance();
			$LogAdminData->Insert([
				'action' => 'update-variable',
				'username' => $admin_info['username'],
				'ip' => util::GetIpClient(),
				'key' => $id,
				'value' => $value
			]);
		}
		else{
			$jResponse = [
				'error' => 81,
				'message' =>'Dữ liệu chưa thay đổi',
				'results' => $data_save
			];
		}
	}
	
	echo json_encode($jResponse);
	// return $bok;
}

function Delete(){
	global $redis;
	
	// $bOk = false;
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$NewsData = NewsData::getInstance();
		$data_delete = $NewsData->Delete($id);
		if($data_delete['n']>0){
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
			$redis->delete(news_detail.$id);//update cache
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