<?php
//include 'ModelBase.php';

class ExamEventData extends ModelBase{
	protected static $collection_name = 'exam_events';
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
	//db.exams.update({'answers':null},{$set:{'answers':[]}},{multi:true,upsert:false})
	/*
	db.exam_events.aggregate(
		[
			{
				$match : {}
			},
			{
				$project: {
					_id: 1,
					game_id:1,
					play:1,
					time:1,
					content_size: { $size: '$content' }
				}
			}
		]
	)
	*/
	private static $select_field = [
		'_id'=>1,
		'type_id'=>1,
		'class_id'=>1,
		'game_id' => 1,
		'play' => 1,
		'time' =>1,
		'spq' => 1,
		'answers' => 1,
		'content' => 1,
		'created_at'=>1,
		'updated_at'=>1
	];
	
	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],self::$select_field);
		/*
		return NULL | object
		*/
	}
	
	public function GetInfo2($type_id,$class_id){
		return self::$collection->findOne(
		[
			'type_id' => $type_id,
			'class_id' => $class_id
		],self::$select_field);
		/*
		return NULL | object
		*/
	}
	
	public function GetListShow(){
		$out = self::$collection->aggregate(
			[
				/*[
					'$match' => [
						'type_id' => $type_id
					)
				),*/
				[
					'$project' => [
						'_id'=>1,
						'game_id' => 1,
						'class_id' => 1,
						'type_id' => 1,
						'play' => 1,
						'time' =>1,
						'spq' => 1,
						'content_count' => ['$size' => '$content']
					]
				]
			]
		);
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
	
	public function Insert($class_id,$type_id,$game_id,$play,$time,$spq,$answers,$content){
		$new_id = self::GetNextId();
		$date = new MongoDate();
		$item = [
			'_id' => $new_id,
			'type_id' => $type_id,
			'class_id' => $class_id,
			'game_id' => $game_id,
			'play' => $play,
			'time' => $time,
			'spq' => $spq,
			'answers' => $answers,
			'content' => $content,
			'created_at' => $date,
			'updated_at' => $date
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
	
	public function Update($id,$play,$time,$spq,$answers,$content){
		$item = [
			'time' => $time,
			'answers' => $answers,
			'content' => $content,
			'play' => $play,
			'spq' => $spq,
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
	
	public function GetId($type_id,$class_id){
		return self::$collection->findOne(
		[
			'type_id' => $type_id,
			'class_id' => $class_id,
		],['_id'=>1]);
		/*
		return NULL | object
		*/
	}

	public function CopyData($id,$type_id,$class_id){
		$info = self::$collection->findOne(
		[
			'_id' => $id
		]);

		//delete old
		self::$collection->remove(['type_id' => $type_id, 'class_id' => $class_id]);
		
		if(isset($info)){
			$info['_id'] = self::GetNextId();
			$info['type_id'] = $type_id;
			$info['class_id'] = $class_id;
			
			$data_insert = self::$collection->insert($info);
			if(isset($data_insert) && $data_insert['ok']>0) return true;
		}
		return false;
	}
}