<?php
//include 'ModelBase.php';

class DistrictsData extends ModelBase{
	protected static $collection_name = 'districts';
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
		'name'=>1
	];
	
	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],self::$select_field);
		/*
		return NULL | object
		*/
	}

	public function count($where){
		$cursor = self::$collection->find($where);
		return $cursor->count();
	}
	
	public function GetList($province_id){
		$cursor = self::$collection->find(
			['province_id'=>intval($province_id)],
			self::$select_field
		);
		//$cursor->sort(['_id' => 1));
		$count = $cursor->count();
		if($count>0) {
			$arr = [];
			foreach($cursor as $item)
			{
				array_push($arr, $item);
			}
			return $arr;
		}
		return null;
	}
	
	public function Search($province_id,$name){
		$cursor = self::$collection->find(
		[
			'province_id'=>intval($province_id),
			'name' => new MongoRegex('/'.$name.'/i')
		],self::$select_field);
		$cursor->sort(['_id' => 1]);
		$count = $cursor->count();
		if($count>0) {
			$arr = [];
			foreach($cursor as $item)
			{
				array_push($arr, $item);
			}
			return ['list'=>$arr,'count'=>$count];
		}
		return ['list'=>null,'count'=>0];
	}
	
	public function CheckExistName($name,$id,$province_id){
		$where = [
			'name' => $name,
			'province_id' => $province_id,
			'_id' => ['$ne'=>$id]
		];
		//var_dump($where);
		return self::$collection->find($where)->count();
	// 	/*
	// 	return 1 | 0
	// 	*/
	}

	// public function CheckExistKoDau($name){
	// 	return self::$collection->find(['name_ko_dau' => $name))->count();
	// 	/*
	// 	return 1 | 0
	// 	*/
	// }
	
	//private function GetNextId(){
	//	global $db;
	//	$NextIdObj= $db->execute('getNextSequence("'. self::$collection_name .'");');
	//	return intval($NextIdObj['retval']);
	//}

	public function Insert($province_id,$name){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'province_id' => intval($province_id),
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
	
	public function Update($id,$name){
		$item = [
			'name' => $name
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