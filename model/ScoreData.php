<?php
//include 'ModelBase.php';

class ScoreData extends ModelBase{
	protected static $collection_name = 'scores';
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
		'name'=>1,
		'time' => 1,
		'score' => 1,
		'round_id' => 1,
		'luot' => 1,
		'created_at'=>1,
		'code' => 1,
		'user_id' => 1
	];
	
	public function CheckExist($user_id,$type_id,$round_id){
		$where = [
			//'_id' => ['$ne'=>$id)
			'user_id' => $user_id,
			'type_id' => $type_id,
			'round_id' => $round_id
		];
		return self::$collection->find($where)->count()>0;
		/*
		return 1 | 0
		*/
	}
	
	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],self::$select_field);
		/*
		return NULL | object
		*/
	}
	
	public function GetList($user_id,$type_id){
		$cursor = self::$collection->find(
			[
				'user_id'=>intval($user_id),
				'type_id' => $type_id
			],
			self::$select_field
		);
		$cursor->sort(['round_id'=>1,'_id' => 1]);
		$count = $cursor->count();
		if($count>0) {
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				array_push($arr, $item);
			}
			return $arr;
		}
		return null;
	}

	public function GetListUse($list_user,$round_id){
		$cursor = self::$collection->find([
				'user_id'=>['$in'=>$list_user],
				'round_id' => $round_id
			],
			self::$select_field
		);
		$cursor->sort(['score'=>-1,'time' => 1]);
		$count = $cursor->count();
		if($count>0) {
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				array_push($arr, $item);
			}
			return $arr;
		}
		return null;
	}
	
	public function GetListCode($code,$type_id){
		$cursor = self::$collection->find(
			[
				'code'=>$code,
				'type_id' => $type_id
			],
			self::$select_field
		);
		$cursor->sort(['score'=>-1,'luot' => 1,'time'=>1]);
		$count = $cursor->count();
		if($count>0) {
			$list = [];
			$list_user_id = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				array_push($list_user_id, $item['user_id']);
				array_push($list, $item);
			}
			return ['list_user_id'=>$list_user_id,'list'=>$list,'count' => $count];
		}
		return null;
	}
	
	public function GetListRound($user_id,$round_id,$type_id){
		return self::$collection->findOne(
			[
				'user_id'=>intval($user_id),
				'type_id' => $type_id,
				'round_id' => $round_id
			],
			[
				'time' => 1,
				'score' => 1,
				'created_at'=>1,
				'code' => 1
			]
		);
	}
	
	//private function GetNextId(){
	//	global $db;
	//	$NextIdObj= $db->execute('getNextSequence("'. self::$collection_name .'");');
	//	return intval($NextIdObj['retval']);
	//}
	
	public function Insert($type_id,$user_id,$time,$score,$round_id,$luot,$code,$created_at){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'type_id' => $type_id,
			'user_id' => $user_id,
			'time' => $time,
			'score' => $score,
			'round_id'=>$round_id,
			'luot' => $luot,
			'code' => $code,
			'created_at' => $created_at,//new MongoDate(),
			'updated_at' => new MongoDate()
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
	
	public function Update($id,$time,$score,$round_id,$luot){
		$item = [
			'time' => intval($time),
			'score' => intval($score),
			'round_id'=>intval($round_id),
			'luot' => intval($luot),
			'updated_at' => new MongoDate()
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
