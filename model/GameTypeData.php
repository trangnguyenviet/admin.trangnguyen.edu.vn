<?php
//include 'ModelBase.php';

class GameTypeData extends ModelBase{
	protected static $collection_name = 'game_types';
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

	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],['_id'=>1,'name'=>1,'name_ko_dau'=>1]);
		/*
		return NULL | object
		*/
	}

	public function GetList(){
		$cursor = self::$collection->find(
			[],
			[
				'_id' => 1,
				'name' => 1,
				'name_ko_dau' => 1,
				'active' => 1,
				'sort' => 1
			]
		);
		$cursor->sort(['sort' => 1]);
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
	
	public function GetListActive(){
		$cursor = self::$collection->find(['active'=>true],['_id'=>1,'name'=>1]);
		$cursor->sort(['sort' => 1]);
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
	
	public function CheckExistName($name,$id){
		$where = [
			'name' => $name,
			'_id' => ['$ne'=>$id]
			//'_id' => $id
		];
		//var_dump($where);
		return self::$collection->find($where)->count();
		/*
		return 1 | 0
		*/
	}

	public function CheckExistKoDau($name){
		return self::$collection->find(['name_ko_dau' => $name])->count();
		/*
		return 1 | 0
		*/
	}

	//private function GetNextId(){
	//	global $db;
	//	$NextIdObj= $db->execute('getNextSequence("'. self::$collection_name .'");');
	//	return intval($NextIdObj['retval']);
	//}

	public function Insert($name,$name_ko_dau,$active,$sort){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'name' => $name,
			'name_ko_dau' => $name_ko_dau,
			'active' => $active,
			'sort' => intval($sort)
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
	public function Update($id,$name,$name_ko_dau,$active,$sort){
		$item = [
			'name' => $name,
			'name_ko_dau' => $name_ko_dau,
			'active' => $active,
			'sort' => intval($sort)
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