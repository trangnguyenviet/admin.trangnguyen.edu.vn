<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/NewsData.php';
require_once '../model/UserAdminData.php';
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
	if(CheckPermission($admin_info['_id'],'news',$action)){
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
			case 'copy':
				$bok = CopyData($admin_info);
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
	$parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
	$page_index = isset($_POST['page_index'])?intval($_POST['page_index']):0;
	$search_key = isset($_POST['search_key'])?$_POST['search_key']:'';
	
	$jResponse = [];
	
	if($parent_id>0){
		$NewsData = NewsData::getInstance();
		$data = [];
		if($search_key=='') $data = $NewsData->GetList($parent_id,page_size_news,$page_index);
		else $data = $NewsData->Search($parent_id,$search_key,page_size_news,$page_index);
		
		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'content' => $data['list'],
			'row_count' => $data['count'],
			'page_index' => $page_index,
			'page_size' => page_size_news,
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
	
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';
	$name_ko_dau = isset($_POST['name_ko_dau'])?$_POST['name_ko_dau']:'';
	$parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
	$parent_name = isset($_POST['parent_name'])?$_POST['parent_name']:'';
	$sort = isset($_POST['sort'])?intval($_POST['sort']):0;
	$thumb = isset($_POST['thumb'])?$_POST['thumb']:'';
	$description = isset($_POST['description'])?$_POST['description']:'';
	$content = isset($_POST['content'])?$_POST['content']:'';
	$is_publish_date = isset($_POST['is_publish_date'])?$_POST['is_publish_date']:'';
	$active = isset($_POST['active'])?$_POST['active']=='true':false;
	
	$tags = [];
	$tags_request = isset($_POST['tags'])?$_POST['tags']:'';
	$tags = explode(',', $tags_request);
	
	$publish_at = isset($_POST['publish_at'])?$_POST['publish_at']:'';
	$publish_end = isset($_POST['publish_end'])?$_POST['publish_end']:'';
// 	$active = true;

	$is_publish_date = $is_publish_date=='true';
	if($publish_at!=''){
		if (($timestamp = strtotime($publish_at)) === false) {
			$publish_at=null;
		} else {
			$publish_at = new MongoDate($timestamp);
		}
	}
	else{
		$publish_at=null;
	}

	if($publish_end!=''){
		if (($timestamp = strtotime($publish_end)) === false) {
			$publish_end=null;
		} else {
			$publish_end = new MongoDate($timestamp);
		}
	}
	else{
		$publish_end=null;
	}

	if($is_publish_date && ($publish_at==null || $publish_end==null)){
		$jResponse = [
			'error' => 2,
			'message' =>'Chọn thời điểm publish'
		];
		echo json_encode($jResponse);
		return;
	}

	$NewsData = NewsData::getInstance();
	if($id>0){
		$data_update = $NewsData->Update($id,$name,$parent_id,$parent_name,$name_ko_dau,$active,$sort,$thumb,$description,$content,$is_publish_date,$publish_at,$publish_end,$tags);
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$redis->delete(sprintf(news_detail,$id));//update cache
			$keys = $redis->keys(sprintf('list_news_%s_%s',$parent_id,'*'));
			if($keys && count($keys)>0){
				foreach($keys as $key=>$value){
					$redis->del($value);
				}
			}
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
		$create_by = $admin_info['_id'];
		$data_insert = $NewsData->Insert($name,$parent_id,$parent_name,$name_ko_dau,$active,$sort,$thumb,$description,$content,$create_by,$is_publish_date,$publish_at,$publish_end,$tags);
		if($data_insert['_id']>0){
			$jResponse = [
				'error' => 0,
				'message' =>'ok'
			];
			$keys = $redis->keys(sprintf('list_news_%s_%s',$parent_id,'*'));
			if($keys && count($keys)>0){
				foreach($keys as $key=>$value){
					$redis->del($value);
				}
			}
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
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
	if($id>0){
		$NewsData = NewsData::getInstance();
		$data_delete = $NewsData->Delete($id);
		if($data_delete['n']>0){
			$jResponse = [
					'error' => 0,
					'message' => 'ok'
			];
			$redis->delete(news_detail.$id);//update cache
			$keys = $redis->keys(sprintf('list_news_%s_%s',$parent_id,'*'));
			if($keys && count($keys)>0){
				foreach($keys as $key=>$value){
					$redis->del($value);
				}
			}
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

function CopyData($admin_info){
	global $redis;
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
	if($id > 0 && $parent_id > 0){
		$create_by = $admin_info['_id'];
		$NewsData = NewsData::getInstance();
		$data_copy = $NewsData->CopyData($id, $parent_id, $create_by);
		if($data_copy){
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
			$keys = $redis->keys(sprintf('list_news_%s_%s',$parent_id,'*'));
			if($keys && count($keys)>0){
				foreach($keys as $key=>$value){
					$redis->del($value);
				}
			}
		}
		else{
			$jResponse = [
				'error' => 83,
				'message' => 'Copy không thành công'
			];
		}
	}
	else{
		$jResponse = [
			'error' => 80,
			'message' => 'Tham số truyền chưa đủ'
		];
	}
	echo json_encode($jResponse);
}