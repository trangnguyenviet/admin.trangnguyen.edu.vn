<?php
//include 'ModelBase.php';

class ExamData extends ModelBase {
	protected static $collection_name = 'exams';
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
	db.exams.aggregate(
		[
			{
				$match : {
					class_id : 1,
					type_id:1
				}
			},
			{
				$project: {
					_id: 1,
					game_id:1,
					play:1,
					round_id:1,
					test:1,
					time:1,
					//answers_count: { $size: '$answers' },
					content_size: { $size: '$content' },
					created_at:1,
					updated_at:1
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
		'round_id' => 1,
		'test' => 1,
		'time' =>1,
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
	
	public function GetInfo2($type_id,$class_id,$round_id,$test){
		return self::$collection->findOne(
		[
			'type_id' => $type_id,
			'class_id' => $class_id,
			'round_id' => $round_id,
			'test' => $test
		],self::$select_field);
		/*
		return NULL | object
		*/
	}
	
	public function GetList($class_id,$type_id){
		$cursor = self::$collection->find(
			[
				'class_id'=>intval($class_id),
				'type_id' => $type_id
			],
			self::$select_field
		);
		//$cursor->sort(['round_id'=>1,'_id' => 1));
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
	
	public function GetListShow($class_id,$type_id){
		$out = self::$collection->aggregate(
			[
				[
					'$match' => [
						'class_id'=>intval($class_id),
						'type_id' => $type_id
					]
				],
				[
					'$project' => [
						'_id'=>1,
						'game_id' => 1,
						'play' => 1,
						'round_id' => 1,
						'test' => 1,
						'time' =>1,
						//'answers_count' => ['$size' => 'answers'),
						'content_count' => ['$size' => '$content'],
						'created_at'=>1,
						'updated_at'=>1
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
	
	public function Insert($type_id,$class_id,$game_id,$round_id,$test,$time,$answers,$content){
		$new_id = self::GetNextId();
		$date = new MongoDate();
		$item = [
			'_id' => $new_id,
			'type_id' => $type_id,
			'class_id' => $class_id,
			'game_id' => $game_id,
			'play' => 10,//$play,
			'round_id' => $round_id,
			'test' => $test,
			'time' => $time,
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
	
	public function Update($id,$game_id,$time,$answers,$content){
		$item = [
			'game_id' => $game_id,
			'time' => $time,
			'answers' => $answers,
			'content' => $content,
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
	
	public function GetId($type_id,$class_id,$round_id,$test){
		//var_dump($type_id);
		//var_dump($class_id);
		//var_dump($round_id);
		//var_dump($test);
		return self::$collection->findOne(
		[
			'type_id' => $type_id,
			'class_id' => $class_id,
			'round_id' => $round_id,
			'test' => $test
		],['_id'=>1]);
		/*
		return NULL | object
		*/
	}
	
	public function CopyData($id,$type_id,$class_id,$round_id,$test){
		$info = self::$collection->findOne(
		[
			'_id' => $id
		]);
		
		if(isset($info)){
			$info['_id'] = self::GetNextId();
			$info['type_id'] = $type_id;
			$info['class_id'] = $class_id;
			$info['round_id'] = $round_id;
			$info['test'] = $test;
			
			$data_insert = self::$collection->insert($info);
			if(isset($data_insert) && $data_insert['ok']>0) return true;
		}
		return false;
	}
}