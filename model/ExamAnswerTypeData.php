<?php
require_once 'ModelBase.php';

/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 12/12/2016
 * Time: 23:20
 */
class ExamAnswerTypeData extends ModelBase{
	protected static $collection_name = 'exam_answer_types';
	private static $instance;
	private static $collection;
	public static function getInstance() {
		global $db;
		if(!self::$instance) {
			self::$instance = new self();
			self::$collection = $db->selectCollection(self::$collection_name);
		}
		return self::$instance;
	}

	private $select_list = [
		'_id' => 1,
		'name' => 1,
		'name_ko_dau' => 1,
		'date_from' => 1,
		'date_to' => 1,
		'active' => 1
	];

	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],['name' => 1]);
		/*
		return NULL | object
		*/
	}

	public function GetList(){
		$cursor = self::$collection->find([], $this->select_list);
		//$cursor->sort(['sort' => 1]);
		if($cursor->count()>0) {
			$arr = [];
			foreach($cursor as $item)
			{
				array_push($arr, $item);
			}
			return $arr;
		}
		return null;
	}

	//private function GetNextId(){
	//	global $db;
	//	$NextIdObj= $db->execute('getNextSequence("'. self::$collection_name .'");');
	//	return intval($NextIdObj['retval']);
	//}

	public function Insert($name,$name_ko_dau,$date_from,$date_to,$active){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'name' => $name,
			'name_ko_dau' => $name_ko_dau,
			'date_from' => $date_from,
			'date_to' => $date_to,
			'active' => $active
		];
		$data_insert = self::$collection->insert($item);
		$data_insert['_id'] = $new_id;
		return $data_insert;
		/*
		return [
			'connectionId'=>2,
			'n'=>0,
			'syncMillis'=>0,
			'writtenTo'=>NULL,
			'err'=>NULL,
			'ok'=>1,
			'_id'=>9
		)
		*/
	}

	//public function Update($id,$name,$class_id,$name_ko_dau,$active,$sort){
	public function Update($id,$name,$name_ko_dau,$date_from,$date_to,$active){
		$item = [
			'name' => $name,
			'name_ko_dau' => $name_ko_dau,
			'date_from' => $date_from,
			'date_to' => $date_to,
			'active' => $active
		];
		return self::$collection->update(['_id' => $id],['$set' => $item],['upsert'=>false,'multiple' => false]);
		/*
		return [
			'connectionId'=>2,
			'updatedExisting' => true, //exist: 1 | not exist: 0
			'n'=>1,//update ok=1 | not ok: 0
			'syncMillis'=>0,
			'writtenTo'=>NULL,
			'err'=>NULL,
			'ok'=>1
		)
		*/
	}

	public function Delete($id){
		return self::$collection->remove(['_id' => intval($id)]);
		/*
		return [
			'connectionId'=>2,
			'n'=>1,//delete ok=1 | not ok: 0
			'syncMillis'=>0,
			'writtenTo'=>NULL,
			'err'=>NULL,
			'ok'=>1
		)
		*/
	}
}