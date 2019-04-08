<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 17/01/2017
 * Time: 21:52
 */
class ExampleCodeData{
	protected static $collection_name = 'example_codes';
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

	private $select_fields = [
		'_id' => 1, //code = _id
		'type' => 1, // 0 = free | 1 = national | 2 = province | 3 = district | 4 = school
		'province_id' => 1,
		'district_id' => 1,
		'school_id' => 1,
		'class_id' => 1,
		'begin_use' => 1, //timestamp
		'end_use' => 1, //timestamp
		'user_create' => 1, //user_id
		'created_at' => 1, //timestamp
		'updated_at' => 1, //timestamp
		'active' => 1 // true/false
	];

	public function GetInfo($id){
		return self::$collection->findOne([
			'_id' => intval($id)
		],$this->select_fields);
		/*
		return NULL | object
		*/
	}

	public function GetList($type, $province_id = 0, $district_id = 0, $school_id = 0){
		$query = [
			'type' => $type
		];

		if($province_id>0){
			$query['province_id'] = $province_id;
			if($district_id>0){
				$query['district_id'] = $district_id;
				if($school_id>0){
					$query['school_id'] = $school_id;
				}
			}
		}

		$cursor = self::$collection->find($query,$this->select_fields);
		$cursor->sort(['created_at' => 1]);
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

	public function CheckExist($id){
		$where = [
			'_id' => ['$ne'=>$id]
		];
		return self::$collection->find($where)->count()>0;
		/*
		return true | false
		*/
	}

	public function Insert($code, $type, $province_id, $district_id, $school_id, $class_id,
		$begin_use, $end_use, $user_create, $created_at, $updated_at, $active){
		$item = [
			'_id' => $code,
			'type' => $type,
			'province_id' => $province_id,
			'district_id' => $district_id,
			'school_id' => $school_id,
			'class_id' => $class_id,
			'begin_use' => $begin_use,
			'end_use' => $end_use,
			'user_create' => $user_create,
			'created_at' => $created_at,
			'updated_at' => $updated_at,
			'active' => $active
		];
		$data_insert = self::$collection->insert($item);
		return $data_insert;
		/*
		return [
			'connectionId'=>2,
			'n'=>0,
			'syncMillis'=>0,
			'writtenTo'=>NULL,
			'err'=>NULL,
			'ok'=>1
		)
		*/
	}

	public function Update($id, $begin_use, $end_use, $active, $updated_at, $class_id){
		return self::$collection->update([
			'_id' => $id
		],[
			'$set' => [
				'class_id' => $class_id,
				'active' => $active,
				'begin_use' => $begin_use,
				'end_use' => $end_use,
				'updated_at' => $updated_at
			]
		],[
			'upsert' => false,
			'multiple' => false
		]);
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
}