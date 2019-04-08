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
	<?php
		if(captcha_login){
			echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
		}
	?>
	<script src="/js/login.js" type="text/javascript"></script>
</head>
<body>
	<section class="container">
		<div class="row">
			<div class="col-sm-12 hidden-xs" style="margin-top: 5%;"></div>
			<div class="col-sm-offset-4 col-sm-4 col-xs-12 login">
				<h1>Admin login</h1>
				<form role="form" onsubmit="return false;">
					<div class="form-group">
						<label for="tb_username">Username:</label>
						<input type="text" class="form-control" id="tb_username" placeholder="Username" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label for="tb_password">Password:</label>
						<input type="password" class="form-control" placeholder="Password" maxlength="20" id="tb_password" required>
					</div>
					<?php 
						if(captcha_login) echo '<div class="form-group"><div class="g-recaptcha" data-sitekey="'.captcha_sitekey.'"></div></div>';
					?>
					<div class="checkbox">
						<label><input type="checkbox" id="remember_me"> Remember me</label>
					</div>
					<p class="message" id="lb_msg"></p>
					<div class="text-center">
						<button type="button" class="form-control btn btn-primary" id="bt_submit">Login</button>
					</div>
				</form>
				<div class="login-help text-center">
					<p><a class="btn btn-info" href="#">Forgot password</a> <a class="btn btn-info" href="https://accounts.google.com/o/oauth2/v2/auth?scope=email%20profile&state=admin-login&redirect_uri=http%3A%2F%2Fadmin.trangnguyen.edu.vn%2Flogin-google.php&response_type=token&client_id=143091150542-s4v3idbma05i15u55kddi7bjisjapuk3.apps.googleusercontent.com"><i class="fa fa-google"></i> Google accout</a></p>
					<!--<p><a class="btn btn-info" href="#">Forgot password</a> <a class="btn btn-info" href="https://accounts.google.com/o/oauth2/v2/auth?scope=email%20profile&state=admin-login&redirect_uri=http%3A%2F%2Fadmins.trangnguyen.edu.vn%2Flogin-google.php&response_type=token&client_id=143091150542-78uilgaone9oe7dmfg124f051b1c2ovr.apps.googleusercontent.com"><i class="fa fa-google"></i> Google accout</a></p>-->
				</div>
			</div>
		</div>
	</section>
	<div id="spinner" style=" position: fixed; top: 0; right: 0; padding: 0px 20px;z-index:999999; background: rgba(0, 0, 0, 0.62); border: 1px solid #020202; border-top: 0; border-right: 0; border-bottom-left-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; -ms-border-bottom-left-radius: 5px; display: none; "><!--?xml version="1.0" encoding="utf-8"?--> <svg width="32px" height="32px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ellipsis"> <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect> <circle cx="34.7825" cy="50" r="0" fill="#ffffff"> <animate id="anir11" attributeName="r" from="0" to="15" begin="0s;anir14.end" dur="0.125s" fill="freeze"></animate> <animate id="anir12" attributeName="r" from="15" to="15" begin="anir11.end" dur="0.625s" fill="freeze"></animate> <animate id="anir13" attributeName="r" from="15" to="0" begin="anir12.end" dur="0.125s" fill="freeze"></animate> <animate id="anir14" attributeName="r" from="0" to="0" begin="anir13.end" dur="0.125s" fill="freeze"></animate> <animate id="anix11" attributeName="cx" from="16" to="16" begin="0s;anix18.end" dur="0.125s" fill="freeze"></animate> <animate id="anix12" attributeName="cx" from="16" to="16" begin="anix11.end" dur="0.125s" fill="freeze"></animate> <animate id="anix13" attributeName="cx" from="16" to="50" begin="anix12.end" dur="0.125s" fill="freeze"></animate> <animate id="anix14" attributeName="cx" from="50" to="50" begin="anix13.end" dur="0.125s" fill="freeze"></animate> <animate id="anix15" attributeName="cx" from="50" to="84" begin="anix14.end" dur="0.125s" fill="freeze"></animate> <animate id="anix16" attributeName="cx" from="84" to="84" begin="anix15.end" dur="0.125s" fill="freeze"></animate> <animate id="anix17" attributeName="cx" from="84" to="84" begin="anix16.end" dur="0.125s" fill="freeze"></animate> <animate id="anix18" attributeName="cx" from="84" to="16" begin="anix17.end" dur="0.125s" fill="freeze"></animate> </circle> <circle cx="16" cy="50" r="15" fill="#0084ff"> <animate id="anir21" attributeName="r" from="15" to="15" begin="0s;anir25.end" dur="0.5s" fill="freeze"></animate> <animate id="anir22" attributeName="r" from="15" to="0" begin="anir21.end" dur="0.125s" fill="freeze"></animate> <animate id="anir23" attributeName="r" from="0" to="0" begin="anir22.end" dur="0.125s" fill="freeze"></animate> <animate id="anir24" attributeName="r" from="0" to="15" begin="anir23.end" dur="0.125s" fill="freeze"></animate> <animate id="anir25" attributeName="r" from="15" to="15" begin="anir24.end" dur="0.125s" fill="freeze"></animate> <animate id="anix21" attributeName="cx" from="16" to="50" begin="0s;anix28.end" dur="0.125s" fill="freeze"></animate> <animate id="anix22" attributeName="cx" from="50" to="50" begin="anix21.end" dur="0.125s" fill="freeze"></animate> <animate id="anix23" attributeName="cx" from="50" to="84" begin="anix22.end" dur="0.125s" fill="freeze"></animate> <animate id="anix24" attributeName="cx" from="84" to="84" begin="anix23.end" dur="0.125s" fill="freeze"></animate> <animate id="anix25" attributeName="cx" from="84" to="84" begin="anix24.end" dur="0.125s" fill="freeze"></animate> <animate id="anix26" attributeName="cx" from="84" to="16" begin="anix25.end" dur="0.125s" fill="freeze"></animate> <animate id="anix27" attributeName="cx" from="16" to="16" begin="anix26.end" dur="0.125s" fill="freeze"></animate> <animate id="anix28" attributeName="cx" from="16" to="16" begin="anix27.end" dur="0.125s" fill="freeze"></animate> </circle> <circle cx="50" cy="50" r="15" fill="#ffffff"> <animate id="anir31" attributeName="r" from="15" to="15" begin="0s;anir35.end" dur="0.25s" fill="freeze"></animate> <animate id="anir32" attributeName="r" from="15" to="0" begin="anir31.end" dur="0.125s" fill="freeze"></animate> <animate id="anir33" attributeName="r" from="0" to="0" begin="anir32.end" dur="0.125s" fill="freeze"></animate> <animate id="anir34" attributeName="r" from="0" to="15" begin="anir33.end" dur="0.125s" fill="freeze"></animate> <animate id="anir35" attributeName="r" from="15" to="15" begin="anir34.end" dur="0.375s" fill="freeze"></animate> <animate id="anix31" attributeName="cx" from="50" to="84" begin="0s;anix38.end" dur="0.125s" fill="freeze"></animate> <animate id="anix32" attributeName="cx" from="84" to="84" begin="anix31.end" dur="0.125s" fill="freeze"></animate> <animate id="anix33" attributeName="cx" from="84" to="84" begin="anix32.end" dur="0.125s" fill="freeze"></animate> <animate id="anix34" attributeName="cx" from="84" to="16" begin="anix33.end" dur="0.125s" fill="freeze"></animate> <animate id="anix35" attributeName="cx" from="16" to="16" begin="anix34.end" dur="0.125s" fill="freeze"></animate> <animate id="anix36" attributeName="cx" from="16" to="16" begin="anix35.end" dur="0.125s" fill="freeze"></animate> <animate id="anix37" attributeName="cx" from="16" to="50" begin="anix36.end" dur="0.125s" fill="freeze"></animate> <animate id="anix38" attributeName="cx" from="50" to="50" begin="anix37.end" dur="0.125s" fill="freeze"></animate> </circle> <circle cx="84" cy="50" r="15" fill="#0084ff"> <animate id="anir41" attributeName="r" from="15" to="0" begin="0s;anir44.end" dur="0.125s" fill="freeze"></animate> <animate id="anir42" attributeName="r" from="0" to="0" begin="anir41.end" dur="0.125s" fill="freeze"></animate> <animate id="anir43" attributeName="r" from="0" to="15" begin="anir42.end" dur="0.125s" fill="freeze"></animate> <animate id="anir44" attributeName="r" from="15" to="15" begin="anir43.end" dur="0.625s" fill="freeze"></animate> <animate id="anix41" attributeName="cx" from="84" to="84" begin="0s;anix48.end" dur="0.125s" fill="freeze"></animate> <animate id="anix42" attributeName="cx" from="84" to="16" begin="anix41.end" dur="0.125s" fill="freeze"></animate> <animate id="anix43" attributeName="cx" from="16" to="16" begin="anix42.end" dur="0.125s" fill="freeze"></animate> <animate id="anix44" attributeName="cx" from="16" to="16" begin="anix43.end" dur="0.125s" fill="freeze"></animate> <animate id="anix45" attributeName="cx" from="16" to="50" begin="anix44.end" dur="0.125s" fill="freeze"></animate> <animate id="anix46" attributeName="cx" from="50" to="50" begin="anix45.end" dur="0.125s" fill="freeze"></animate> <animate id="anix47" attributeName="cx" from="50" to="84" begin="anix46.end" dur="0.125s" fill="freeze"></animate> <animate id="anix48" attributeName="cx" from="84" to="84" begin="anix47.end" dur="0.125s" fill="freeze"></animate> </circle> </svg></div>
</body>
</html>