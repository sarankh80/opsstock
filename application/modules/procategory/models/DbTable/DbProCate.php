<?php

class procategory_Model_DbTable_DbProCate extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_pro_category";
	
	public function getAllCate(){
		$db = $this->getAdapter();
		$this->_name="tb_pro_category";
		$sql ="SELECT * FROM $this->_name ";
		return $db->fetchAll($sql);
	}
	public function getCateById($id){
		$db = $this->getAdapter();
		$this->_name="tb_pro_category";
		$sql ="SELECT * FROM $this->_name WHERE cat_id=$id ";
		return $db->fetchRow($sql);
	}
    public function addProCate($data){
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
	    		'cat_name_en'		=>	$data["name_en"],
	    		'cat_name_km'		=>	$data["name_km"],
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
    			$sql = "SELECT icon FROM $this->_name WHERE cat_id =".$id;
    			$old_photo = $db->fetchRow($sql);
    			foreach ($old_photo as $old_photos){
    				$data['icon'] = $old_photos;
    			}
	    	}
	    	$arr = array(
	    		'cat_name_en'		=>	$data["name_en"],
	    		'cat_name_km'		=>	$data["name_km"],
	    		'status'			=>	$data["status"],
	    		'date'				=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'user_id'			=>	$GetUserId,
	    		
	    	);
	    	$where = $this->getAdapter()->quoteInto('cat_id=?', $id);
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
    	$where = $this->getAdapter()->quoteInto('cat_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"status"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('cat_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function deleteCat($id) {
    	$db = $this->getAdapter();
    	$sql = "Delete From $this->_name WHERE cat_id=".$id;
    	$row = $db->query($sql);
    	return $row;
    }
    public function getExistCat($data){
    	$db = $this->getAdapter();
    	$cat_name = $data["name"];
    	$type = $data["type"];
    	
    	if($type==1){
    		$sql = "SELECT cat_name_en as cat_name FROM tb_pro_category WHERE cat_name_en = '$cat_name'";
    	}else{
    		$sql = "SELECT cat_name_km as cat_name FROM tb_pro_category WHERE cat_name_km = '$cat_name'";
    	}
    	return $db->fetchAll($sql);
    }
}

