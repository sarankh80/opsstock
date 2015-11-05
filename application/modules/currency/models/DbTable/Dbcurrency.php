<?php

class currency_Model_DbTable_Dbcurrency extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_currency";
	
	public function getExchange(){
    	$db = $this->getAdapter();
    	$sql="SELECT 
			  e.id,
			  e.`rate`,
			  (SELECT 
			    CONCAT(c.cu_name_km, '-',c.cu_name_en) 
			  FROM
			    `tb_currency` AS c 
			  WHERE c.cu_id = e.`from_cu`) AS from_cu,
			  (SELECT 
			    CONCAT(c.cu_name_km, '-',c.cu_name_en) 
			  FROM
			    `tb_currency` AS c 
			  WHERE c.cu_id = e.`to_cu`) AS to_cu 
			FROM
			  `tb_exchange` AS e WHERE e.`status`=1";
    	return $db->fetchAll($sql);
    }
	public function updateExchange($data){
   		$db = $this->getAdapter();
   		$identify = $data["identity"];
			$ids = explode(',',$identify);
			foreach ($ids as $i){
				$arr = array(
					'rate'	=> $data["rate_".$i],
				);
				$this->_name="tb_exchange";
				$where = $db->quoteInto("id=?", $data["id_".$i]);
				$this->update($arr, $where);
			}
			
   }
	public function getAllCate(){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name ";
		return $db->fetchAll($sql);
	}
	public function getCateById($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE cu_id=$id ";
		return $db->fetchRow($sql);
	}
	public function getCuById(){
		$db = $this->getAdapter();
		$sql ="SELECT cu_id,rate FROM $this->_name WHERE cu_id=1 ";
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
	    		'cu_name_en'			=>	$data["name_en"],
	    		'cu_name_km'			=>	$data["name_km"],
	    		'status'			=>	$data["status"],
	    		'date'				=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'rate'				=>	$data["rate"],
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
	    	$arr = array(
	    		'cu_name_en'			=>	$data["name_en"],
	    		'cu_name_km'			=>	$data["name_km"],
	    		'status'			=>	$data["status"],
	    		'date'				=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'rate'				=>	$data["rate"],
	    		'user_id'			=>	$GetUserId,
	    	);
	    	$where = $this->getAdapter()->quoteInto('cu_id=?', $id);
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
    	$where = $this->getAdapter()->quoteInto('cu_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"status"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('cu_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function deleteCat($id) {
    	$db = $this->getAdapter();
    	$sql = "Delete From $this->_name WHERE cu_id=".$id;
    	$row = $db->query($sql);
    	return $row;
    }
    public function getExistCat($data){
    	$db = $this->getAdapter();
    	$cat_name = $data["name"];
    	$type = $data["type"];
    	
    	if($type==1){
    		$sql = "SELECT cu_name_en as cat_name FROM tb_currency WHERE cu_name_en = '$cat_name'";
    	}else{
    		$sql = "SELECT cu_name_km as cat_name FROM tb_currency WHERE cU_name_km = '$cat_name'";
    	}
    	return $db->fetchAll($sql);
    }
    public static function getCallteralCode(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$sql = "SELECT COUNT(cu_id) AS amount FROM tb_currency";
    	$acc_no= $db->getGlobalDbRow($sql);
    	$acc_no=$acc_no['amount'];
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "";
    	for($i = $acc_no;$i<3;$i++){
    		$pre.='0';
    	}
    	return "TB-".$pre.$new_acc_no;
    }
}

