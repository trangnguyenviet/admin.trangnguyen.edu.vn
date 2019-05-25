<?php
require_once 'ModelBase.php';

class GameData extends ModelBase{
	protected static $collection_name = 'games';
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
		'category_id' => 1,
		'category_name' => 1,
		'type_id'=>1,
		'name'=>1,
		'url'=>1,
		//'width'=>1,
		//'height'=>1,
		'name_ko_dau'=>1,
		'sort'=>1,
		'thumb'=>1,
		'description'=>1,
		'content'=>1,
		'create_by'=>1,
		'is_publish_date'=>1,
		'publish_at'=>1,
		'publish_end'=>1,
		'active'=>1,
		'tags'=>1,
		'created_at'=>1
	];
	
	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],['_id'=>1,'name'=>1,'name_ko_dau'=>1]);
		/*
		return NULL | object
		*/
	}
	
	public function GetList($category_id,$page_size,$page_index){
		$cursor = self::$collection->find(
			[
				'category_id' => $category_id,
				'deleted' => false
			],self::$select_field);
		$cursor->sort(['sort' => 1,'created_at'=>-1]);
		$count = $cursor->count();
		if($count>0) {
			$cursor->skip($page_size*$page_index);
			$cursor->limit($page_size);
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				if(isset($item['publish_at'])) $item['publish_at'] = $item['publish_at']->sec;
				if(isset($item['publish_end'])) $item['publish_end'] = $item['publish_end']->sec;
				
				array_push($arr, $item);
			}
			return ['list'=>$arr,'count'=>$count];
		}
		return ['list'=>null,'count'=>0];
	}
	
	public function Search($category_id,$search_key,$page_size,$page_index){
		$cursor = self::$collection->find(
			[
				'category_id' => $category_id,
				'deleted' => false,
				'name' => new MongoRegex('/'.$search_key.'/i')
			],self::$select_field);
		$cursor->sort(['sort' => 1,'created_at'=>1]);
		$count = $cursor->count();
		if($count>0) {
			$cursor->skip($page_size*$page_index);
			$cursor->limit($page_size);
			$arr = [];
			foreach($cursor as $item)
			{
				if(isset($item['created_at'])) $item['created_at'] = $item['created_at']->sec;
				if(isset($item['publish_at'])) $item['publish_at'] = $item['publish_at']->sec;
				if(isset($item['publish_end'])) $item['publish_end'] = $item['publish_end']->sec;
				
				array_push($arr, $item);
			}
			return ['list'=>$arr,'count'=>$count];
		}
		return ['list'=>null,'count'=>0];
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

	public function Insert($category_id,$category_name,$name,$type_id,$url,$name_ko_dau,$sort,$thumb,$description,$content,$create_by,$is_publish_date,$publish_at,$publish_end,$tags,$active){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'category_id' => $category_id,
			'category_name' => $category_name,
			'type_id'=>$type_id,
			'name' =>$name,
			'url'=>$url,
			//'width'=>$width,
			//'height'=>$height,
			'name_ko_dau'=>$name_ko_dau,
			'sort'=>$sort,
			'thumb'=>$thumb,
			'description'=>$description,
			'content'=>$content,
			'create_by'=>$create_by,
			'is_publish_date'=>$is_publish_date,
			'publish_at'=>$publish_at,
			'publish_end'=>$publish_end,
			'active'=>$active,
			'tags'=>$tags,
			'created_at'=>new MongoDate(),
			'deleted'=>false
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
	
	public function Update($id,$name,$type_id,$url,$name_ko_dau,$sort,$thumb,$description,$content,$create_by,$is_publish_date,$publish_at,$publish_end,$tags,$active){
		$item = [
			'type_id'=>$type_id,
			'name' =>$name,
			'url'=>$url,
			//'width'=>$width,
			//'height'=>$height,
			'name_ko_dau'=>$name_ko_dau,
			'sort'=>$sort,
			'thumb'=>$thumb,
			'description'=>$description,
			'content'=>$content,
			'create_by'=>$create_by,
			'is_publish_date'=>$is_publish_date,
			'publish_at'=>$publish_at,
			'publish_end'=>$publish_end,
			'active'=>$active,
			'tags'=>$tags,
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
		return self::$collection->update(['_id' => $id],['$set' => ['deleted'=>true]],['upsert'=>false,'multiple' => false]);
		
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