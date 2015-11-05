<?php

class Application_Model_DbTable_DbUsers extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_user';
    
    public function setName($name)
    {
    	$this->_name=$name;
    }
	//function get user info from database
	public function getUserInfo($id){
		$sql = "SELECT * FROM $this->_name	WHERE user_type = 1 AND user_id = ".$id;
		$row = $this->getAdapter()->fetchRow($sql);
		if(!$row) return NULL;
		return $row;
	}
	public function getUserInfoMember($id){
		$sql = "SELECT * FROM $this->_name	WHERE is_active = 1 AND id = ".$id;
		$row = $this->getAdapter()->fetchRow($sql);
		if(!$row) return NULL;
		return $row;
	}
	//function get user id from database
	public function getUserID($login)
	{		
		$select=$this->select();
			$select->from($this,'user_id')
			->where('user_name=?',$login);
		$row=$this->fetchRow($select);
		if(!$row) return NULL;
		return $row['user_id'];
	}

	
	/**
	 * To check email have or not 
	 * have before allow to user register
	 * @param string $email
	 */
	public function checkUsr($login){
		$db = $this->getAdapter();
		$sql = "SELECT user_id,user_name FROM $this->_name WHERE user_name = '". $login ."'";
		$row = $db->fetchRow($sql);		
		return $row['user_id'];
	}	
    
    /** 
     * To validate the user name 
     * and password is valids or not
     * @param <string> $username
     * @param <string> $password
     */
    public function userAuthenticate($login,$password)
	{        
		$db_adapter = Application_Model_DbTable_DbUsers::getDefaultAdapter(); 
        $auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
              
        $auth_adapter->setTableName($this->_name) // table where users are stored
                     ->setIdentityColumn('user_name') // field name of user in the table
                     ->setCredentialColumn('password') // field name of password in the table
                     ->setCredentialTreatment('MD5(?) AND status=1'); // optional if password has been hashed
 		
        $auth_adapter->setIdentity($login); // set value of username field
        $auth_adapter->setCredential($password);// set value of password field
 
        //instantiate Zend_Auth class        
        $auth = Zend_Auth::getInstance();
        
 
        $result = $auth->authenticate($auth_adapter);
        
 
        if($result->isValid()){            
           	return true;				  
        }else{        
		   return false;
        }
	}
	public static function redirectUrl($url)
	{
		echo '<script language="javascript">
		window.location = "'.Zend_Controller_Front::getInstance()->getBaseUrl().$url.'";
		</script>';
	}	
	function changePassword($newpwd, $id){
		$_user_data=array(
			'password'=> MD5($newpwd),
			'modified_date'=> date('Y-m-d H:i:s')		
	    );    	   
		
		$where=$this->getAdapter()->quoteInto('id=?', $id); 
    	   
		return  $this->update($_user_data,$where);
	}
	
	function comfirmEmail($email, $psw){
		$_user_data=array(
				'status'=> 1,				
				'modified_date'=> date('Y-m-d H:i:s')
		);
	
		$where='email = "'. $email . '" AND password = "'. $psw .'" AND status = 0';
	
		return  $this->update($_user_data,$where);
	}
	
	public function getUserLists($exp='', $sqlonly = false)
	{
		$sql="SELECT 	
				id
				,`name`
				,email
				,age
				,title
			 FROM ". $this->_name ." WHERE `status`=1 ".$exp;
		if($sqlonly) return $sql;
		$db=$this->getAdapter();
		return $db->fetchAll($sql);		
	}	
	
	//check email
	public function isExistEmail($email){
		$sql="SELECT email FROM ". $this->_name ." WHERE email='".$email."'";		
		$rs=$this->getAdapter()->fetchAll($sql);
		if($rs) return true;
		return false;
	}	
	
	/**
	 * To check email have or not
	 * have before allow to user register
	 * @param string $email
	 */
	public function checkStatusBy($login){
		$db = $this->getAdapter();
		$sql = "SELECT status FROM $this->_name WHERE user_name = '". $login ."'";
		$row = $db->fetchRow($sql);
		return $row['status'];
	}
	
	/**
	 * To get all acl of a user type
	 * @param string $user_type_id
	 */
	public function getArrAcl($user_type_id){
		$db = $this->getAdapter();
		$sql = "SELECT ua.user_type_id,aa.module, aa.controller, aa.action FROM tb_acl_user_access AS ua  
		INNER JOIN tb_acl_acl AS aa ON (ua.acl_id=aa.acl_id) WHERE ua.user_type_id='".$user_type_id."'";
		$rows = $db->fetchAll($sql);
		//print_r($rows);exit();
		return $rows;
	}
	
	public function getArrConMod( $user_type_id, $module){
		$db = $this->getAdapter();
		$sql = "SELECT DISTINCT aa.controller 
				FROM tb_acl_user_access AS ua  
					 INNER JOIN tb_acl_acl AS aa ON (ua.acl_id=aa.acl_id) 
				WHERE ua.user_type_id='".$user_type_id."' AND aa.module = '" . $module ."' AND aa.status = 1 ORDER BY aa.rank ASC";
		//echo $sql;exit;
		$cols = $db->fetchCol($sql);
		return $cols;
	}
}

