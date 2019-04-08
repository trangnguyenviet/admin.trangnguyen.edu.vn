<?php
class PaymentLogData{
	protected static $collection_name = 'payment_logs';
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
		'user_admin' => 1,
		'user_id' => 1,
		'day' => 1,
		'money' => 1,
		'note' => 1,
		'vip_old' => 1,
		'vip_new' => 1,
		'created_at' => 1
	];

	public function GetListByUser($user_id) {
		$cursor = self::$collection->find(['user_id' => $user_id], self::$select_field);
		$cursor->sort(['created_at' => -1]);
		$count = $cursor->count();
		if($count > 0) {
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				array_push($arr, $item);
			}
			return ['list' => $arr, 'count' => $count];
		}
		return ['list' => null, 'count' => 0];
	}

	public function GetList($where, $page_size, $page_index) {
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

	public function Insert($user_admin, $user_id, $day, $money, $vip_old, $vip_new, $note){
		$object = [
			'user_admin' => $user_admin,
			'user_id' => $user_id,
			'day' => $day,
			'money' => $money,
			'vip_old' => $vip_old,
			'vip_new' => $vip_new,
			'note' => $note,
			'created_at' => new MongoDate()
		];

		return self::$collection->insert($object);
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
}