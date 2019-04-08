<?php

/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 22/03/2017
 * Time: 20:36
 */
class TNCardData{
	protected static $collection_name = 'cards';
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
		'serial'=>1,
		'money'=>1,
		'day'=>1,
		'is_used' => 1,
		'used_at' => 1,
		'user_used' => 1
	];

	public function Report(){
		return self::$collection->aggregate([
			[
				'$match' => [
					'active' => true
				]
			],[
				'$group' => [
					'_id' => [
						'money' => '$money',
						'is_used' => '$is_used'
					],
					'count' => [
						'$sum' => 1
					]
				]
			]
		]);
	}

	public function Search($where, $page_size, $page_index){
		$cursor = self::$collection->find($where,self::$select_field);
		$count = $cursor->count();
		if($count>0) {
			$cursor->skip($page_size*$page_index);
			$cursor->limit($page_size);
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['used_at'])) $item['used_at'] = $item['used_at']->sec;
				array_push($arr, $item);
			}
			return ['list'=>$arr,'count'=>$count];
		}
		return ['list'=>null,'count'=>0];
	}
}