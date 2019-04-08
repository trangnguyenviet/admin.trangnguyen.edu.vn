<?php
//
define ('title', 'TrangNguyen Cpanel',true);
define('session_user', 'ss_user_tn', true);
define('password_append', 'tn', true);
define('cookie_login', 'data-tn', true);

define('captcha_login', true, true);
define('captcha_sitekey', '6Ld4EwsTAAAAAFOo7eKyX0VXn6tL6dYN1ebn2w-z', true);

//page size
define('page_size_news', 10, true);
define('page_size_lesson', 10, true);
define('page_size_animated', 10, true);
define('page_size_user', 100, true);
define('page_size_user_payment', 10, true);

define('avatar_path', $_SERVER['DOCUMENT_ROOT'].'/avatar/%d.jpg', true);///home/tanmv/admin/avatar/<user_id>.jpg

//config redis document: https://github.com/phpredis/phpredis
$redis_client = new Redis();
$redis_client->connect('10.0.0.50', 6379);
$GLOBALS['redis']=$redis_client;

//transfer node js
define('nodejs_url', 'http://localhost:8080/mvt-config/%s', true);

//redis keys
//local
define('local_province', 'province', true);
define('local_district', 'district_%d', true);//district_<province_id>
define('local_school', 'school_%d', true);//school_<district_id>
define('province_info', 'province_info_%d', true);
define('district_info', 'district_info_%d', true);//district_<province_id>
define('school_info', 'school_info_%d', true);//school_<district_id>

//param
define('param_global', 'param_global', true);
define('total_round_js', 'total_round_', true);//total_round_<type_id>
define('current_round_js', 'current_round_', true);//current_round_<type_id>
define('total_round', 'total_round_%d', true);//total_round_<type_id>
define('current_round', 'current_round_%d', true);//current_round_<type_id>
define('payment_round', 'payment_round_%d', true);//payment_round_<type_id>
define('payment_round_js', 'payment_round_', true);//payment_round_<type_id>
//
define('count_member', 'count_member', true);
define('news_detail', 'news_%d', true);//news_<news_id>
define('user_info', 'user_info_%d', true);//user_info_<user_id>
//rank
define('rank_type', 'rank_type_%d', true);//rank_type_<type_id>
define('rank_province', 'rank_province_%d_%d', true);//rank_province_<type_id>_<province_id>
define('rank_district', 'rank_district_%d_%d', true);//rank_district_<type_id>_<district_id>
define('rank_school', 'rank_school_%d_%d', true);//rank_school_<type_id>_<school_id>
//data exam
define('exam_info', 'exam_%d_%d_%d_%d', true);//exam_data_<type_id>_<class_id>_<round_id>_<test>
define('score_user_hash_delete', 'score_user_%d_*_%d', true);//score_user_<type_id>_<round_id>_<user_id>
define('score_user_hash', 'score_user_%d_%d_%d', true);//score_user_<type_id>_<round_id>_<user_id>
define('score_user_info_hash', 'score_user_info_%d_%d_%d', true);//score_user_<type_id>_<round_id>_<user_id>
define('score_user_score', 'score_%d', true);//score_<luot>
define('score_user_totaltime', 'totaltime_%d', true);//totaltime_<luot>
define('score_user_wrong', 'wrong_%d', true);//wrong_<luot>
define('score_user_luot', 'luot', true);

define('exam_event_game', 'exam_event_game_%d_%d', true);//exam_event_game_<type_id>_<class_id>
define('exam_answer_game', 'exam_answer_game_%d', true);//exam_answer_game_<id>

//game
define('category_game', 'category_game', true);
define('list_game', 'list_game_%d_*', true); //list_game_<category_id>_<page_size>_<page_index>
define('game_info', 'game_info_%d', true); //game_info_<game_id>
//end redis keys

$GLOBALS['list_game_info'] = [
	0 => ['game_name' => 'Web game', 'content_name' => 'câu hỏi', 'enable' => true],
	1 => ['game_name' => 'Chuột vàng', 'content_name' => 'chủ đề', 'enable' => true],
	2 => ['game_name' => 'Trâu vàng', 'content_name' =>'câu hỏi', 'enable' => true],
	3 => ['game_name' => 'Hổ con', 'content_name' => 'câu hỏi', 'enable' => true],
	4 => ['game_name' => 'Mèo con', 'content_name' =>'câu hỏi', 'enable' => true],
	5 => ['game_name' => 'Rồng', 'content_name' => 'câu hỏi', 'enable' => false],
	6 => ['game_name' => 'Rắn', 'content_name' => 'câu hỏi', 'enable' => false],
	7 => ['game_name' => 'Ngựa', 'content_name' => 'câu hỏi', 'enable' => true],
	8 => ['game_name' => 'Dê con', 'content_name' => 'câu hỏi', 'enable' => false],
	9 => ['game_name' => 'Khỉ con', 'content_name' => 'câu hỏi', 'enable' => false],
	10 => ['game_name' => 'Gà con', 'content_name' => 'câu hỏi', 'enable' => true],
	11 => ['game_name' => 'Chó con', 'content_name' => 'câu hỏi', 'enable' => false],
	12 => ['game_name' => 'Heo con', 'content_name' => 'câu hỏi', 'enable' => false]
];

//config db
$mongo = new MongoClient('mongodb://10.0.0.200:27017');
$GLOBALS['db'] = $mongo->selectDB('trangnguyen');//or $mongo->trangnguyen

//datetime config
date_default_timezone_set ('Asia/Ho_Chi_Minh');