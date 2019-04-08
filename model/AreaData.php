<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 07/11/2018
 * Time: 09:18
 */

class AreaData extends ModelBase
{
	protected static $collection_name = 'areas';
	private static $instance;
	private static $collection;

	public static function getInstance()
	{
		global $db;
		if (!self::$instance) {
			self::$instance = new self();
			self::$collection = $db->selectCollection(self::$collection_name);
		}
		return self::$instance;
	}

	private static $select_field = [
		'_id' => 1,
		'name' => 1
	];

	public function GetList() {
		$cursor = self::$collection->find([],
			self::$select_field
		);
		$cursor->sort(['_id' => 1]);
		$count = $cursor->count();
		if($count > 0) {
			return ['list' => $cursor, 'count' => $count];
		}
		return ['list'=>null,'count'=>0];
	}

	public function Insert($name) {
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'name' => $name
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

	public function Update($id, $name) {
		$item = [
			'name' => $name
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