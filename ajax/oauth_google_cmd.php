<?php
	require_once '../util/util.php';
	require_once '../config/config.php';
	if (!class_exists('ModelBase')) {
		require_once '../model/ModelBase.php';
	}
	require_once '../model/UserAdminData.php';
	require_once '../model/LogAdminData.php';

	if(!isset($_SESSION)){
		session_start();
	}
	
	$jResponse = null;
	
	if(!isset($_SESSION[session_user])){
		$access_token=isset($_GET['access_token'])?$_GET['access_token']:'';
		
		if($access_token!=''){
			//$content = file_get_contents('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$access_token);
			$content = file_get_contents('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$access_token);
			$content_obj = json_decode($content,true);
			
			if(isset($content_obj['error'])){
				$jResponse = [
					'error'=>6,
					'message'=>content.error
				];
			}
			else{
				$user_id = $content_obj['user_id'];
				$email = $content_obj['email'];
				
				$userAdminData = UserAdminData::getInstance();
				$userInfo = $userAdminData->LoginByEmail($email);
				
				if($userInfo!=null){
					if($userInfo['active']){
						$_SESSION[session_user] = $userInfo;
						$jResponse = [
							'error' => 0,
							'message' => 'Đăng nhập thành công',
							'email' => $email
						];
						
						// $userAdminData->Save_Login($userInfo['id'],util::GetIpClient());
						$LogAdminData = LogAdminData::getInstance();
						$LogAdminData->Insert([
							'action' => 'login-google',
							'ip' => util::GetIpClient(),
							'username' => $userInfo['username']
						]);
						
						//if($remember==1){
						//	$expire = time() + 30*24*60*60;
						//	setcookie(cookie_login, $password.$userInfo['_id'], $expire,"/");
						//}
					}
					else{
						$jResponse = [
							'error' => 7,
							'message' => 'Tài khoản bị khóa hoặc không còn sử dụng'
						];
					}
				}
				else{
					$jResponse = [
						'error' => 7,
						'message' => 'thông tin đăng nhập không đúng'
					];
				}
			}
		}
		else{
			$jResponse = [
				'error' => 100,
				'message' => 'chưa đăng nhập accout Google'
			];
		}
	}
	else{
		$jResponse = [
			'error' => 0,
			'message' => ''
		];
	}
	
	echo json_encode($jResponse);