<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ExamEventData.php';
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
	if(CheckPermission($admin_info['_id'],'exam',$action)){
		$bok = false;
		switch ($action){
			case 'list':
				$bok = Load_List();
				break;
			case 'info':
                $bok = Info();
                break;
			case 'save':
				$bok = Save($admin_info);
				break;
			case 'delete':
				$bok = Delete($admin_info);
				break;
			case 'exists':
				$bok = Exists($admin_info);
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
	$jResponse = [];
	$ExamEventData = ExamEventData::getInstance();
	$data = $ExamEventData->GetListShow();
	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $data
	];
	echo json_encode($jResponse);
	// return $bok;
}

function Info(){
    $jResponse = [];
    $id = isset($_POST['id'])?intval($_POST['id']):0;
    $ExamEventData = ExamEventData::getInstance();
    $data = $ExamEventData->GetInfo($id);
    $jResponse = [
        'error' => 0,
        'message' => 'ok',
        'info' => $data
    ];
    echo json_encode($jResponse);
    // return $bok;
}

function Save($admin_info){
// 	var_dump($admin_info);
	global $redis;
	$jResponse = [];

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$answers = isset($_POST['answers'])?$_POST['answers']:[];
	$content = isset($_POST['content'])?$_POST['content']:[];
	$time = isset($_POST['time'])?intval($_POST['time']):0;
	//$game_id = isset($_POST['game_id'])?intval($_POST['game_id']):0;
	$play = isset($_POST['play'])?intval($_POST['play']):100;
	$spq = isset($_POST['spq'])?intval($_POST['spq']):10;

	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	//$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	//$test = isset($_POST['test'])?intval($_POST['test']):0;

	/*game chuot
	if($game_id==1){
		if(count($content)>0){
			$length = count($content);
			for($i=0;$i<$length;$i++){
				if($content[$i]['is_noisy']){
					$content[$i]['is_noisy']=$content[$i]['is_noisy']=='true';
				}
			}
		}
	}
	*/

	$ExamEventData = ExamEventData::getInstance();
	if($id>0){
		$data_update = $ExamEventData->Update($id, $play, $time, $spq, $answers, $content);
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$redis->delete(sprintf(exam_event_game,$type_id, $class_id));//update cache
			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'id' => $id
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
		$exam_info = $ExamEventData->GetInfo2($type_id, $class_id);
		if(isset($exam_info)){
			$id = intval($exam_info['_id']);
			$data_update = $ExamEventData->Update($id, $play, $time, $spq, $answers, $content);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$redis->delete(sprintf(exam_event_game,$type_id, $class_id));//update cache
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'id' => $id
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
			$data_insert = $ExamEventData->Insert($class_id,$type_id, 0, $play, $time, $spq, $answers, $content);
			if($data_insert['_id']>0){
				$jResponse = [
					'error' => 0,
					'message' =>'ok',
					'id' => $data_insert['_id']
				];
			}
			else{
				$jResponse = [
					'error' => 82,
					'message' =>'Insert không thành công'
				];
			}
		}
	}
	//var_dump($_POST);

	echo json_encode($jResponse);
	// return $bok;
}

function Delete($admin_info){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	if($id>0){
		$ExamEventData = ExamEventData::getInstance();
		$exam_info = $ExamEventData->GetInfo($id);
		if(isset($exam_info)){
			$type_id = intval($exam_info['type_id']);
			$class_id = intval($exam_info['class_id']);
			//$round_id = intval($exam_info['round_id']);
			//$test = intval($exam_info['test']);

			$redis->delete(sprintf(exam_event_game,$type_id, $class_id));//update cache

			$data_delete = $ExamEventData->Delete($id);
			if($data_delete['n']>0){
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
				'error' => 83,
				'message' => 'Dữ liệu chưa thay đổi'
			];
		}
	}
	else{
		$jResponse = [
			'error' => 80,
			'message' => 'Chưa có tham số id'
		];
	}
	echo json_encode($jResponse);
	// return $bOk;
}

function Exists($admin_info){
	//var_dump($admin_info);
	//global $redis;
	$jResponse = [];

	//$id = isset($_POST['id'])?intval($_POST['id']):0;
	$type_id = isset($_POST['type_id'])?intval($_POST['type_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	//$round_id = isset($_POST['round_id'])?intval($_POST['round_id']):0;
	//$test = isset($_POST['test'])?intval($_POST['test']):0;

	$ExamEventData = ExamEventData::getInstance();
	$data_id = $ExamEventData->GetId($type_id, $class_id);
	//var_dump($data_id);
	if(isset($data_id)){
		$jResponse = [
			'error' => 0,
			'message' =>'ok',
			'id' => $data_id['_id'],
			'exists' => true
		];
	}
	else{
		$jResponse = [
			'error' => 0,
			'message' =>'ok',
			'id' => 0,
			'exists' => false
		];
	}

	echo json_encode($jResponse);
	// return $bok;
}

function CopyData($admin_info){
	//var_dump($admin_info);
	global $redis;
	$jResponse = [];

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	//$exists_id = isset($_POST['exists_id'])?intval($_POST['exists_id']):0;
	$exam_event_id = isset($_POST['exam_event_id'])?intval($_POST['exam_event_id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;

	if($id>0 && $exam_event_id>0 && $class_id>0){
		$ExamEventData = ExamEventData::getInstance();

		$data_copy = $ExamEventData->CopyData($id, $exam_event_id, $class_id);
		if($data_copy){
			$jResponse = [
				'error' => 0,
				'message' =>'ok'
			];
			//if($exists_id>0) $data_delete = $ExamEventData->Delete($exists_id);
			$redis->delete(sprintf(exam_event_game,$exam_event_id, $class_id));//update cache
		}
		else{
			$jResponse = [
				'error' => 0,
				'message' =>'copy không thành công'
			];
		}
	}
	else{
		$jResponse = ['error' => 1, 'message' =>'chưa nhập đủ thông tin'];
	}

	echo json_encode($jResponse);
	// return $bok;
}