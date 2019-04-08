<?php
//include 'ModelBase.php';

class UserData extends ModelBase{
	protected static $collection_name = 'users';
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
		'username'=>1,
		'name'=>1,
		'birthday'=>1,
		'class_id'=>1,
		'class_name'=>1,
		'school_id'=>1,
		'school_name'=>1,
		'district_id'=>1,
		'district_name'=>1,
		'province_id'=>1,
		'province_name'=>1,
		'mobile'=>1,
		'email'=>1,
		'created_at'=>1,
		'updated_at' => 1,
		'banned'=>1,
		'money'=>1,
		'expire_date'=>1,
		'vip_expire'=>1,
		'exam_school'=>1,
		'exam_district' => 1,
		'exam_province' => 1,
		'exam_national' => 1,
		'total_score_4'=>1,
		'total_time_4'=>1,
		'current_round_4'=>1,
		'active'=>1
	];

	public function getVipDay($id) {
		return self::$collection->findOne(['_id' => intval( $id )], ['vip_expire' => 1]);
	}

	public function updateVipDay($id, $value) {
		return self::$collection->update([
			'_id' => $id
		], [
			'$set' => [
				'vip_expire' => $value
			]
		],[
			'upsert' => false,
			'multiple' => false
		]);
	}
	
//	public function AddVipDay($id, $day) {
//		$info = self::$collection->findOne(['_id' => intval($id)], ['vip_expire' => 1]);
//		if(isset($info)) {
//			$newDate = new MongoDate();
//			$secAdd = 86400 * intval($day);
//			$date_update=null;
//			if(isset($info['vip_expire'])) {
//				$date = $info['vip_expire'];
//				if($newDate->sec > $date->sec) {
//					$newDate->sec += $secAdd;
//					$date_update = $newDate;
//				} else {
//					$date->sec += $secAdd;
//					$date_update = $date;
//				}
//			} else {
//				$newDate->sec += $secAdd;
//				$date_update = $newDate;
//			}
//			return self::$collection->update(['_id' => $id], ['$set' => ['vip_expire' => $date_update]], ['upsert' => false, 'multiple' => false]);
//		}
//		return null;
//	}
	
	public function GetInfo($id) {
		return self::$collection->findOne(['_id' => intval($id)], self::$select_field);
		/*
		return NULL | object
		*/
	}

	public function GetListDistinct($field, $listId) {
		return self::$collection->distinct($field, ['_id' => ['$in' => $listId]]);
	}
	
	public function GetList($page_size, $page_index){
		$cursor = self::$collection->find(
			[
				'deleted' => false
			],
			self::$select_field
		);
		$cursor->sort(['_id' => 1]);
		$count = $cursor->count();
		if($count>0) {
			$cursor->skip($page_size * $page_index);
			$cursor->limit($page_size);
			$arr = [];
			foreach($cursor as $item) {
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				if(isset($item['updated_at'])) $item['updated_at'] = $item['updated_at']->sec;
				if(isset($item['birthday'])) $item['birthday'] = $item['birthday']->sec;
				if(isset($item['expire_date'])) $item['expire_date'] = $item['expire_date']->sec;
				if(isset($item['vip_expire'])) $item['vip_expire'] = $item['vip_expire']->sec;
				
				array_push($arr, $item);
			}
			return ['list' => $arr, 'count' => $count];
		}
		return ['list' => null, 'count' => 0];
	}

	public function count($where) {
		$where['deleted'] = false;
		$cursor = self::$collection->find($where, self::$select_field);
		return $cursor->count();
	}
	
	public function Search($where, $order_by, $page_size, $page_index, $bFull) {
		$where['deleted'] = false;
		$cursor = self::$collection->find($where, self::$select_field);
		$cursor->sort($order_by);
		$count = $cursor->count();
		if($count > 0) {
			if($bFull == false) {
				$cursor->skip($page_size * $page_index);
				$cursor->limit($page_size);
			}
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				if(isset($item['updated_at'])) $item['updated_at'] = $item['updated_at']->sec;
				if(isset($item['birthday'])) $item['birthday'] = $item['birthday']->sec;
				if(isset($item['expire_date'])) $item['expire_date'] = $item['expire_date']->sec;
				if(isset($item['vip_expire'])) $item['vip_expire'] = $item['vip_expire']->sec;
				
				array_push($arr, $item);
			}
			return ['list' => $arr, 'count' => $count];
		}
		return ['list' => null, 'count' => 0];
	}
	
	public function TopScore($where, $limit) {
		$where['deleted'] = false;
		$where['total_score_4'] = ['$ne' => null];
		$where['total_time_4'] = ['$ne' => null];
		$where['current_round_4'] = ['$ne' => null];
		$cursor = self::$collection->find($where, [
			'_id' => 1,
			'username' => 1,
			'name' => 1,
			'birthday' => 1,
			'school_id' => 1,
			'school_name' => 1,
			'district_id' => 1,
			'district_name' => 1,
			'province_id' => 1,
			'province_name' => 1,
			'class_id' => 1,
			'class_name' => 1,
			'total_score_4' => 1,
			'total_time_4' => 1,
			'current_round_4' => 1,
			'is_exam_province' => 1,
			'is_exam_national' => 1
		]);
		$cursor->limit($limit);
		$cursor->sort(['total_score_4' => -1, 'total_time_4' => 1, 'current_round_4' => 1]);
		$arr = [];
		foreach($cursor as $item) {
			if(isset($item['birthday'])) $item['birthday'] = $item['birthday']->sec;
			array_push($arr, $item);
		}
		return $arr;
	}
	
	// public function CheckExistName($name,$id){
	// 	$where = [
	// 		'name' => $name,
	// 		'_id' => ['$ne'=>$id)
	// 		//'_id' => $id
	// 	);
	// 	//var_dump($where);
	// 	return self::$collection->find($where)->count();
	// 	/*
	// 	return 1 | 0
	// 	*/
	// }

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

	public function Insert($name,$birthday,$email,$mobile,$province_id,$province_name,$district_id,$district_name,$school_id,$school_name,$class_id,$class_name,$active){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'name' => $name,
			'birthday' => $birthday,
			'email' => ($email==''? null: $email),
			'mobile' => ($mobile==''? null: $mobile),
			'province_id' => $province_id,
			'province_name' => $province_name,
			'district_id' => $district_id,
			'district_name' => $district_name,
			'school_id' => $school_id,
			'school_name' => $school_name,
			'class_id' => $class_id,
			'class_name' => $class_name,
			'active' => $active,
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
	
	public function Update($id,$name,$birthday,$email,$mobile,$province_id,$province_name,$district_id,$district_name,$school_id,$school_name,$class_id,$class_name,$active){
		$item = [
			'name' => $name,
			'birthday' => $birthday,
			'email' => ($email==''? null: $email),
			'mobile' => ($mobile==''? null: $mobile),
			'province_id' => $province_id,
			'province_name' => $province_name,
			'district_id' => $district_id,
			'district_name' => $district_name,
			'school_id' => $school_id,
			'school_name' => $school_name,
			'class_id' => $class_id,
			'class_name' => $class_name,
			'active' => $active,
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

	public function SetExamSchool($id,$val){
		$item = [
			'exam_school' => $val,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update(['_id' => $id],['$set' => $item],['upsert'=>false,'multiple' => false]);
	}

	public function UpdateList($where,$field,$val){
		$item = [
			$field => $val,
			//'updated_at' => new MongoDate()
		];
		return self::$collection->update($where,['$set' => $item],['upsert'=>false,'multiple' => true]);
	}

	public function UpdateListField($list_id, $field, $val){
		$item = [
			$field => $val,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update(['_id' => ['$in' => $list_id]],['$set' => $item],['upsert'=>false,'multiple' => true]);
	}

	public function SetExamDistrict($id,$val){
		$item = [
			'exam_district' => $val,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update(['_id' => $id],['$set' => $item],['upsert'=>false,'multiple' => false]);
	}
	
	public function SetExamProvince($id,$val){
		$item = [
			'exam_province' => $val,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update(['_id' => $id],['$set' => $item],['upsert'=>false,'multiple' => false]);
	}
	
	public function SetExamNational($id,$val){
		$item = [
			'exam_national' => $val,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update(['_id' => $id],['$set' => $item],['upsert'=>false,'multiple' => false]);
	}
	
	public function Change_Password($id,$password){
		$item = [
			'password' => $password,
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
	
	public function Delete($id, $user_admin, $reason){
		return self::$collection->update([
			'_id' => $id
		], [
			'$set' => [
				'deleted' => true,
				'delete_by' => $user_admin,
				'delete_reason' => $reason,
				'delete_at' => new MongoDate()
			]
		], [
			'upsert' => false,
			'multiple' => false
		]);
		
		//return self::$collection->remove(['_id' => intval($id)));
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
	
	public function Ban($id, $user_admin, $reason){
		return self::$collection->update([
			'_id' => $id
		], [
			'$set' => [
				'banned' => true,
				'ban_by' => $user_admin,
				'ban_reason' => $reason,
				'ban_at' => new MongoDate()
			]
		], [
			'upsert' => false,
			'multiple' => false
		]);
		
		//return self::$collection->remove(['_id' => intval($id)));
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
	
	public function UnBan($id, $user_admin, $reason){
		return self::$collection->update([
			'_id' => $id
		], [
			'$set' => [
				'banned' => false,
				'unban_by' => $user_admin,
				'unban_reason' => $reason,
				'unban_at' => new MongoDate()
			]
		], [
			'upsert' => false,
			'multiple' => false
		]);
		
		//return self::$collection->remove(['_id' => intval($id)));
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
