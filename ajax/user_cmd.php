<?php
require_once '../util/util.php';
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/UserData.php';
require_once '../model/ScoreData.php';
require_once '../model/PaymentData.php';
require_once '../model/PaymentLogData.php';
require_once '../util/email_cmd.php';
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
	if(CheckPermission($admin_info['_id'],'user',$action)){
		switch ($action){
			case 'list':
				Load_List();
				break;
			case 'save':
				Save($admin_info);
				break;
//			case 'change_password':
//				Change_Password();
//				break;
			case 'ban':
				Ban($admin_info);
				break;
//			case 'un_ban':
//				UnBan($admin_info);
//				break;
			case 'delete':
				Delete($admin_info);
				break;
			case 'delete_avatar':
				DeleteAvatar();
				break;
			case 'add_expire_vip':
				AddExpireVip($admin_info);
				break;
			case 'top-score':
				TopScore();
				break;
			case 'exam_school':
				ExamSchool();
				break;
			case 'exam_district':
				ExamDistrict();
				break;
			case 'exam_province':
				ExamProvince();
				break;
			case 'exam_national':
				ExamNational();
				break;
			case 'export':
				Export();
				break;
			case 'send_email':
				Email();
				break;
			case 'send_sms':
				Sms();
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
	echo json_encode(Load_Data_Search(false));
}

function Load_Data_Search($bFull){
	$search_type = isset($_POST['search_type'])?$_POST['search_type']:'';
	$page_index = isset($_POST['page_index'])?intval($_POST['page_index']):0;

	$jResponse = [];
	$order_by = ['_id'=>1];

	try {
		$where = null;
		$bWhere = true;
		$score = null;
		if($search_type=='id'){
			$id = isset($_POST['id'])?intval($_POST['id']):0;
			if($id>0){
				$where = ['_id'=>$id];
			}
		}
		elseif ($search_type=='list_id'){
			$list_id = isset($_POST['list_id'])?$_POST['list_id']:'';
			if($list_id!=''){
				$list = [];
				$arr_id = explode(',', $list_id);
				foreach ($arr_id as $id){
					$id = intval($id);
					array_push($list, $id);
				}
				$where = ['_id' => ['$in' => $list]];
			}
		}
		elseif($search_type=='username'){
			$username = isset($_POST['username'])?$_POST['username']:'';
			if($username!=''){
				$where = ['username'=>new MongoRegex('/'.$username.'/i')];
			}
		}
		elseif($search_type=='fullname'){
			$fullname = isset($_POST['fullname'])?$_POST['fullname']:'';
			if($fullname!=''){
				$where = ['name'=>new MongoRegex('/'.$fullname.'/i')];
			}
		}
		elseif($search_type=='email'){
			$email = isset($_POST['email'])?$_POST['email']:'';
			if($email!=''){
				$where = ['email'=>new MongoRegex('/'.$email.'/i')];
			}
		}
		elseif($search_type=='mobile'){
			$mobile = isset($_POST['mobile'])?$_POST['mobile']:'';
			if($mobile!=''){
				$where = ['mobile'=>new MongoRegex('/'.$mobile.'/i')];
			}
		}
		elseif($search_type=='mobile'){
			$mobile = isset($_POST['mobile'])?intval($_POST['mobile']):'';
			if($mobile!=''){
				$where = ['mobile'=>new MongoRegex('/'.$mobile.'/i')];
			}
		}
		elseif($search_type=='address'){
			$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
			if($province_id>0){
				$where = [];
				$where['province_id']=$province_id;

				$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
				if($district_id>0){
					$where['district_id']=$district_id;

					$school_id = isset($_POST['school_id'])?intval($_POST['school_id']):0;
					if($school_id>0){
						$where['school_id']=$school_id;

						$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
						if($class_id>0){
							$where['class_id']=$class_id;
						}
					}
				}
			}
		}
		elseif($search_type=='exam_district'){
			$where = ['exam_district' => true];
			$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
			$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
			$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
			if($province_id>0){
				$where['province_id']=$province_id;
				if($district_id>0) $where['district_id']=$district_id;
			}
			if($class_id>0) $where['class_id']=$class_id;
		}
		elseif($search_type=='exam_province'){
			$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
			if($province_id>0){
				$where = [];
				$where['province_id']=$province_id;
				$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
				if($class_id>0) $where['class_id']=$class_id;
			}
			$where['exam_province']=true;
		}
		elseif($search_type=='exam_national'){
			$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
			if($province_id>0){
				$where = [];
				$where['province_id']=$province_id;
			}
			$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
			if($class_id>0) $where['class_id']=$class_id;
			$where['exam_national']=true;
		}
		elseif($search_type=='award'){
			$award = isset($_POST['award'])?intval($_POST['award']):0;
			if($award>0){
				$where = [];
				$where['award']= $award;
				$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
				if($province_id>0){
					$where['province_id']=$province_id;
				}
				$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
				if($class_id>0) $where['class_id']=$class_id;
			}
		}
		elseif($search_type=='score'){
			$code = isset($_POST['code'])?$_POST['code']:'';
			$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
			if($code!=''){
				$ScoreData = ScoreData::getInstance();
				$score = $ScoreData->GetListCode($code, 4);
				if(isset($score) && count($score['list'])>0){
					$bWhere = false;
					$where = [];
					$where['_id']=['$in'=>$score['list_user_id']];
					if($class_id>0) $where['class_id'] = $class_id;
					$UserData = UserData::getInstance();
					$data = $UserData->Search($where, $order_by, page_size_user, $page_index,true);

					if(isset($data) && count($data['list'])>0){
						$list_user = $data['list'];
						$map = [];
						foreach($list_user as $user_info){
							$map[$user_info['_id']] = $user_info;
						}
						$list=[];
						$list_score = $score['list'];
						foreach($list_score as $item){
							unset($item['_id']);
							$user_id = $item['user_id'];
							if(isset($map[$user_id])){
								$user_info = $map[$user_id];
								unset($item['user_id']);
								$item['score_date'] = $item['created_at'];
								unset($item['created_at']);
								array_push($list,array_merge($item,$user_info));
							}
						}
						$jResponse = [
							'error' => 0,
							'message' => '',
							'content' => $list,
							'row_count' => count($data['list']),//count($list),
							'page_index' => 0,
							'page_size' => 99999,
						];
					}
					else{
						$jResponse = [
							'error' => 2,
							'message' => 'Mã thẻ không đúng hoặc chưa có người sử dụng'
						];
					}
				}
				else{
					$bWhere = false;
					$jResponse = [
						'error' => 1,
						'message' => 'Mã thẻ không đúng hoặc chưa có người sử dụng'
					];
				}
			}
		}
		elseif($search_type=='payment'){
			$PaymentData = PaymentData::getInstance();
			$list_user = $PaymentData->GetListUserDone();
			$where = [];
			$where['_id']=['$in'=>$list_user];
			$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
			if($province_id>0) $where['province_id']=$province_id;
		}

		if($bWhere){
			if($where!=null){
				$UserData = UserData::getInstance();
				$data = $UserData->Search($where, $order_by, page_size_user, $page_index,$bFull);

				$jResponse = [
					'error' => 0,
					'message' => 'ok',
					'content' => $data['list'],
					'row_count' => $data['count'],
					'page_index' => $page_index,
					'page_size' => page_size_user,
				];
			}
			else{
				$jResponse = [
					'error' => 2,
					'message' => 'Chưa chọn kiểu tìm kiếm hoặc điền đầy đủ thông tin tìm kiếm'
				];
			}
		}
	} catch (Exception $e) {
		$jResponse = [
			'error' => 10000,
			'message' => $e->getMessage()
		];
	}
	finally{
		//echo json_encode($jResponse);
		return $jResponse;
	}
}

function Export(){
	$data = Load_Data_Search(true);
	if($data['error']==0){
		$content = $data['content'];
		if(isset($content) && count($content)>0){
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			// date_default_timezone_set('Europe/London');

			if (PHP_SAPI == 'cli')
				die('This example should only be run from a Web Browser');

			/** Include PHPExcel */
			//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
			require_once $_SERVER['DOCUMENT_ROOT'] . '/Classes/PHPExcel.php';

			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()
				->setCreator("trangnguyen.edu.vn")
				->setLastModifiedBy("admin")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Export data from Trangnguyen.edu.vn")
				->setKeywords("office 2007 openxml php")
				->setCategory("Export data");
			$sheet = $objPHPExcel->setActiveSheetIndex(0);
			$sheet->setCellValue('A1','ID');
			$sheet->setCellValue('B1','Tên đăng nhập');
			$sheet->setCellValue('C1','Họ tên');
			$sheet->setCellValue('D1','Ngày sinh');
			$sheet->setCellValue('E1','Email');
			$sheet->setCellValue('F1','Điện thoại');
			$sheet->setCellValue('G1','Khối');
			$sheet->setCellValue('H1','Lớp');
			$sheet->setCellValue('I1','Trường');
			$sheet->setCellValue('J1','Quận/huyện');
			$sheet->setCellValue('K1','Tỉnh/TP');

			$sheet->setCellValue('L1','Học phí');

			$sheet->setCellValue('M1','Điểm');
			$sheet->setCellValue('N1','TGian thi');
			$sheet->setCellValue('O1','Vòng');
			$sheet->setCellValue('P1','Lượt');
			$sheet->setCellValue('Q1','Ngày thi');
			$sheet->setCellValue('R1','Mã thi');
			$sheet->setCellValue('S1','Tổng điểm');
			$sheet->setCellValue('T1','Tổng thời gian');
			$sheet->setCellValue('U1','Tổng vòng thi');

			foreach($content as $index => $item){
				if(isset($item['_id'])) $sheet->setCellValue('A'.($index+2),$item['_id']);
				if(isset($item['username'])) $sheet->setCellValue('B'.($index+2),$item['username']);
				if(isset($item['name'])) $sheet->setCellValue('C'.($index+2),$item['name']);
				if(isset($item['birthday'])){
					$s = date('d/m/Y', $item['birthday']);
					$sheet->setCellValue('D'.($index+2),'\''.$s);
				}

				// if(isset($item['email'])) $sheet->setCellValue('E'.($index+2),$item['email']);
				// if(isset($item['mobile'])) $sheet->setCellValue('F'.($index+2),'\''.$item['mobile']);

				if(isset($item['class_id'])) $sheet->setCellValue('G'.($index+2),$item['class_id']);
				if(isset($item['class_name'])) $sheet->setCellValue('H'.($index+2),'\''.$item['class_name']);
				if(isset($item['school_name'])) $sheet->setCellValue('I'.($index+2),$item['school_name']);
				if(isset($item['district_name'])) $sheet->setCellValue('J'.($index+2),$item['district_name']);
				if(isset($item['province_name'])) $sheet->setCellValue('K'.($index+2),$item['province_name']);

				if(isset($item['vip_expire'])) $sheet->setCellValue('L'.($index+2),util::TimeStampToDate($item['vip_expire']));

				if(isset($item['score'])) $sheet->setCellValue('M'.($index+2),$item['score']);
				if(isset($item['time'])) $sheet->setCellValue('N'.($index+2),$item['time']);
				if(isset($item['round_id'])) $sheet->setCellValue('O'.($index+2),$item['round_id']);
				if(isset($item['luot'])) $sheet->setCellValue('P'.($index+2),$item['luot']);
				if(isset($item['score_date'])){
					$s = date('H:i:s d/m/Y', $item['score_date']);
					$sheet->setCellValue('Q'.($index+2),'\''.$s);
				}
				if(isset($item['code'])) $sheet->setCellValue('R'.($index+2),$item['code']);

				if(isset($item['total_score_4'])) $sheet->setCellValue('S'.($index+2),$item['total_score_4']);
				if(isset($item['total_time_4'])) $sheet->setCellValue('T'.($index+2),$item['total_time_4']);
				if(isset($item['current_round_4'])) $sheet->setCellValue('U'.($index+2),$item['current_round_4']);
			}

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			$date = date('YmdHis');
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="export-"'.$date.'".xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}
	}
	echo null;
}

function Save($admin_info){
// 	var_dump($admin_info);
	global $redis;
	$jResponse = [];

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$name = isset($_POST['name'])?$_POST['name']:'';
	$email = isset($_POST['email'])?$_POST['email']:'';
	$birthday = isset($_POST['birthday'])?$_POST['birthday']:'';
	$province_id = isset($_POST['province_id'])?intval($_POST['province_id']):0;
	$province_name = isset($_POST['province_name'])?$_POST['province_name']:'';
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	$district_name = isset($_POST['district_name'])?$_POST['district_name']:'';
	$school_id = isset($_POST['school_id'])?intval($_POST['school_id']):0;
	$school_name = isset($_POST['school_name'])?$_POST['school_name']:'';
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):1;
	$class_name = isset($_POST['class_name'])?$_POST['class_name']:'';
	$mobile = isset($_POST['mobile'])?$_POST['mobile']:'';
	$active = (isset($_POST['active'])?$_POST['active']:'')=='true';

	if($name=='' || $birthday=='' || $province_id==0 || $district_id==0 || $school_id==0){
		$jResponse = [
			'error' => 3,
			'message' =>'Hãy nhập đủ dữ liệu'
		];
	}
	else{
		$UserData = UserData::getInstance();
		if (($timestamp = strtotime($birthday)) === false) {
			$birthday=null;
		} else {
			$birthday = new MongoDate($timestamp);
		}

		if($id>0){
			$data_update = $UserData->Update($id, $name, $birthday, $email, $mobile, $province_id, $province_name, $district_id, $district_name, $school_id, $school_name,$class_id, $class_name,$active);
			if($data_update['updatedExisting'] && $data_update['n']>0){
				$redis->delete(sprintf(user_info,$id));//update cache
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
			//$create_by = $admin_info['_id'];
			$data_insert = $UserData->Insert($name, $birthday, $email, $mobile, $province_id, $province_name, $district_id, $district_name, $school_id, $school_name, $class_id, $class_name,$active);
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
	}

	echo json_encode($jResponse);
}

function Change_Password(){
	//global $redis;
	$jResponse = [];

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$password = isset($_POST['password'])?$_POST['password']:'';

	if($password==''){
		$jResponse = [
			'error' => 3,
			'message' =>'Hãy nhập đủ dữ liệu'
		];
	}
	else{
		$UserData = UserData::getInstance();
		if($id > 0) {
			$password = util::sha256($password);
			$data_update = $UserData->Change_Password($id, $password);
			if($data_update['updatedExisting'] && $data_update['n'] > 0) {
				//$redis->delete(news_detail.$id);//update cache
				$jResponse = [
					'error' => 0,
					'message' =>'ok'
				];
			} else {
				$jResponse = [
					'error' => 81,
					'message' =>'Dữ liệu chưa thay đổi'
				];
			}
		}
	}

	echo json_encode($jResponse);
}

function Ban($admin_info){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id = isset($_POST['id'])? intval($_POST['id']): 0;
	$reason = isset($_POST['reason'])? $_POST['reason']: '';
	$status = (isset($_POST['status'])? $_POST['status']: '') == 'true';

	if($id > 0) {
		$UserData = UserData::getInstance();
		$data_delete = $status? $UserData->Ban($id, $admin_info['username'], $reason): $UserData->UnBan($id, $admin_info['username'], $reason);
		if($data_delete['n'] > 0) {
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
		} else {
			$jResponse = [
				'error' => 83,
				'message' => 'Dữ liệu chưa thay đổi'
			];
		}
	} else {
		$jResponse = [
			'error' => 80,
			'message' => 'Không có thông tin yêu cầu'
		];
	}
	echo json_encode($jResponse);
	// return $bOk;
}

//function UnBan($admin_info){
//	global $redis;
//
//	// $bOk = false;
//	$jResponse = [];
//	$id=isset($_POST['id'])?intval($_POST['id']):0;
//	$reason = isset($_POST['reason'])? $_POST['reason']: '';
//
//	if($id>0){
//		$UserData = UserData::getInstance();
//		$data_delete = $UserData->UnBan($id, $admin_info['username'], $reason);
//		if($data_delete['n']>0){
//			$jResponse = [
//				'error' => 0,
//				'message' => 'ok'
//			];
//		}
//		else{
//			$jResponse = [
//				'error' => 83,
//				'message' => 'Dữ liệu chưa thay đổi'
//			];
//		}
//	}
//	else{
//		$jResponse = [
//			'error' => 80,
//			'message' => 'Không có thông tin yêu cầu'
//		];
//	}
//	echo json_encode($jResponse);
//	// return $bOk;
//}

function Delete($admin_info){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id = isset($_POST['id'])? intval($_POST['id']): 0;
	$province_id = isset($_POST['province_id'])? intval($_POST['province_id']): 0;
	$district_id = isset($_POST['district_id'])? intval($_POST['district_id']): 0;
	$school_id = isset($_POST['school_id'])? intval($_POST['school_id']): 0;
	$reason = isset($_POST['reason'])? $_POST['reason']: '';
	if($id>0){
		$UserData = UserData::getInstance();
		$data_delete = $UserData->Delete($id, $admin_info['username'], $reason);
		if($data_delete['n']>0){
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
			//delete user info from cache
			$redis->delete(sprintf(user_info,$id));//update cache
			$redis->decr(count_member);//update count member
			for($type_id=1;$type_id<=4;$type_id++){
				//xóa dữ liệu thi
				$keys = $redis->keys(sprintf(score_user_hash_delete,$type_id,$id));
				if($keys && count($keys)>0){
					foreach($keys as $key=>$value){
						$redis->del($value);
					}
				}
				//xóa xếp hạng
				$redis->zrem(sprintf(rank_type,$type_id),$id);
				$redis->zrem(sprintf(rank_province,$type_id,$province_id),$id);
				$redis->zrem(sprintf(rank_district,$type_id,$district_id),$id);
				$redis->zrem(sprintf(rank_school,$type_id,$school_id),$id);
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
			'message' => 'Không có thông tin yêu cầu'
		];
	}
	echo json_encode($jResponse);
	// return $bOk;
}

function DeleteAvatar(){
	$jResponse = [];

	try {
		$id=isset($_POST['id'])?intval($_POST['id']):0;
		if($id>0){
			$path = sprintf(avatar_path,$id);
			if(file_exists($path)){
				if(unlink($path)){
					$jResponse['error']=0;
					$jResponse['message']='Xóa thành công';
				}
				else{
					$jResponse['error']=2;
					$jResponse['message']='Xóa không thành công';
				}
			}
			else{
				$jResponse['error']=1;
				$jResponse['message']='Không tìm thấy avatar';
			}
		}
	} catch (Exception $e) {
		$jResponse['error']=10000;
		$jResponse['message']=$e->getMessage();
	}
	finally{
		echo json_encode($jResponse);
	}
}

function AddExpireVip($admin_info){
	//global $redis;
	global $vip_day;

	$jResponse = [];

	$id = isset($_POST['id'])? intval($_POST['id']): 0;
	$money = isset($_POST['money'])? intval($_POST['money']): 0;
	$note = isset($_POST['note'])? $_POST['note']: '';

	if($id == 0) { // || $money == 0
		$jResponse = [
			'error' => 3,
			'message' => 'Hãy nhập đủ dữ liệu'
		];
	} else {
		global $db;
		//$db->execute('AddVipDay('. $id .',' . $day . ');');

		$UserData = UserData::getInstance();

		$userInfo = $UserData->getVipDay($id);
		if(isset($userInfo)) {
			$newDate = new MongoDate();

			if(isset($userInfo['vip_expire']) && $userInfo['vip_expire']->sec > $newDate->sec + 86400 * 3) {
				$jResponse = [
					'error' => 2,
					'message' => 'User đã được cộng ngày học rồi!'
				];
			} else {
				$day = $vip_day[$money];
				// $UserData->AddVipDay($id, $day);

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

				$jResponse = [
					'error' => 0,
					'message' => 'ok',
					'result' => $result
				];

				// payment log
				$PaymentLogData = PaymentLogData::getInstance();
				$PaymentLogData->Insert($admin_info['username'], $id, $day, $money, $userInfo['vip_expire'], $date_update, util::ReplaceHTML($note));
			}
		} else {
			$jResponse = [
				'error' => 1,
				'message' => 'Người dùng không tồn tại'
			];
		}
	}

	echo json_encode($jResponse);
}


function TopScore(){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	//province:province,district:district,school:school,class_id:class_id,limit:limit
	$province_id = isset($_POST['province'])?intval($_POST['province']):0;
	$district_id = isset($_POST['district'])?intval($_POST['district']):0;
	$school_id = isset($_POST['school'])?intval($_POST['school']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$limit = isset($_POST['limit'])?intval($_POST['limit']):30;
	$round = isset($_POST['round'])?intval($_POST['round']):1;

	$where = [];
	if($province_id>0) $where['province_id']=$province_id;
	if($district_id>0) $where['district_id']=$district_id;
	if($school_id>0) $where['school_id']=$school_id;
	if($class_id>0) $where['class_id']=$class_id;
	//if($round>0) $where['round_number_4']=['$gte'=>$round);

	$UserData = UserData::getInstance();
	$data = $UserData->TopScore($where,$limit);
	$jResponse=[
		'content'=>$data,
		'error'=>0,
		'message'=>'done'
	];
	echo json_encode($jResponse);
	// return $bOk;
}

function ExamSchool(){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$state = isset($_POST['state'])?$_POST['state']:'';
	$state = $state == 'true';

	$UserData = UserData::getInstance();
	$data = $UserData->SetExamSchool($id,$state);
	$jResponse=[
		'id'=> $id,
		'content'=>$data,
		'error'=>0,
		'message'=>'done'
	];
	echo json_encode($jResponse);
	// return $bOk;
}

function ExamDistrict(){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$state = isset($_POST['state'])?$_POST['state']:'';
	$state = $state == 'true';

	$UserData = UserData::getInstance();
	$data = $UserData->SetExamDistrict($id,$state);
	$jResponse=[
		'id'=> $id,
		'content'=>$data,
		'error'=>0,
		'message'=>'done'
	];
	echo json_encode($jResponse);
	// return $bOk;
}

function ExamProvince(){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$state = isset($_POST['state'])?$_POST['state']:'';
	$state = $state == 'true';

	$UserData = UserData::getInstance();
	$data = $UserData->SetExamProvince($id,$state);
	$jResponse=[
		'id'=> $id,
		'content'=>$data,
		'error'=>0,
		'message'=>'done'
	];
	echo json_encode($jResponse);
	// return $bOk;
}
function ExamNational(){
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$state = isset($_POST['state'])?$_POST['state']:'';
	$state = $state == 'true';

	$UserData = UserData::getInstance();
	$data = $UserData->SetExamNational($id,$state);
	$jResponse=[
		'id'=> $id,
		'content'=>$data,
		'error'=>0,
		'message'=>'done'
	];
	echo json_encode($jResponse);
	// return $bOk;
}

function Email(){
	// $from, $fromName, $to, $subject, $body
	$from = isset($_POST['from'])? $_POST['from']: '';
	$fromName = isset($_POST['fromName'])? $_POST['fromName']: '';
	$to = isset($_POST['to'])? $_POST['to']: '';
	$subject = isset($_POST['subject'])? $_POST['subject']: '';
	$body = isset($_POST['body'])? $_POST['body']: '';

	$list_emails = [];
	$arr_email = explode(',', $to);
	foreach ($arr_email as $email){
		array_push($list_emails, trim($email));
	}

	$result = SendEmail($from, $fromName, $list_emails, $subject, $body);

	$jResponse = [
		'content' => $result,
		'error' => $result == true ? 0: 1000,
		'message' => $result == true? 'done': $result
	];

	echo json_encode($jResponse);
}