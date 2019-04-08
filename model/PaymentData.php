<?php
//include 'ModelBase.php';

class PaymentData extends ModelBase{
	protected static $collection_name = 'payments';
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
		'_id' => 1,
		'user_id' => 1,
		'network' => 1,
		'card_number' => 1,
		'card_serial' => 1,
		'done' => 1,
		'amout' => 1,
		'created_at' => 1,
		'res_body' => 1,
	];
	
	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],self::$select_field);
		/*
		return NULL | object
		*/
	}
	
	public function GetList($user_id,$page_size,$page_index,$done_status){
		$where = ['user_id'=>$user_id];
		if($done_status!=''){
			if($done_status=='1') $where['done'] = true;
			else $where['done'] = false;
		}
		$cursor = self::$collection->find($where,self::$select_field);
		
		$cursor->sort(['created_at'=>-1]);
		$count = $cursor->count();
		if($count>0) {
			$cursor->skip($page_size*$page_index);
			$cursor->limit($page_size);
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				
				array_push($arr, $item);
			}
			return ['list'=>$arr,'count'=>$count];
		}
		return ['list'=>null,'count'=>0];
	}
	
	public function GetListUserDone(){
		//return array
		return self::$collection->distinct('user_id',['done'=>true]);
	}
	
	public function Search($number){
		$where = ['form.pin_card'=>new MongoRegex('/'.$number.'/i')];
		$cursor = self::$collection->find($where,[
			'_id' => 1,
			'user_id' => 1,
			'done' => 1,
			'amout' => 1,
			'created_at' => 1,
			'res_body' => 1,
			'form.pin_card'=>1,
			'form.card_serial'=>1,
			'form.ref_code'=>1,
			'form.type_card'=>1
		]);
		
		$cursor->sort(['created_at'=>-1]);
		$count = $cursor->count();
		if($count>0) {
			//$cursor->skip($page_size*$page_index);
			//$cursor->limit($page_size);
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				array_push($arr, $item);
			}
			return ['list'=>$arr,'count'=>$count];
		}
		return ['list'=>null,'count'=>0];
	}
	
	//private function GetNextId(){
	//	global $db;
	//	$NextIdObj= $db->execute('getNextSequence("'. self::$collection_name .'");');
	//	return intval($NextIdObj['retval']);
	//}

	public function Insert($object){
		$new_id = self::GetNextId();
		$object['_id'] = $new_id;
		$object['created_date'] = new MongoDate();
		$data_insert = self::$collection->insert($object);
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