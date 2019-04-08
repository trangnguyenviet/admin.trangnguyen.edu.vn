<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/CategoryData.php';
header("Content-type: application/json;charset=utf-8");

	// $CategoryData = CategoryData::getInstance();
	//$list = $CategoryData->GetList();
	// foreach ($list as $doc) {
	// 	var_dump($doc);
	// }
	//echo $list->count();
	//echo json_encode(iterator_to_[$list));
	// echo json_encode($list);
	//$NextIdObj = $CategoryData->GetNextId();
	//var_dump($NextIdObj['retval']);
	// echo $CategoryData->GetNextId();

	// $one = $CategoryData->GetInfo(14);
	// var_dump($one);

	// $exist = $CategoryData->CheckExist('tin nóng');
	// var_dump($exist);

	// $insert = $CategoryData->Insert('tin nóng',0,'','tin-nong',true,999);
	// var_dump($insert);

	// $update = $CategoryData->Update(14,'tin nguội',0,'','tin-nguoi',true,999);
	// var_dump($update);

	// $delete = $CategoryData->Delete(11);
	// var_dump($delete);


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
	if(CheckPermission($admin_info['_id'],'category',$action)){
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
	 $bok = false;
	$jResponse = [];
	
	$CategoryData = CategoryData::getInstance();
	$list = $CategoryData->GetList();

	$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $list
	];
	echo json_encode($jResponse);
    return $bok;
}

function Save(){
    $bok = false;

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';
	$name_ko_dau = isset($_POST['name_ko_dau'])?$_POST['name_ko_dau']:'';
	$parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
	$parent_name = isset($_POST['parent_name'])?$_POST['parent_name']:'';
	$sort = isset($_POST['sort'])?intval($_POST['sort']):0;
	$active = true;

	$CategoryData = CategoryData::getInstance();
	$exist = $CategoryData->CheckExistName($name,$id);
	if($exist==0){
		if($id>0){
			$data_update = $CategoryData->Update($id,$name,$parent_id,$parent_name,$name_ko_dau,$active,$sort);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok'
				];
				$bok = true;
			}
			else{
				$jResponse = [
					'error' => 81,
					'message' =>'Dữ liệu chưa thay đổi'
				];
			}
		}
		else{
			$data_insert = $CategoryData->Insert($name,$parent_id,$parent_name,$name_ko_dau,$active,$sort);
			if($data_insert['_id']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok'
				];
				$bok = true;
			}
			else{
				$jResponse = [
					'error' => 82,
					'message' =>'Insert không thành công'
				];
			}
		}
	}
	else{
		$jResponse = [
			'error' => 1,
			'message' =>'Tên đã tồn tại'
		];
	}
	
	echo json_encode($jResponse);
	 return $bok;
}

function Delete(){
	 $bOk = false;
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$CategoryData = CategoryData::getInstance();
		$data_delete = $CategoryData->Delete($id);
		if($data_delete['n']>0){
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
			 $bOk=true;
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
	 return $bOk;
}