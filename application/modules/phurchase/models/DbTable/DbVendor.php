<?php

class phurchase_Model_DbTable_DbVendor extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_vendor";
	
	public function getAllCate(){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name";
		//echo $sql;
		return $db->fetchAll($sql);
	}
	public function getCateById($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE vendor_id=$id ";
		return $db->fetchRow($sql);
	}
    public function addProCate($data){
    	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$arr = array(
	    		'v_name'		=>	$data["vendor_name"],
	    		'phone'			=>	$data["phone"],
	    		'contact_name'				=>	$data["contact"],
	    		'email'			=>	$data["email"],
	    		'vendor_remark'			=>	$data["address"],
	    		'is_active'			=>	$data["status"],
	    		'last_mod_date'		=>	new Zend_Date(),
	    		'last_usermod'		=>	$GetUserId,
	    		
	    	);
	    	$this->insert($arr);
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    		exit();
    	}
    }
	public function updatCat($data,$id){
	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$arr = array(
	    		'v_name'		=>	$data["vendor_name"],
	    		'phone'			=>	$data["phone"],
	    		'contact_name'				=>	$data["contact"],
	    		'email'			=>	$data["email"],
	    		'vendor_remark'			=>	$data["address"],
	    		'is_active'			=>	$data["status"],
	    		'last_mod_date'		=>	new Zend_Date(),
	    		'last_usermod'		=>	$GetUserId,
	    		
	    	);
	    	$where = $this->getAdapter()->quoteInto('vendor_id=?', $id);
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
    			"is_active"	=>	1
    	);
    	$where = $this->getAdapter()->quoteInto('vendor_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"is_active"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('vendor_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function deleteCat($id) {
    	$db = $this->getAdapter();
    	$sql = "Delete From $this->_name WHERE vendor_id=".$id;
    	$row = $db->query($sql);
    	return $row;
    }
    public function getExistCat($data){
    	$db = $this->getAdapter();
    	$cat_name = $data["name"];
    	$type = $data["type"];
    	
    	if($type==1){
    		$sql = "SELECT pro_name_en as cat_name FROM $this->_name WHERE pro_name_en = '$cat_name'";
    	}else{
    		$sql = "SELECT pro_name_km as cat_name FROM $this->_name WHERE pro_name_km = '$cat_name'";
    	}
    	return $db->fetchAll($sql);
    }
    public static function getCallteralCode(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$sql = "SELECT COUNT(pro_id) AS amount FROM tb_product";
    	$acc_no= $db->getGlobalDbRow($sql);
    	$acc_no=$acc_no['amount'];
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "";
    	for($i = $acc_no;$i<5;$i++){
    		$pre.='0';
    	}
    	return "PC-".$pre.$new_acc_no;
    }
    public function getAllPro(){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM tb_currency";
    	return $db->fetchAll($sql);
    }
}

