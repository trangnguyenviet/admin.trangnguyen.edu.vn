<?php
require_once '../util/util.php';
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ParamData.php';
require_once '../model/LogAdminData.php';
//require_once '../model/UserAdminData.php';
header("Content-type: application/json;charset=utf-8");

if(!isset($_SESSION)){
	session_start();
}
//db.students.update( {}, { $rename: { 'create_at': 'created_at'}})
//update db: db.news.update({},{$set:{deleted:false,active:true,create_by:1,created_at:ISODate("2015-10-30T10:10:10.965Z")},{multi:true})
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

function Load_List(){
	// $bok = false;
	$keys = isset($_POST['keys'])?$_POST['keys']:'';
	
	$jResponse = [];
	
	if($keys!=''){
		$keys = explode(',', $keys);
		$ParamData = ParamData::getInstance();
		$data = $ParamData->GetList(
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
	//$type_id = isset($_POST['type_id'])?$_POST['type_id']:'';
	
	if($id!=''){
		$ParamData = ParamData::getInstance();
		$data_update = $ParamData->Save($id, $value);
		
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$redis->hmset(param_global,[$id=>$value]);//update cache
			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'results' => $data_update
			];

			$LogAdminData = LogAdminData::getInstance();
			$LogAdminData->Insert([
				'username' => $admin_info['username'],
				'action' => 'update-param',
				'id' => $value,
				'ip' => util::GetIpClient(),
				'key' => $id,
				'value' => $value
			]);
		}
		else{
			$jResponse = [
				'error' => 81,
				'message' =>'Dữ liệu chưa thay đổi'
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