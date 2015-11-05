<?php

class users_Model_DbTable_DbUser extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_user';
	//function for getting record user by user_id
    public function getUserShow()
    {
    	$db = new Application_Model_DbTable_DbGlobal();
    	$sql = "SELECT u.*,ut.`user_type` FROM tb_user AS u,`tb_user_type` AS ut WHERE u.`user_type`= ut.`user_type_id`";
    	return $db->getGlobalDb($sql);;
    }
    public function getUserShowByid($id)
    {
    	$db = new Application_Model_DbTable_DbGlobal();
    	$sql = "SELECT * FROM tb_user WHERE user_id =".$id;
    	return $db->getGlobalDb($sql);;
    }
	public function getUser($user_id)
	{
		$select=$this->select();		
		$select->where('user_id=?',$user_id);
		$row=$this->fetchRow($select);
		if(!$row) return NULL;
		return $row->toArray();
	}
	public function getuserprofile($user_id)
	{
		$db = new Application_Model_DbTable_DbGlobal();
		$sql = "SELECT u.*, tu.user_type FROM tb_user as u, tb_user_type as tu where u.user_type_id = tu.user_type_id AND u.user_id =".$user_id;
		return $db->getGlobalDb($sql);;
	}
	//get user name
	public function getUserName($user_id)
	{
		$select=$this->select();
		$select->from($this,'username')
			->where("user_id=?",$user_id);
		$row=$this->fetchRow($select);
		if(!$row) return null; 
		return $row['username'];
	}
	//change password user wanted
	public function changePassword($user_id,$password)
	{
		$data=array('password'=>$password);
		$where=$this->getAdapter()->quoteInto('user_id=?',$user_id);
		$this->update($data,$where);
	}
	//is valid password
	public function isValidCurrentPassword($user_id,$current_password)
	{
		$select=$this->select();
		$select->from($this,'password')
			->where("user_id=?",$user_id);
		$row=$this->fetchRow($select);
		if($row){
			$current_password=md5($current_password);
			$password=$row['password'];			 
			if($password==$current_password) return true;
		}
		return false;
	}
	//get infomation of user
	public function getUserInfo($sql)
	{
		$db=$this->getAdapter();
  		$stm=$db->query($sql);
  		$row=$stm->fetchAll();
  		if(!$row) return NULL;
  		return $row;
	}
	//function get user id from database
	public function getUserID($username)
	{
		$select=$this->select();
			$select->from($this,'user_id')
			->where('username=?',$username);
		$row=$this->fetchRow($select);
		if(!$row) return NULL;
		return $row['user_id'];
	}
	//function retrieve record users by column 
	public function getUsers($column)
	{		
		$sql='user_id not in(select user_id from pdbs_acl) AND status=1 ';	
		$select=$this->select();
		$select->from($this,$column)
			   ->where($sql);
		$row=$this->fetchAll($select);
		if(!$row) return NULL;		
		return $row->toArray();
	}
	//function check user have exist
	public function isUserExist($username)
	{
		$select=$this->select();
		$select->from($this,'username')
			->where("username=?",$username);
		$row=$this->fetchRow($select);
		if(!$row) return false;
		return true;
	}
	public function updateStatus($arr)
	{
		$array_data = array(
				"status"	=>	1
		);
		$where = $this->getAdapter()->quoteInto('user_id=?', $arr["unstatus"]);
		$this->update($array_data, $where);
			
	}
	public function updateUnStatus($arr)
	{
		$array_data = array(
				"status"	=>	0
		);
		$where = $this->getAdapter()->quoteInto('user_id=?', $arr["status"]);
		$this->update($array_data, $where);
			
	}
	public function getuserss()
	{
		$db = new Application_Model_DbTable_DbGlobal();
		$sql = "SELECT * FROM tb_user";
		return $db->getGlobalDb($sql);;
	}
	public function ifUserExist($username)
	{
		$db=$this->getAdapter();
		$sql = "SELECT user_id FROM tb_user WHERE username = '".$username."' LIMIT 1";
		$row = $db->fetchRow($sql);
		if(!$row) return false;
		return true;
	}
	//function check id number have exist
	public function isIdNubmerExist($id_number)
	{
		$select=$this->select();
		$select->from($this,'id_number')
			->where("id_number=?",$id_number);
		$row=$this->fetchRow($select);
		if(!$row) return false;
		return true;
	}
	//add user
	public function insertUser($arr)
	{ 
		$photoname = str_replace(" ", "_", $arr['username']) . '.jpg';
		$upload = new Zend_File_Transfer();
		$upload->addFilter('Rename',
				array('target' => PUBLIC_PATH . '/images/'. $photoname, 'overwrite' => true) ,'photo');
		$receive = $upload->receive();
			
		if($receive)
		{
			$arr['photo'] = $photoname;
		}
		else{
			$arr['photo']="";
		}
		$arr['password']= md5($arr['password']);
     	$db = $this->getAdapter();
     	$array_data = array(
     			"name"		=>	$arr["fullname"],
     			"user_name"		=>	$arr["username"],
     			"user_code"		=>	$arr["id_code"],
     			"password"		=>	$arr['password'],
     			"email"			=>	$arr["email"],
     			"photo"			=>	$arr["photo"],
     			"user_type"	=>	$arr["user_type"],
     			"status"		=>	1,
     			"date"	=>	date("Y-m-d H:i:s")
     			);
     	$id=$this->insert($array_data);
     	
	}
	
	public function updateUser($arr,$user_id)
	{
		try{
			$db=$this->getAdapter();
			$db->beginTransaction();
			$photoname = str_replace(" ", "_", $arr['username']) . '.jpg';
			$upload = new Zend_File_Transfer();
			$upload->addFilter('Rename',
					array('target' => PUBLIC_PATH . '/images/'. $photoname, 'overwrite' => true) ,'photo');
			$receive = $upload->receive();
			if($receive !="")
			{
				$arr['photo'] = $photoname;
			}
			elseif($receive ==""){
				$dbs = $this->getAdapter();
				$sql = "SELECT photo FROM `tb_user` WHERE `user_id` =".$user_id;
				$old_photo = $db->fetchRow($sql);
				foreach ($old_photo as $old_photos){
					$arr['photo'] = $old_photos;
				}
			}
			unset($arr['MAX_FILE_SIZE']);
			$db->getProfiler()->setEnabled(true);
			$data = array(
					"name"		=>	$arr["fullname"],
	     			"user_name"		=>	$arr["username"],
	     			"user_code"		=>	$arr["id_code"],
	     			"email"			=>	$arr["email"],
	     			"photo"			=>	$arr["photo"],
	     			"user_type"	=>	$arr["user_type"],
	     			"status"		=>	1,
	     			"date"	=>	date("Y-m-d H:i:s")
			);
			$this->_name ="tb_user";
			$where = $this->getAdapter()->quoteInto('user_id=?',$user_id);
			$this->update($data, $where);			
			$db->commit();
		}
		catch (Exception $e){
			$db->rollBack();
			$e->getMessage();
			echo "Connect Fail";
		}
		
		
	}
	public function updatePassword($arr,$user_id)
	{
		try{
			$db=$this->getAdapter();
			$db->beginTransaction();
			$db->getProfiler()->setEnabled(true);
			$data = array(
					"password"			=>	$arr["password"],
			);
			$this->_name ="tb_user";
			$where = $this->getAdapter()->quoteInto('user_id=?',$user_id);
			$this->update($data, $where);
			//echo $where;
			$db->commit();
		}
		catch (Exception $e){
			$db->rollBack();
			$e->getMessage();
			echo "Connect Fail";
		}
	}
	//function dupdate field status user to force use become inaction
	public function inactiveUser($user_id)
	{
		$data=array('status'=>0);
		$where=$this->getAdapter()->quoteInto('user_id=?',$user_id);
		$this->update($data,$where);
	}
	public function userAuthenticate($username,$password)
	{
		try{
	              $db_adapter = $this->getDefaultAdapter(); 
	              $auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
	              
	              $auth_adapter->setTableName('tb_user') // table where users are stored
	                           ->setIdentityColumn('username') // field name of user in the table
	                           ->setCredentialColumn('password') // field name of password in the table
	                           ->setCredentialTreatment('MD5(?) AND status=1'); // optional if password has been hashed
	 
	              $auth_adapter->setIdentity($username); // set value of username field
	              $auth_adapter->setCredential($password);// set value of password field
	 
	              //instantiate Zend_Auth class
	              $auth = Zend_Auth::getInstance();
	 
	              $result = $auth->authenticate($auth_adapter);
	 
	              if($result->isValid()){
	              	  return true;
	              }else{
	                  // validation errors here
					  return false;
	              }
		}catch(Zend_Exception $ex){}
	}
	public function deleteUser($id) {
		$db = $this->getAdapter();
		$sql = "Delete From tb_user Where user_id=".$id;
		$row = $db->query($sql);
		return $row;
	}
	public function getpassword($user_id,$data){
		$select=$this->select();
		$select->from($this,'password')
			->where("user_id=?",$user_id);
		$row = $this->fetchRow($select);
		if($row){
			$data = md5($data);
			$password = $row['password'];			 
			if($password==$data) return true;
		}
		return false;
	}
}

