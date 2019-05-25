<?php
require_once 'ModelBase.php';

class ExamAnswersData extends ModelBase{
	protected static $collection_name = 'exam_answers';
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
		//'type_id'=>1,
		'class_id'=>1,
		'subject_id'=>1,
		'subject_rewrite' => 1,
		'name' => 1,
		'rewrite' => 1,
		//'game_id' => 1,
		'play' => 1,
		'time' =>1,
		'description' => 1,
		'thumb' => 1,
		'active' => 1,
		//'answers' => 1,
		'content' => 1,
		'created_at'=>1,
		'updated_at'=>1
	];

	public function GetInfo($id) {
		return self::$collection->findOne(['_id' => intval($id)],self::$select_field);
		/*
		return NULL | object
		*/
	}

	// public function GetInfo2($type_id,$class_id){
	// 	return self::$collection->findOne(
	// 	[
	// 		'type_id' => $type_id,
	// 		'class_id' => $class_id
	// 	],self::$select_field);
	// 	/*
	// 	return NULL | object
	// 	*/
	// }

	public function GetListShow($class_id, $subject_id) {
		$out = self::$collection->aggregate([
			[
				'$match' => [
					'class_id' => $class_id,
					'subject_id' => $subject_id
				]
			], [
				'$project' => [
					'_id'=>1,
					'thumb' => 1,
					'name' => 1,
					'rewrite' => 1,
					'active' => 1,
					// 'game_id' => 1,
					'class_id' => 1,
					//'type_id' => 1,
					'created_at'=>1,
					'updated_at'=>1,
					'play' => 1,
					'time' =>1,
					'content_count' => ['$size' => '$content']
				]
			]
		]);
		if($out['ok']>0) {
			$arr = [];
			$result = $out['result'];
			foreach($result as $item)
			{
				array_push($arr, $item);
			}
			return $arr;
		}
		return null;
	}

	//private function GetNextId(){
	//	global $db;
	//	$NextIdObj= $db->execute('getNextSequence("'. self::$collection_name .'");');
	//	return intval($NextIdObj['retval']);
	//}

	public function CopyData($id, $subject_id, $class_id) {
		$info = self::$collection->findOne(
			[
				'_id' => $id
			]);

		if(isset($info)){
			$info['_id'] = self::GetNextId();
			$info['class_id'] = $class_id;
			$info['subject_id'] = $subject_id;

			$data_insert = self::$collection->insert($info);
			if(isset($data_insert) && $data_insert['ok']>0) return true;
		}
		return false;
	}

	public function Insert($name, $thumb, $rewrite,$class_id, $subject_id, $subject_rewrite, $play,$time,$description,$answers,$content) {
	//public function Insert($name, $thumb, $rewrite,$class_id, $play,$time,$description,$answers,$content){
		$new_id = self::GetNextId();
		$date = new MongoDate();
		$item = [
			'_id' => $new_id,
			'name' => $name,
			'thumb' => $thumb,
			'rewrite' => $rewrite,
			// 'type_id' => $type_id,
			'class_id' => $class_id,
			'subject_id' => $subject_id,
			'subject_rewrite' => $subject_rewrite,
			'game_id' => 0,
			'play' => $play,
			'time' => $time,
			'description' => $description,
			'answers' => $answers,
			'content' => $content,
			'created_at' => $date,
			'updated_at' => $date,
			'active' => true
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

	//public function Update($id, $subject_id, $name, $thumb, $rewrite, $play, $time, $description, $answers, $content){
	public function Update($id, $name, $thumb, $rewrite, $play, $time, $description, $answers, $content) {
		$item = [
			'name' => $name,
			'thumb' => $thumb,
			'rewrite' => $rewrite,
			//'subject_id' => $subject_id,
			'time' => $time,
			'play' => $play,
			'content' => $content,
			'answers' => $answers,
			'description' => $description,
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

	public function setActive($id, $active) {
		$item = [
			'active' => $active,
			'updated_at' => new MongoDate()
		];
		return self::$collection->update([
			'_id' => $id
		], [
			'$set' => $item
		], [
			'upsert' => false,
			'multiple' => false
		]);
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

		return self::$collection->remove([
			'_id' => intval($id)
		]);
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

	// public function GetId($type_id,$class_id){
	// 	return self::$collection->findOne(
	// 	[
	// 		'type_id' => $type_id,
	// 		'class_id' => $class_id,
	// 	],['_id'=>1]);
	// 	/*
	// 	return NULL | object
	// 	*/
	// }

	// public function CopyData($id,$type_id,$class_id){
	// 	$info = self::$collection->findOne(['_id' => $id]);

	// 	//delete old
	// 	self::$collection->remove(['type_id' => $type_id, 'class_id' => $class_id]);

	// 	if(isset($info)){
	// 		$info['_id'] = self::GetNextId();
	// 		// $info['type_id'] = $type_id;
	// 		$info['class_id'] = $class_id;

	// 		$data_insert = self::$collection->insert($info);
	// 		if(isset($data_insert) && $data_insert['ok']>0) return true;
	// 	}
	// 	return false;
	// }
}