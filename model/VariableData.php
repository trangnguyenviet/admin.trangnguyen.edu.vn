<?php
class VariableData{
	protected static $collection_name = 'variables';
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
		'value'=>1
	];
	
	public function GetInfo($id){
		return self::$collection->findOne(['_id' => $id],self::$select_field);
		/*
		return NULL | object
		*/
	}
	
	public function GetList($list_id){
		$cursor = self::$collection->find(
			['_id'=>$list_id],
			self::$select_field
		);
		
		$count = $cursor->count();
		if($count>0) {
			$arr = [];
			foreach($cursor as $item)
			{
				$arr[$item['_id']] = $item['value'];
			}
			return $arr;
		}
		return null;
	}
	
	public function CheckExistKey($id){
		return self::$collection->find(['_id' => $id])->count();
		/*
		return 1 | 0
		*/
	}
	
	public function Insert($id,$value){
		$item = [
			'_id' => $id,
			'value' => $value
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
			'ok'=>1,
			'_id'=>9
		)
		*/
	}
	
	public function Update($id,$value){
		$item = [
			'value' => $value
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
	
	public function Save($id,$value){
		$item = [
			'value' => $value
		];
		return self::$collection->update(['_id' => $id],['$set' => $item],['upsert'=>true,'multiple' => false]);
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
		
		return self::$collection->remove(['_id' => $id]);
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