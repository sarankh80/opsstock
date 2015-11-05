<?php

class users_Model_DbTable_DbUserType extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_user_type';
	//function for getting record user_type by user_type_id
	public function getUserType($user_id)
	{
		$select=$this->select();		
		$select->where('user_type_id=?',$user_id);
		$row=$this->fetchRow($select);
		if(!$row) return NULL;
		return $row->toArray();
	}
	public function getUserTypes(){
		$db = $this->getAdapter();
		$sql='SELECT * FROM tb_user_type';
		$stm = $db->query($sql);
		$row=$stm->fetchAll();
		if(!$row) return NULL;
		return $row;
	}
	public function updateStatus($arr)
	{
		$array_data = array("status" =>	1);
		$where = $this->getAdapter()->quoteInto('user_type_id=?', $arr["unstatus"]);
		$this->update($array_data, $where);
			
	}
	public function updateUnStatus($arr)
	{
		$array_data = array( "status"	=>	0);
		$where = $this->getAdapter()->quoteInto('user_type_id=?', $arr["status"]);
		$this->update($array_data, $where);
			
	}
	public function insertUserType($arr)
	{
		$data=array(); 
		$data['user_type']= $arr['user_type'];   	
     	$data['status']= 1;
    	return $this->insert($data); 
	}	
	public function updateUserType($arr,$id)
	{
		$data=array(); 	
		$data['user_type']=$arr['user_type'];   	
    	$where=$this->getAdapter()->quoteInto('user_type_id=?',$id);
		$this->update($data,$where); 
	}
	public function deleteUserType($id) {
		$db = $this->getAdapter();
		$sql = "Delete From tb_user_type Where user_type_id=".$id;
		$row = $db->query($sql);
		return $row;
	}
}

