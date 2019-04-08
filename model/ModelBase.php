<?php

/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 26/05/2017
 * Time: 17:00
 */
class ModelBase {
	protected static $collection_name = '';

	protected function GetNextId(){
		global $db;
		$collection = $db->selectCollection('counters');
		$result = $collection->findAndModify(
			['_id'=> $this::$collection_name],
			[
				'$inc'=>[
					'seq' => 1
				]
			],
			[
				'seq' => 1
			],
			[
				'new' => true,
				'upsert' => true
			]
		);
		return $result['seq'];
	}
}