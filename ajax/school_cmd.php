<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/SchoolData.php';
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

if(isset($_POST['action'])) $action=$_POST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'school',$action)){
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
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$SchoolData = SchoolData::getInstance();
	$list = $SchoolData->GetList($district_id);

	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $list
	];
	echo json_encode($jResponse);
	// return $bok;
}

function Save(){
	global $redis;
	
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';

	$SchoolData = SchoolData::getInstance();
	if($id>0){
		$data_update = $SchoolData->Update($id,$name);
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$redis->delete(sprintf(local_school,$district_id));//update list
			$redis->delete(sprintf(school_info,$id));//update cache

			//update users
			$UserData = UserData::getInstance();
			$result = $UserData->UpdateList(['school_id' => $id], 'school_name', $name);

			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'user_result' => $result
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
		$data_insert = $SchoolData->Insert($district_id,$name);
		if($data_insert['_id']>0){
			$redis->delete(sprintf(local_school,$district_id));//update cache
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
	// return $bok;
}

function Delete(){
	global $redis;
	// $bOk = false;
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	if($id>0){
		$UserData = UserData::getInstance();
		$count_user = $UserData->count(['school_id' => $id]);

		if($count_user==0){
			$SchoolData = SchoolData::getInstance();
			$data_delete = $SchoolData->Delete($id);
			if($data_delete['n']>0){
				$redis->delete(sprintf(local_school,$district_id));//update cache
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
				'error' => 1,
				'message' => "vẫn còn $count_user học sinh trong trường này<br/>Hãy chuyển tất cả học sinh sang trường khác để xóa"
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