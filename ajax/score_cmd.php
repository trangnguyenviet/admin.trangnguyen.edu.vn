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

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'score',$action)){
		$bok = false;
		switch ($action) {
			case 'list':
				$bok = Load_List();
				break;
			case 'save':
				$bok = Save();
				break;
			case 'update_rank':
				UpdateRankScore();
				break;
			case 'delete':
				$bok = Delete();
				break;
			case 'delete_score_cache':
				$bok = DeleteCache();
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
	global $redis;
	$jResponse = [];
	$user_id = isset($_POST['user_id'])?intval($_POST['user_id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$school_id = isset($_POST['school_id'])?intval($_POST['school_id']):0;
	
	$ScoreData = ScoreData::getInstance();
	$list = $ScoreData->GetList($user_id, $type_id);
	
	//var_dump($redis->keys('rank_type_*'));
	//echo 'key: ' . rank_type.$type_id.'\n';
	//echo 'Id: ' . $user_id.'\n';
	//var_dump($redis->zrevrank(rank_type.$type_id,$id));
	
	$rank_score_national = $redis->zrevrank(sprintf(rank_type,$type_id),$user_id);
	$rank_score_province = $redis->zrevrank(sprintf(rank_province,$type_id,$province_id),$user_id);
	$rank_score_district = $redis->zrevrank(sprintf(rank_district,$type_id,$district_id),$user_id);
	$rank_score_school = $redis->zrevrank(sprintf(rank_school,$type_id,$school_id),$user_id);
	
	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $list,
		'rank_score_national' => $rank_score_national,
		'rank_score_province' => $rank_score_province,
		'rank_score_district' => $rank_score_district,
		'rank_score_school' => $rank_score_school
	];
	echo json_encode($jResponse);
	// return $bok;
}

function Save(){
	$user_id = isset($_POST['user_id'])?intval($_POST['user_id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$date_create = isset($_POST['date_create'])?$_POST['date_create']:'';
	$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	$score = isset($_POST['score'])?intval($_POST['score']):0;
	$time = isset($_POST['time'])?intval($_POST['time']):1200;
	$luot = isset($_POST['luot'])?intval($_POST['luot']):0;
	$code = $_POST['code']?$_POST['code']:'';

	$ScoreData = ScoreData::getInstance();
	$exist = $ScoreData->CheckExist($user_id,$type_id,$round_id);
	if($exist==false){
		$created_at = new MongoDate(strtotime($date_create));
		$data_insert = $ScoreData->Insert($type_id,$user_id,$time,$score,$round_id,$luot,$code,$created_at);
		if($data_insert['_id']>0){
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
	else{
		$jResponse = [
			'error' => 1,
			'message' =>'Điểm vòng này đã tồn tại.'
		];
	}

	echo json_encode($jResponse);
	// return $bok;
}

function Delete(){
	// $bOk = false;
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$ScoreData = ScoreData::getInstance();
		$data_delete = $ScoreData->Delete($id);
		if($data_delete['n']>0){
			$jResponse = [
					'error' => 0,
					'message' => 'ok'
			];
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

function DeleteCache(){
	// $bok = false;
	global $redis;
	$jResponse = [];
	$user_id = isset($_POST['user_id'])?intval($_POST['user_id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	
	$redis->del(sprintf(score_user_hash,$type_id,$round_id,$user_id));
	$redis->del(sprintf(score_user_info_hash,$type_id,$round_id,$user_id));
	
	$jResponse = [
		'error' => 0,
		'message' => 'ok'
	];
	echo json_encode($jResponse);
	// return $bok;
}

function UpdateRankScore(){
	global $redis;
	$jResponse = [];
	$user_id = isset($_POST['user_id'])?intval($_POST['user_id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$school_id = isset($_POST['school_id'])?intval($_POST['school_id']):0;
	
	$ScoreData = ScoreData::getInstance();
	$list = $ScoreData->GetList($user_id, $type_id);
	if($list && count($list)>0){
		$total_score = 0;
		$total_time = 0;
		foreach($list as $value){
			$total_score+=$value['score'];
			$total_time+=$value['time'];
			
			//update dữ liệu thi (redis)
			//$key = sprintf(score_user_hash,$type_id,$value['round_id'],$user_id);
			//$redis->hmset($key,);
		}
		
		//update xếp hạng
		$score = $total_score + 1/$total_time;
		$redis->zadd(sprintf(rank_type,$type_id),$score,$user_id);
		$redis->zadd(sprintf(rank_province,$type_id,$province_id),$score,$user_id);
		$redis->zadd(sprintf(rank_district,$type_id,$district_id),$score,$user_id);
		$redis->zadd(sprintf(rank_school,$type_id,$school_id),$score,$user_id);
		
		//$UserData = UserData:getInstance();
		//$UserData->
	}
	else{
		//xóa dữ liệu thi
		$keys = $redis->keys(sprintf(score_user_hash_delete,$type_id,$user_id));
		if($keys && count($keys)>0){
			foreach($keys as $key=>$value){
				$redis->del($value);
			}
		}
		//xóa xếp hạng
		$redis->zrem(sprintf(rank_type,$type_id),$user_id);
		$redis->zrem(sprintf(rank_province,$type_id,$province_id),$user_id);
		$redis->zrem(sprintf(rank_district,$type_id,$district_id),$user_id);
		$redis->zrem(sprintf(rank_school,$type_id,$school_id),$user_id);
	}
	
	$jResponse['error'] = 0;
	$jResponse['message'] = 'ok';
	
	echo json_encode($jResponse);
}
