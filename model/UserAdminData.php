<?php
require_once 'ModelBase.php';

class UserAdminData extends ModelBase{
	protected static $collection_name = 'user_admins';
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
	
	// public function GetEmailByUsername($username){
		
	// }
	
	// public function GetIsSupperAdmin($user_id){
		
	// }
	
	// public function GetInfo($user_id){
		
	// }
	
	// public function GetList(){
		
	// }
	
	// public function GetList_Edit(){
		
	// }
	
	public function GetListForView(){
		$cursor = self::$collection->find(
				[],
				[
						'_id'=>1,
						'username'=>1
				]
		);
		
		$list = [];
		if($cursor->count()>0) {
			foreach($cursor as $item)
			{
				$list[$item['_id']]=$item['username'];
			}
		}
		return $list;
	}
	
	public function Login($username,$password){
		return self::$collection->findOne(
			[
				'username' => $username,
				'password' => $password
			],
			[
				'_id'=>1,
				'username'=>1,
				'fullname'=>1,
				'email'=>1,
				'position'=>1,
				'created_date'=>1,
				'active'=>1
			]
		);
	}
	
	public function Login_2($admin_id,$password){
		return self::$collection->findOne(
			[
				'_id' => $admin_id,
				'password' => $password
			],
			[
				'_id'=>1,
				'username'=>1,
				'fullname'=>1,
				'email'=>1,
				'position'=>1,
				'created_date'=>1,
				'active'=>1
			]
		);
	}
	
	public function LoginByEmail($email){
		return self::$collection->findOne(
			[
				'email' => $email
			],
			[
				'_id'=>1,
				'username'=>1,
				'fullname'=>1,
				'email'=>1,
				'position'=>1,
				'created_date'=>1,
				'active'=>1
			]
		);
	}
	
	// public function CheckOldPass($admin_id,$password){
		
	// }
	
	// public function Save_Login($admin_id,$login_ip){
		
	// }
	
	// public function Insert($username,$password,$fullname,$email,$notes,$status,$is_super_admin){
		
	// }
	
	// public function ChangePass($UserId,$password){
		
	// }
	
	// public function Update($UserId,$fullname,$email){
		
	// }
	
	// public function Update_Full($UserId,$password,$fullname,$email,$notes,$status,$is_super_admin){
		
	// }
	
	// public function Update_WithOut_Password($UserId,$fullname,$email,$notes,$status,$is_super_admin){
		
	// }
	
	// public function SetStatus($UserId,$status,$server_id){
		
	// }
	
	// public function CheckExistUser($Username){
		
	// }
	
	// public function CheckExistEmail($email){
		
	// }
	
	// public function CheckPassword($userId,$password,$server_id){
		
	// }
	
	// public function Delete($id,$server_id){
		
	// }
}