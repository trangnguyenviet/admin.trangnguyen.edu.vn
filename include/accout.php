<?php
	//require_once './config/config.php';
	//require_once './util/util.php';
	if(!isset($_SESSION)){
		session_start();
	}

	$user_info=null;
	if(!isset($_SESSION[session_user])){
		header("Location: login.php?type=timeout");
		die();
	}
	else{
		$user_info = $_SESSION[session_user];
	}
?>
<li class="dropdown user user-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<img src="/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
		<span class="hidden-xs"><?php echo $user_info['username'];?></span>
	</a>
	<ul class="dropdown-menu">
		<li class="user-header">
			<img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
			<p>
				<?php echo $user_info['username'].' - ' . $user_info['fullname'];?>
				<small>Thành viên từ Tháng 10 - 2015</small>
			</p>
		</li>
		<!-- Menu Body -->
		<li class="user-body">
			<div class="col-xs-4 text-center">
				<a href="#">Followers</a>
			</div>
			<div class="col-xs-4 text-center">
				<a href="#">Sales</a>
			</div>
			<div class="col-xs-4 text-center">
				<a href="#">Friends</a>
			</div>
		</li>
		<!-- Menu Footer-->
		<li class="user-footer">
			<div class="pull-left">
				<a href="/profiler.html" class="btn btn-default btn-flat">Profile</a>
			</div>
			<div class="pull-right">
				<a href="/logout.php" class="btn btn-default btn-flat logout">Sign out</a>
			</div>
		</li>
	</ul>
</li>