<?php
	require_once '../util/util.php';
	require_once '../config/config.php';
	require_once '../model/UserAdminData.php';
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$jResponse = null;
	
	if(!isset($_SESSION[session_user])){
		$username=isset($_POST['username'])?$_POST['username']:'';
		$password=isset($_POST['password'])?$_POST['password']:'';
		$remember=isset($_POST['remember'])?intval($_POST['remember']):0;
		$captcha = isset($_POST['captcha'])?$_POST['captcha']:'';
		
		$bValidate = false;
		
		if(captcha_login) {
			if($captcha!='') {
				$data = [
					'secret' => '6Ld4EwsTAAAAAHn0TP89WveSDpDJEDivsuzlaBPh',
					'response' => $captcha,
					'remoteip' => util::GetIpClient()
				];
				$options = [
					'http' => [
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data),
						'timeout' => 60
					]
				];
				$context  = stream_context_create($options);
				$result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
				if ($result === FALSE) {
					$jResponse = [
						'error' => 1,
						'message' => 'Lỗi xác thực captcha'
					];
				} else {
					$captcha_obj = json_decode($result);
					if(isset($captcha_obj->success)) {
						if($captcha_obj->success) {
							$bValidate = true;
						} else {
							$jResponse = [
								'error' => 1,
								'message' => 'Lỗi xác thực captcha'
							];
						}
					} else {
						$jResponse = [
							'error' => 1,
							'message' => 'Lỗi xác thực captcha'
						];
					}
				}
				//var_dump($result);
				/*
				* {
					"success": true,
					"challenge_ts": "2016-05-03T09:38:49Z",
					"hostname": "admin.trangnguyen.edu.vn"
				}
				*/
			} else {
				$jResponse = [
					'error' => 1,
					'message' => 'Hãy xác nhận captcha'
				];
			}
		} else {
			$bValidate = true;
		}
		
		if($bValidate) {
			if($username!='' && $password!='') {
				if(util::CheckUsername($username)) {
					if(util::CheckPassword($password)) {
						$userAdminData = UserAdminData::getInstance();
						
						$password = util::sha256(password_append.$password);
						
						$userInfo = $userAdminData->Login($username, $password);
						if($userInfo!=null){
							if($userInfo['active']){
								$_SESSION[session_user] = $userInfo;
								$jResponse = [
									'error' => 0,
									'message' => 'Đăng nhập thành công'
								];
								
								//$userAdminData->Save_Login($userInfo['id'],util::GetIpClient());
								
								if($remember==1){
									$expire = time() + 30*24*60*60;
									setcookie(cookie_login, $password.$userInfo['_id'], $expire,"/");
								}
							} else {
								$jResponse = [
									'error' => 7,
									'message' => 'Tài khoản bị khóa hoặc không còn sử dụng'
								];
							}
						} else {
							$jResponse = [
								'error' => 7,
								'message' => 'thông tin đăng nhập không đúng'
							];
						}
					} else {
						$jResponse = [
							'error' => 113,
							'message' => 'password không phù hợp'
						];
					}
				} else {
					$jResponse = [
						'error' => 111,
						'message' => 'username không phù hợp'
					];
				}
			} else {
				$jResponse = [
					'error' => 100,
					'message' => 'chưa nhập đủ dữ liệu'
				];
			}
		} else {
			//show message
		}
	} else {
		$jResponse = [
			'error' => 0,
			'message' => ''
		];
	}
	
	echo json_encode($jResponse);
?>