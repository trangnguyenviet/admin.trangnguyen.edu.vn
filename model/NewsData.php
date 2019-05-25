<?php
require_once 'ModelBase.php';

class NewsData extends ModelBase{
	protected static $collection_name = 'news';
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

	public function GetInfo($id){
		return self::$collection->findOne(['_id' => intval($id)],['_id'=>1,'name'=>1,'name_ko_dau'=>1]);
		/*
		return NULL | object
		*/
	}
	
	public function GetList($parent_id,$page_size,$page_index){
		$cursor = self::$collection->find(
			[
				'parent_id' => $parent_id,
				'deleted' => false
			],
			[
				'_id'=>1,
				'name'=>1,
				'parent_id'=>1,
				'parent_name'=>1,
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
			]
		);
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
	
	public function Search($parent_id,$search_key,$page_size,$page_index){
	
		$cursor = self::$collection->find(
			[
				'parent_id' => $parent_id,
				'deleted' => false,
				'name' => new MongoRegex('/'.$search_key.'/i')
			],
			[
				'_id'=>1,
				'name'=>1,
				'parent_id'=>1,
				'parent_name'=>1,
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
			]
		);
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

	public function Insert($name,$parent_id,$parent_name,$name_ko_dau,$active,$sort,$thumb,$description,$content,$create_by,$is_publish_date,$publish_at,$publish_end,$tags){
		$new_id = self::GetNextId();
		$item = [
			'_id' => $new_id,
			'name' => $name,
			'parent_id' => intval($parent_id),
			'parent_name' => $parent_name,
			'name_ko_dau' => $name_ko_dau,
			'active' => $active,
			'sort' => intval($sort),
			'thumb' => $thumb,
			'description' => $description,
			'content' => $content,
			'create_by' => $create_by,
			'is_publish_date' => $is_publish_date,
			'publish_at' => $publish_at,
			'publish_end' => $publish_end,
			'created_at' => new MongoDate(),
			'updated_at' => new MongoDate(),
			'tags'=>$tags,
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
	
	public function Update($id,$name,$parent_id,$parent_name,$name_ko_dau,$active,$sort,$thumb,$description,$content,$is_publish_date,$publish_at,$publish_end,$tags){
		$item = [
			'name' => $name,
			'parent_id' => intval($parent_id),
			'parent_name' => $parent_name,
			'name_ko_dau' => $name_ko_dau,
			'active' => $active,
			'sort' => intval($sort),
			'thumb' => $thumb,
			'description' => $description,
			'content' => $content,
			'is_publish_date' => $is_publish_date,
			'publish_at' => $publish_at,
			'publish_end' => $publish_end,
			'updated_at' => new MongoDate(),
			'tags'=>$tags
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

	public function CopyData($id,$parent_id,$create_by){
		$info = self::$collection->findOne([
			'_id' => $id
		]);

		if(isset($info)){
			$info['_id'] = self::GetNextId();
			$info['parent_id'] = $parent_id;
			$info['create_by'] = $create_by;
			$data_insert = self::$collection->insert($info);
			if(isset($data_insert) && $data_insert['ok']>0) return true;
		}
		return false;
	}
}