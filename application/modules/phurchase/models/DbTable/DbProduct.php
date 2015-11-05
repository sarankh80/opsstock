<?php

class product_Model_DbTable_DbProduct extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_product";
	
	public function getAllCate(){
		$db = $this->getAdapter();
		$sql ="SELECT p.*,(SELECT c.`cat_name_km` FROM `tb_pro_category` AS c WHERE c.`cat_id`= p.cat_id LIMIT 1)AS cat_name,
				c.`cu_name_en`,c.`cu_name_km`,c.icon as icons FROM $this->_name AS p,`tb_currency` AS c WHERE p.cu_id = c.cu_id";
		//echo $sql;
		return $db->fetchAll($sql);
	}
	public function getCateById($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE pro_id=$id ";
		return $db->fetchRow($sql);
	}
    public function addProCate($data){
    	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$image = str_replace(" ", "_", $data["name_en"]) . '.png';
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
	    		'cat_id'			=>	$data["cat_id"],
	    		'pro_name_en'		=>	$data["name_en"],
	    		'pro_name_km'		=>	$data["name_km"],
	    		'pro_code'			=>	$data["id_code"],
	    		'cu_id'				=>	$data["currency"],
	    		'price_out'			=>	$data["price"],
	    		'description'			=>	$data["description"],
	    		'status'			=>	$data["status"],
	    		'date'				=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'user_id'			=>	$GetUserId,
	    		
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
	    	$image = str_replace(" ", "_", $data["name_en"]) . '.png';
	    	$upload = new Zend_File_Transfer();
	    	$upload->addFilter('Rename',
	    			array('target' => PUBLIC_PATH . '/icon/'. $image, 'overwrite' => true) ,'icon');
	    	$receive = $upload->receive();
	    	if($receive)
	    	{
	    		$data['icon'] = $image;
	    	}elseif($receive ==""){
    			$dbs = $this->getAdapter();
    			$sql = "SELECT icon FROM $this->_name WHERE pro_id =".$id;
    			$old_photo = $db->fetchRow($sql);
    			foreach ($old_photo as $old_photos){
    				$data['icon'] = $old_photos;
    			}
	    	}
	    	$arr = array(
	    		'cat_id'			=>	$data["cat_id"],
	    		'pro_name_en'		=>	$data["name_en"],
	    		'pro_name_km'		=>	$data["name_km"],
	    		'pro_code'			=>	$data["id_code"],
	    		'cu_id'				=>	$data["currency"],
	    		'price_out'			=>	$data["price"],
	    		'description'			=>	$data["description"],
	    		'status'			=>	$data["status"],
	    		'date'				=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'user_id'			=>	$GetUserId,
	    		
	    	);
	    	$where = $this->getAdapter()->quoteInto('pro_id=?', $id);
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
    	$where = $this->getAdapter()->quoteInto('pro_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"status"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('pro_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function deleteCat($id) {
    	$db = $this->getAdapter();
    	$sql = "Delete From $this->_name WHERE pro_id=".$id;
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
    	return "KEM-".$pre.$new_acc_no;
    }
    public function getAllPro(){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM tb_currency";
    	return $db->fetchAll($sql);
    }
}

