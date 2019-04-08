<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
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

if(isset($_POST['action'])) $action=$_POST['action'];
if(isset($action)){
	if(CheckPermission($admin_info['_id'],'category',$action)){
		$bok = false;
		switch ($action){
			case 'get_info':
				$bok = Get_Info();
				break;
// 			case 'youtube':
// 				$bok = Get_Youtube();
// 				break;
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

function Get_Info(){
	$jResponse = [];
	$cmd = 'timeout 5 ffprobe -v quiet -print_format json -show_format -show_streams "%s"';
	$file = isset($_POST['file'])?$_POST['file']:'';
	if($file!=''){
		if(startsWith($file,'http') || startsWith($file,'https')){
			//nothing
		}
		else{
			$file = urldecode($file);
			$file = join(DIRECTORY_SEPARATOR, [getcwd(), '..', $file]);
		}
		$cmd = sprintf($cmd,$file);
		exec($cmd,$file_content);
		$jResponse = [
			'error' => 0,
			'message' => 'ok',
			'cmd'=>$cmd,
			'content' => json_decode(implode(" ",$file_content),true)
		];
	}
	else{
		$jResponse = [
			'error' => 1,
			'message' => 'File input'
		];
	}
	echo json_encode($jResponse);
}

// function Get_Youtube(){
// 	echo getYoutubeDuration("vE-v52WsWdo");
// }

// function getYoutubeDuration($videoid) {
// 	$xml = simplexml_load_file('https://gdata.youtube.com/feeds/api/videos/' . $videoid . '?v=2');
// 	$result = $xml->xpath('//yt:duration[@seconds]');
// 	$total_seconds = (int) $result[0]->attributes()->seconds;

// 	return $total_seconds;
// }

function startsWith($haystack, $needle)
{
	return strncmp($haystack, $needle, strlen($needle)) === 0;
}

function endsWith($haystack, $needle)
{
	return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
}