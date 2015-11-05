<?php

class brand_Model_DbTable_DbBrand extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_brand";
	
public function getAllBrand(){
		$db = $this->getAdapter();
		try {
			$this->_name = "tb_brand";
			$sql = "SELECT c.`brand_id`,c.`name_en`,c.`name_kh`,c.`IsActive`,c.icon FROM $this->_name AS c";
			return $db->fetchAll($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	public function getBrandById($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE brand_id=$id ";
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
	    		'parent_id'	=> 	$data["parent_id"],
				'name_kh'	=>	$data["name_km"],
				'name_en'	=>	$data["name_en"],
				'IsActive'	=>	$data["status"],
	    		'date'		=>	date("y-m-d"),
	    		'icon'		=>	$data["icon"],
	    		'user_id'	=>	$GetUserId,
	    		
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
    			$sql = "SELECT icon FROM $this->_name WHERE brand_id =".$id;
    			$old_photo = $db->fetchRow($sql);
    			foreach ($old_photo as $old_photos){
    				$data['icon'] = $old_photos;
    			}
	    	}
	    	$arr = array(
	    		'parent_id'	=> 	$data["parent_id"],
				'name_kh'	=>	$data["name_km"],
				'name_en'	=>	$data["name_en"],
				'IsActive'	=>	$data["status"],
	    		'date'		=>	date("y-m-d"),
	    		'icon'		=>	$data["icon"],
	    		'user_id'	=>	$GetUserId,
	    		
	    	);
	    	$where = $this->getAdapter()->quoteInto('brand_id=?', $id);
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
    	$where = $this->getAdapter()->quoteInto('brand_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"status"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('brand_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function delete($id) {
    	$db = $this->getAdapter();
    	$sql = "Delete From $this->_name WHERE brand_id=".$id;
    	$row = $db->query($sql);
    	return $row;
    }
    public function getExistName($data){
    	$db = $this->getAdapter();
    	$brand_name = $data["name"];
    	$type = $data["type"];
    	
    	if($type==1){
    		$sql = "SELECT name_en as brand_name FROM $this->_name WHERE name_en = '$brand_name'";
    	}else{
    		$sql = "SELECT name_km as brand_name FROM $this->_name WHERE name_km = '$brand_name'";
    	}
    	return $db->fetchAll($sql);
    }
}

