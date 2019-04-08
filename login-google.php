<?php 
	include './config/config.php';
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if(isset($_SESSION[session_user])){
		header("Location: index.php?page=home");
		die();
	}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Login Form</title>
	<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/login.css">
	<!--[if lt IE 9]><script src="/plugins/jQuery/html5.js"></script><![endif]-->
	<script src="/plugins/jquery/dist/jquery.min.js"></script>
	<script src="/js/auth_google.js" type="text/javascript"></script>
</head>
<body>
	<section class="container">
		<div class="col-sm-12 hidden-xs" style="margin-top: 5%;"></div>
			<div class="col-sm-offset-4 col-sm-4 col-xs-offset-0 col-xs-12 login">
				<h1>Google accout</h1>
				<form role="form" onsubmit="return false;">
					<div class="form-group">
						<input type="text" class="form-control" id="tb_username" value="Đang kiểm tra thông tin..." placeholder="email" style="text-align: center;font-weight: bold;color: #472C94;" autocomplete="off" required>
					</div>
					<p class="message" id="lb_msg"></p>
					<div class="text-center">
						<button type="button" class="form-control btn btn-primary" id="bt_submit">Login as other accout</button>
					</div>
				</form>
			</div>
		</div>
	</section>
</body>
</html>