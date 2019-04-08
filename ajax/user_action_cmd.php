<?php
require_once '../util/util.php';
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/UserData.php';
require_once '../model/PaymentData.php';
require_once '../model/LogAdminData.php';
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

//$_POST = json_decode(file_get_contents('php://input'),true);
if(isset($_POST['action'])) $action=$_POST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'user',$action)){
		switch ($action){
			case 'add_vip':
				AddExpireVip($admin_info);
				break;
			case 'set_award':
				setAward($admin_info,true);
				break;
			case 'unset_award':
				setAward($admin_info,false);
				break;
			case 'example_school':
			case 'un_example_school':
			case 'example_district':
			case 'un_example_district':
			case 'example_province':
			case 'un_example_province':
			case 'example_national':
			case 'un_example_national':
			case 'set_active':
			case 'set_inactive':
			case 'set_password':
				set_example($action,$admin_info);
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

function AddExpireVip($admin_info){
	//global $redis;
	$jResponse = [];
	
	$list_id = isset($_POST['list_id'])?$_POST['list_id']:'';
//	$day = isset($_POST['day'])?intval($_POST['day']):0;
	$money = isset($_POST['money'])?intval($_POST['money']): 0;
	$note = isset($_POST['note'])?$_POST['note']:'';

	if($list_id == '' || $money == 0){
		$jResponse = [
			'error' => 3,
			'message' =>'Hãy nhập đủ dữ liệu'
		];
	} else {
		global $db;
		global $vip_day;

		//$db->execute('AddVipDay('. $id .',' . $day . ');');
		$user_admin = $admin_info['username'];
		$PaymentLogData = PaymentLogData::getInstance();
		$note = util::ReplaceHTML($note);

		$UserData = UserData::getInstance();
		$results = [];
		$arr_id = explode(',', $list_id);

		$day = $vip_day[$money];

		foreach ($arr_id as $id) {
			$newDate = new MongoDate();
			$id = intval($id);

			$userInfo = $UserData->getVipDay($id);
			if(isset($userInfo)) {
				if(isset($userInfo['vip_expire']) && $userInfo['vip_expire']->sec > $newDate->sec + 86400 * 3) {
					// éo cho cộng!
					array_push($results, [
						'id' => $id
					]);
				} else {
					// $result = $UserData->AddVipDay($id, $day);

					$secAdd = 86400 * intval($day);
					$date_update=null;
					if(isset($userInfo['vip_expire'])) {
						$date = $userInfo['vip_expire'];
						if($newDate->sec > $date->sec) {
							$newDate->sec += $secAdd;
							$date_update = $newDate;
						} else {
							$date->sec += $secAdd;
							$date_update = $date;
						}
					} else {
						$newDate->sec += $secAdd;
						$date_update = $newDate;
					}

					$result = $UserData->updateVipDay($id, $date_update);

					$result['id'] = $id;
					unset($result['shards']);
					unset($result['shardRawGLE']);
					array_push($results, $result);
					$PaymentLogData->Insert($user_admin, $id, $day, $money, $userInfo['vip_expire'], $date_update, $note);
				}
			} else {
				// user tồn tại éo đâu mà cộng!
				array_push($results, [
					'id' => $id
				]);
			}
		}

		$jResponse = [
			'error' => 0,
			'message' => '',
			'results' => $results
		];
	}
	
	echo json_encode($jResponse);
}

function set_example($action, $admin_info){
	//global $redis;
	$jResponse = [];

	$list_id = isset($_POST['list_id'])?$_POST['list_id']:'';
	$note = isset($_POST['note'])?$_POST['note']:'';

	if($list_id == '') {
		$jResponse = [
			'error' => 3,
			'message' =>'Hãy nhập đủ dữ liệu'
		];
	} else {
		global $db;
		//$db->execute('AddVipDay('. $id .',' . $day . ');');

		$list_ids = [];
		$arr_id = explode(',', $list_id);
		foreach ($arr_id as $id) {
			$id = intval($id);
			if(!in_array($id, $list_ids)) {
				array_push($list_ids, $id);
			} else {
				echo json_encode([
					'error' => 100,
					'message' => 'Trong danh sách có học sinh trùng: ' . $id
				]);
				return false;
			}
		}

		if(count($list_ids) > 0) {
			$UserData = UserData::getInstance();
			$results = [];
			$jResponse = null;

			$list_exists_id = $UserData->GetListDistinct('_id', $list_ids);

			if(count($list_exists_id) == count($list_ids)) {
				if($action == 'example_school') {
					// kiểm tra có cùng trường không?
					$list_school = $UserData->GetListDistinct('school_id', $list_ids);
					if(count($list_school) == 1) {
						// kiểm tra đã pass qua round
						$pass_round = isset($_POST['pass_round'])? intval($_POST['pass_round']): 0;
						if($pass_round > 0) {
							$list_round = $UserData->GetListDistinct('current_round_4', $list_ids);
							if(count($list_round) == 1 && $list_round[0] == $pass_round) {
								// execute update
								$results = $UserData->UpdateListField($list_ids,'exam_school',true);
							} else {
								$jResponse = [
									'error' => 8,
									'message' => 'Một vài học sinh chưa qua vòng đã chọn'
								];
							}
						} else {
							$jResponse = [
								'error' => 7,
								'message' => 'Hãy chọn "Bắt buộc HS phải qua vòng"'
							];
						}
					} else {
						$jResponse = [
							'error' => 6,
							'message' => 'Học sinh đang ở nhiều trường khác nhau, vui lòng check lại',
							'results' => count($list_school)
						];
					}
				} else if($action == 'un_example_school') {
					$results = $UserData->UpdateListField($list_ids,'exam_school',false);
				} else if($action == 'example_district') {
					// kiểm tra có cùng huyện không?
					$list_district = $UserData->GetListDistinct('district_id', $list_ids);
					if(count($list_district) == 1) {
						// kiểm tra đã pass qua round
						$pass_round = isset($_POST['pass_round'])? intval($_POST['pass_round']): 0;
						if($pass_round > 0) {
							$list_round = $UserData->GetListDistinct('current_round_4', $list_ids);
							if(count($list_round) == 1 && $list_round[0] == $pass_round) {
								// execute update
								$results = $UserData->UpdateListField($list_ids,'exam_district',true);
							} else {
								$jResponse = [
									'error' => 8,
									'message' => 'Một vài học sinh chưa qua vòng đã chọn'
								];
							}
						} else {
							$jResponse = [
								'error' => 7,
								'message' => 'Hãy chọn "Bắt buộc HS phải qua vòng"'
							];
						}
					} else {
						$jResponse = [
							'error' => 6,
							'message' => 'Học sinh đang ở nhiều huyện khác nhau, vui lòng check lại',
							'results' => count($list_district)
						];
					}
				} else if($action == 'un_example_district') {
					$results = $UserData->UpdateListField($list_ids,'exam_district',false);
				} else if($action == 'example_province') {
					// kiểm tra có cùng tỉnh không?
					$list_province = $UserData->GetListDistinct('province_id', $list_ids);
					if(count($list_province) == 1) {
						// kiểm tra đã pass qua round
						$pass_round = isset($_POST['pass_round'])? intval($_POST['pass_round']): 0;
						if($pass_round > 0) {
							$list_round = $UserData->GetListDistinct('current_round_4', $list_ids);
							if(count($list_round) == 1 && $list_round[0] == $pass_round) {
								// execute update
								$results = $UserData->UpdateListField($list_ids,'exam_province',true);
							} else {
								$jResponse = [
									'error' => 8,
									'message' => 'Một vài học sinh chưa qua vòng đã chọn'
								];
							}
						} else {
							$jResponse = [
								'error' => 7,
								'message' => 'Hãy chọn "Bắt buộc HS phải qua vòng"'
							];
						}
					} else {
						$jResponse = [
							'error' => 6,
							'message' => 'Học sinh đang ở nhiều tỉnh khác nhau, vui lòng check lại',
							'results' => count($list_province)
						];
					}
				} else if($action == 'un_example_province') {
					$results = $UserData->UpdateListField($list_ids,'exam_province',false);
				} else if($action == 'example_national') {
					// kiểm tra đã pass qua round 19
					$list_round = $UserData->GetListDistinct('current_round_4', $list_ids);
					if(count($list_round) == 1 && $list_round[0] == 18) {
						// execute update
						$results = $UserData->UpdateListField($list_ids,'exam_national',true);
					} else {
						$jResponse = [
							'error' => 8,
							'message' => 'Một vài học sinh chưa qua vòng 18'
						];
					}
				} else if($action == 'un_example_national') {
					$results = $UserData->UpdateListField($list_ids,'exam_national',false);
				} else if($action == 'set_active') {
					$results = $UserData->UpdateListField($list_ids,'active',true);
				} else if($action == 'set_inactive') {
					$results = $UserData->UpdateListField($list_ids,'active',false);
				} /*else if($action == 'set_password') {
					$password = isset($_POST['password']) ? $_POST['password'] : '';
					$newPassword = util::sha256($password);
					$results = $UserData->UpdateListField($list_ids,'password', $newPassword);
				}*/
				unset($results['shards']);
				unset($results['shardRawGLE']);
			} else {
				$jResponse = [
					'error' => 1404,
					'message' => 'Một vài ID không có trong hệ thống',
					'results' => $list_exists_id
				];
			}

			if($jResponse == null) {
				$jResponse = [
					'error' => 0,
					'message' => '',
					'results' => $results
				];

				$LogAdminData = LogAdminData::getInstance();
				$LogAdminData->Insert([
					'username' => $admin_info['username'],
					'action' => $action,
					'list_user' => $list_ids,
					'ip' => util::GetIpClient(),
					'note'=>util::ReplaceHTML($note)
				]);
			}
		} else {
			$jResponse = [
				'error' => 1,
				'message' => 'Hãy nhập đúng danh sách ID'
			];
		}
	}

	echo json_encode($jResponse);
}

function setAward($admin_info, $bSet){
	//global $redis;
	$jResponse = [];

	$list_id = isset($_POST['list_id'])?$_POST['list_id']:'';
	$award = isset($_POST['award'])?$_POST['award']:'';
	$note = isset($_POST['note'])?$_POST['note']:'';

	if($list_id == '' || ($bSet && $award == '')){
		$jResponse = [
			'error' => 3,
			'message' =>'Hãy nhập đủ dữ liệu'
		];
	}
	else{
		global $db;
		$user_admin = $admin_info['username'];
		//$PaymentLogData = PaymentLogData::getInstance();
		//$note = util::ReplaceHTML($note);
		//$PaymentLogData->Insert($user_admin,$id,$day,$note);

		$list_ids = [];
		$arr_id = explode(',', $list_id);
		foreach ($arr_id as $id){
			$id = intval($id);
			array_push($list_ids, $id);
		}

		$results = null;
		if(count($list_ids)>0) {
			$UserData = UserData::getInstance();

			if ($bSet) {
				$results = $UserData->UpdateListField($list_ids, 'award', $award);
			}
			else{
				$results = $UserData->UpdateListField($list_ids, 'award', null);
			}
		}
		$jResponse = [
			'error' => 0,
			'message' => '',
			'results' => $results
		];
	}

	echo json_encode($jResponse);
}