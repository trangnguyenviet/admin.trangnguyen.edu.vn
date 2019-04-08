<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 22/03/2017
 * Time: 20:30
 */
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/TNCardData.php';
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
	if(CheckPermission($admin_info['_id'],'category',$action)){
		switch ($action){
			case 'report':
				$jResponse = Report();
				break;
			case 'search':
				$jResponse = Search();
				break;
			default:
				$jResponse = [
					'error' => 81,
					'message' => 'Request not correct'
				];
		}
	}
	else{
		$jResponse = [
			'error' => 81,
			'message' => 'Bạn không có quyền thao tác hành động này'
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

function Report(){
	$jResponse = [];
	$TNCardData = TNCardData::getInstance();
	$list = $TNCardData->Report();

	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $list
	];
	return $jResponse;
}

function Search(){
	$jResponse = [];
	$page_index = isset($_POST['page_index'])? intval($_POST['page_index']): 0;
	$search_type = isset($_POST['search_type'])? $_POST['search_type']: '';
	$key_search = isset($_POST['key_search'])? $_POST['key_search']: '';
	$page_size = 100;

	$where = ['active'=>true];
	if($key_search!=''){
		if($search_type=='like'){
			$where['serial'] = new MongoRegex('/'.$key_search.'/');
		}
		elseif($search_type=='start'){
			$where['serial'] = new MongoRegex('/^'.$key_search.'/');
		}
		elseif($search_type=='end'){
			$where['serial'] = new MongoRegex('/'.$key_search.'$/');
		}
		else {
			$where['serial'] = $key_search;
		}
	}

	if(isset($_POST['is_used'])){
		$is_used = $_POST['is_used'];
		if($is_used=='true'){
			$where['is_used'] = true;
		}
		elseif ($is_used=='false'){
			$where['is_used'] = false;
		}
	}

	$TNCardData = TNCardData::getInstance();
	$list = $TNCardData->Search($where,$page_size,$page_index);
	$jResponse = [
		'error' => 0,
		'message' => '',
		'content' => $list['list'],
		'count' => $list['count'],
		'page_index' => $page_index,
		'page_size' => $page_size
	];
	return $jResponse;
}