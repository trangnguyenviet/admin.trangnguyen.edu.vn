<?php
require_once 'ModelBase.php';

class ExamLevelTypeData extends ModelBase {
	protected static $collection_name = 'exam_level_types';
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

	private static $select_field = [
		'_id'=>1,
		'class_id'=>1,
		'subject_id'=>1,
		'subject_rewrite' => 1,
		'name' => 1,
		'description' => 1,
		'thumb' => 1,
		'level' => 1,
		'created_at'=>1,
		'updated_at'=>1
	];

	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],self::$select_field);
		/*
		return NULL | object
		*/
	}

	public function GetListShow($class_id, $subject_id){
		$cursor = self::$collection->find([
			'class_id' => $class_id,
			'subject_id' => $subject_id
		], self::$select_field);

		if($cursor->count() > 0) {
			$arr = [];
			foreach($cursor as $item) {
				array_push($arr, $item);
			}
			return $arr;
		}
		return null;
	}

	public function CopyData($id, $subject_id, $class_id) {
		$info = self::$collection->findOne(
			[
				'_id' => $id
			]);

		if(isset($info)) {
			$info['_id'] = self::GetNextId();
			$info['class_id'] = $class_id;
			$info['subject_id'] = $subject_id;

			$data_insert = self::$collection->insert($info);
			if(isset($data_insert) && $data_insert['ok'] > 0) return true;
		}
		return false;
	}

	public function Insert($name, $thumb, $rewrite, $class_id, $subject_id, $subject_rewrite, $description, $level){
		$new_id = self::GetNextId();
		$date = new MongoDate();
		$item = [
			'_id' => $new_id,
			'name' => $name,
			'thumb' => $thumb,
			'rewrite' => $rewrite,
			'class_id' => $class_id,
			'subject_id' => $subject_id,
			'subject_rewrite' => $subject_rewrite,
			'description' => $description,
			'created_at' => $date,
			'updated_at' => $date,
			'level' => $level
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

	public function Update($id, $name, $thumb, $rewrite, $description, $level) {
		$item = [
			'name' => $name,
			'thumb' => $thumb,
			'rewrite' => $rewrite,
			'description' => $description,
			'level' => $level,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update(['_id' => $id], ['$set' => $item], ['upsert' => false, 'multiple' => false]);
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
		//return self::$collection->update(['_id' => $id),['$set' => ['deleted'=>true)),['upsert'=>false,'multiple' => false));

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