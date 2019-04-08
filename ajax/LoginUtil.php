<?php
//require_once '../util/util.php';
//require_once '../config/config.php';
//require_once '../model/UserAdminData.php';

if(!isset($_SESSION)){
	session_start();
}

function Relogin(){
	require_once './util/util.php';
	require_once './model/UserAdminData.php';
	require_once './model/LogAdminData.php';

	if(isset($_COOKIE[cookie_login])){
		$cookie = $_COOKIE[cookie_login];
		$admin_info = CheckLogin($cookie);
		if($admin_info!=null){
			$_SESSION[session_user] = $admin_info;
			return $admin_info;
		}
		else{
			setcookie(cookie_login, null, -1);
		}
	}
	header("Location: /login.php?type=timeout");
	return null;
	die();
}

function ReloginAjax(){
	require_once '../util/util.php';
	require_once '../model/UserAdminData.php';
	require_once '../model/LogAdminData.php';

	if(isset($_COOKIE[cookie_login])){
		$cookie = $_COOKIE[cookie_login];
		$user_info = CheckLogin($cookie);
		if($user_info!=null){
			$_SESSION[session_user] = $user_info;
			return true;
		}
		else{
			setcookie(cookie_login, null, -1);
		}
	}
	return false;
}

function CheckLogin($str_remembe){
	if(strlen($str_remembe)>=64){
		$password = substr($str_remembe, 0,64);
		$user_id = substr($str_remembe, 64);
		if(util::CheckOnlyNumber($user_id)){
			$user_id = intval($user_id);
			$userAdminData = UserAdminData::getInstance();
			$user_admin_info = $userAdminData->Login_2($user_id, $password);
			if($user_admin_info!=null){
				$LogAdminData = LogAdminData::getInstance();
				$log_info = [
					'action' => 'login-cookie',
					'user_id' => $user_id,
					'ip' => util::GetIpClient(),
					'url_refer' => util::GetUrlRefer()
				];
				$LogAdminData->Insert($log_info);
			}
			return $user_admin_info;
		}
		else{
			return null;
		}
	}
	return null;
}