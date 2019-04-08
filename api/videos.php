<?php
require_once '../model/ModelBase.php';
require_once '../config/config.php';
require_once '../model/VideoData.php';
header("Content-type: application/json;charset=utf-8");

$VideoData = VideoData::getInstance();
$data = $VideoData->ListParent([10, 11, 12]);

$jResponse = [
	'error' => 0,
	'message' => 'ok',
	'list' => $data
];

echo json_encode($jResponse);