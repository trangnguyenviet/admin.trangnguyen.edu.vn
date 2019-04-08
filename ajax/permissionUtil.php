<?php
	require_once '../model/ModelBase.php';

	function CheckPermission($admin_id, $page, $action){
		switch ($page) {
			case 'school':
			case 'district':
			case 'province':
				if(in_array($action, ['save', 'delete'])) {
					if(in_array($admin_id, [1, 2])) {
						return true;
					} else {
						return false;
					}
				}
				return true;
			default:
				return true;
		}
	}

	function GetPermission($admin_id,$page){
		return [
			'list' => true,
			'add' => true,
			'update' => true,
			'delete' => true
		];
	}