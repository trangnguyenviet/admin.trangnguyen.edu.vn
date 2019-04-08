<?php
	include './config/config.php';
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	session_destroy();
	setcookie(cookie_login, '',time() -1,"/");
	//if(isset($_COOKIE['remember_' . server_id]))
	//unset($_COOKIE['remember_' . server_id]);
	//echo "remember_" . server_id;
	header("Location: /login.php?type=logout");
?>