<?php

class customer_Model_DbTable_DbCustomer extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_customer";
	
public function getAllCustomer(){
		$db = $this->getAdapter();
		try {
			$sql = "SELECT * FROM $this->_name AS c";
			return $db->fetchAll($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	public function getCustomerById($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE customer_id=$id ";
		return $db->fetchRow($sql);
	}
    public function add($data){
    	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$image = str_replace(" ", "_", $data["name_en"]) . '.jpg';
	    	$upload = new Zend_File_Transfer();
	    	$upload->addFilter('Rename',
	    			array('target' => PUBLIC_PATH . '/icon/'. $image, 'overwrite' => true) ,'icon');
	    	$receive = $upload->receive();
	    	if($receive)
	    	{
	    		$data['icon'] = $image;
	    	}
	    	else{
	    		$data['icon']="";
	    	}
    	
	    	$arr = array(
	    			'customer_code'	=>  $data["customer_code"],
					'name_kh'		=>	$data["name_km"],
					'name_en'		=>	$data["name_en"],
					'user_id'		=>	1,
					'status'		=>	$data["status"],
					'contact'		=>	$data["contact"],
					'phone'			=>	$data["tel"],
					'fax'			=>	$data["fax"],
					'email'			=>	$data["email"],
					'company'		=>	$data["company"],
					'last_mod_date'	=>	new Zend_Date(),
					'address'		=>	$data["address"],
					'description'	=>	$data["description"],
	    			'user_id'		=>	$GetUserId,
	    			'icon'			=>	$data["icon"]
	    		
	    	);
	    	$this->insert($arr);
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    		exit();
    	}
    }
	public function updat($data,$id){
	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$image = str_replace(" ", "_", $data["name_en"]) . '.jpg';
	    	$upload = new Zend_File_Transfer();
	    	$upload->addFilter('Rename',
	    			array('target' => PUBLIC_PATH . '/icon/'. $image, 'overwrite' => true) ,'icon');
	    	$receive = $upload->receive();
	    	if($receive)
	    	{
	    		$data['icon'] = $image;
	    	}elseif($receive ==""){
    			$dbs = $this->getAdapter();
    			$sql = "SELECT icon FROM $this->_name WHERE Customer_id =".$id;
    			$old_photo = $db->fetchRow($sql);
    			foreach ($old_photo as $old_photos){
    				$data['icon'] = $old_photos;
    			}
	    	}
	    	$arr = array(
	    			'customer_code'	=>  $data["customer_code"],
					'name_kh'		=>	$data["name_en"],
					'name_en'		=>	$data["name_km"],
					'user_id'		=>	1,
					'status'		=>	$data["status"],
					'contact'		=>	$data["contact"],
					'phone'			=>	$data["tel"],
					'fax'			=>	$data["fax"],
					'email'			=>	$data["email"],
					'company'		=>	$data["company"],
					'last_mod_date'	=>	new Zend_Date(),
					'address'		=>	$data["address"],
					'description'	=>	$data["description"],
	    			'user_id'		=>	$GetUserId,
	    			'icon'			=>	$data["icon"]
	    		
	    	);
	    	$where = $this->getAdapter()->quoteInto('customer_id=?', $id);
	    	$this->update($arr, $where);
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    		exit();
    	}
    }
    public function updateStatus($arr)
    {
    	$array_data = array(
    			"status"	=>	1
    	);
    	$where = $this->getAdapter()->quoteInto('Customer_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"status"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('Customer_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function delete($id) {
    	$db = $this->getAdapter();
    	$sql = "Delete From $this->_name WHERE Customer_id=".$id;
    	$row = $db->query($sql);
    	return $row;
    }
    public function getExistName($data){
    	$db = $this->getAdapter();
    	$Customer_name = $data["name"];
    	$type = $data["type"];
    	
    	if($type==1){
    		$sql = "SELECT name_en as Customer_name FROM $this->_name WHERE name_en = '$Customer_name'";
    	}else{
    		$sql = "SELECT name_km as Customer_name FROM $this->_name WHERE name_km = '$Customer_name'";
    	}
    	return $db->fetchAll($sql);
    }
}

