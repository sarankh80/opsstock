<?php

class Application_Model_DbTable_DbGlobal extends Zend_Db_Table_Abstract
{
    // set name value
	public function setName($name){
		$this->_name=$name;
	}
	
	/**
	 * get selected record of $sql
	 * @param string $sql
	 * @return array $row;
	 */
	 public function getPurchaseNo(){
		$db = $this->getAdapter();
		$sql = 'SELECT s.`order`,s.`order_id` FROM `tb_purchase_order` AS s';
		return $db->fetchAll($sql);
	}
	public function getOrderNo(){
		$db = $this->getAdapter();
		$sql = 'SELECT s.`order_no`,s.`so_id` FROM `tb_sale_order` AS s';
		return $db->fetchAll($sql);
	}
	public function getQtyWarning($data){
		$db = $this->getAdapter();
		$id = $data["id"];
		$this->_name = "tb_product";
		$sql="SELECT p.`qty_onhand` FROM $this->_name AS p WHERE p.`pro_id`=$id";
		//return $sql;
		return $db->fetchOne($sql);
	}
	public function getProductOption(){
		$db = $this->getAdapter();
		$sql_cate = 'SELECT c.`cat_id`,c.`cat_name_en` FROM `tb_pro_category` AS c WHERE c.`status`=1 AND c.`cat_name_en`!=""';
		$row_cate = $db->fetchAll($sql_cate);
		$option="";
		$option.='<option value="0"> - - - - - ជ្រើសរើស ផលិតផល  - - - - -  </optgroup>';
		foreach($row_cate as $cate){
			$cat_id = $cate['cat_id'];
				
				
			$option .= '<optgroup  label="'.htmlspecialchars($cate['cat_name_en'], ENT_QUOTES).'">';
			$sql = "SELECT p.`pro_id`,p.`item_code`,p.`name_en`,p.`name_kh`  FROM `tb_product` AS p WHERE p.`cate_id`=$cat_id AND p.`name_en`!=''";
			$rows = $db->fetchAll($sql);
			if($rows){
				foreach($rows as $value){
					$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['item_code'], ENT_QUOTES).'">'.htmlspecialchars($value['item_code'], ENT_QUOTES)." -<br />".
							htmlspecialchars($value['name_en'], ENT_QUOTES)." -<br />".htmlspecialchars($value['name_kh'], ENT_QUOTES)
							.'</option>';
				}
			}
			$option.="</optgroup>";
		}
		return $option;
	}
	public static function getProductCode(){
		$db = new Application_Model_DbTable_DbGlobal();
		$sql = "SELECT  pro_id as amount FROM `tb_product` ORDER BY pro_id DESC LIMIT 1 ";
		$acc_no= $db->getGlobalDbRow($sql);
		$acc_no=$acc_no['amount'];
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre = "";
		for($i = $acc_no;$i<3;$i++){
			$pre.='0';
		}
		return "KEM".$pre.$new_acc_no;
	}
	public function getSaleOrderById($id){
		$db = $this->getAdapter();
		$sql="SELECT s.`so_id`,s.`order_no`,s.`customer_id`,s.`currency_id`,s.`all_total`,s.`balance`,s.`paid`,s.date FROM `tb_sale_order` AS s WHERE s.`so_id`=$id";
		return $db->fetchRow($sql);
	}
	public function getSaleOrderDetail($id){
		$db = $this->getAdapter();
		$sql="SELECT 
			  sd.`pro_id`,
			  sd.`unit_qty`,
			  sd.`unit_price`,
			  sd.`qty`,
			  sd.`price_per_unit`,
			  sd.`total_qty`,
			  sd.`total_price`,
			  sd.`sub_total`,
			  p.`cu_id`,
			  p.`name_kh`,
			  p.`name_en`,
			  p.`item_size`,
			  p.`price_per_qty`,
			  p.`unit_sale_price`,
			  p.`qty_perunit`
			FROM
			  `tb_sale_order_detail` AS sd ,
			  `tb_product` AS p
			WHERE sd.`so_id` = $id AND sd.`pro_id`= p.`pro_id` AND sd.status=1";
		//echo $sql;
		return $db->fetchAll($sql);
	}
	public function InventoryExist($pro_id){
		$db=$this->getAdapter();
		$sql="SELECT * FROM tb_product WHERE pro_id =".$pro_id." LIMIT 1";
		$row = $db->fetchRow($sql);
		if(!$row) return false;
		return $row;
	}
	public function getQtyProductExist($id){
		$db = $this->getAdapter();
		$this->_name ="tb_product";
		$sql="SELECT p.`qty_available`,p.`qty_onhand`,p.`qty_onsold` FROM $this->_name AS p WHERE p.`pro_id`=$id";
		echo $sql;
		return $db->fetchRow($sql);
	}
	public function getQtyProLocationExist($id){
		$db = $this->getAdapter();
		$this->_name ="tb_prolocation";
		$sql="SELECT p.`qty_available`,p.`qty_onhand`,p.`qty_onsold` FROM $this->_name AS p WHERE p.`pro_id`=$id AND p.location_id=1";
		return $db->fetchRow($sql);
	}
	 public function getCurrencyIcon($data){
		 $db = $this->getAdapter();
		 $id = $data["cu_id"];
		 $this->_name = "tb_currency";
		 $sql = "SELECT e.icon FROM $this->_name AS e WHERE e.`status`=1 AND e.cu_id=$id";
		 //return $sql;
		 return $db->fetchOne($sql);
	 }
	 public function getAllRate(){
		 $db = $this->getAdapter();
		 $this->_name = "tb_exchange";
		 $sql = "SELECT e.`from_cu`,e.`to_cu`,e.`rate` FROM $this->_name AS e WHERE e.`status`=1";
		 return $db->fetchAll($sql);
	 }
	 public function getRateById(){
	 	
	 }
	 
	 public function getRateByCurrencyId($data){
	 	$db = $this->getAdapter();
	 	$from = $data["from_cu"];
	 	$to = $data["to_cu"];
	 	$this->_name = "tb_exchange";
	 	$sql= "SELECT e.`rate` FROM $this->_name AS e WHERE e.`from_cu`=$from AND e.`to_cu`=$to AND e.`status`=1";
	 	//return $sql;
	 	return $db->fetchOne($sql);
	 }
	public function getSettingByCode($code){
		$db = $this->getAdapter();
		$this->_name ="tb_setting";
		$sql="SELECT s.`key_value` FROM $this->_name AS s WHERE s.`code`=$code";
		return $db->fetchOne($sql);
	}
	public function getProductById($data){
		$db = $this->getAdapter();
		$id = $data["id"];
		$this->_name = "tb_product";
		$sql="SELECT p.`pro_id`,p.`name_en`,p.`name_kh`,p.`price_per_qty`,p.`unit_sale_price`,p.`qty_onhand`,p.`item_size`,p.`cu_id`,c.`rate`,p.`qty_perunit`,c.icon FROM $this->_name AS p , `tb_currency` AS c WHERE p.`cu_id`=c.`cu_id` AND p.`pro_id`=$id";
		return $db->fetchRow($sql);
	}
	public function getCustomer(){
		$db=$this->getAdapter();
		$this->_name="tb_customer";
		$sql="SELECT c.`customer_id`,`name_en`,c.`name_kh` FROM $this->_name AS c WHERE c.status=1";
		return $db->fetchAll($sql);
	}
	public function getAllCurrency(){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM tb_currency";
		return $db->fetchAll($sql);
	}
	
	public function getAllProCategories(){
		$db = $this->getAdapter();
		$this->_name = "tb_pro_category";
		$sql = "SELECT pc.`cat_id`,pc.`cat_name_en`,pc.`cat_name_km` FROM $this->_name AS pc";
		return $db->fetchAll($sql);
	}
	public function getMeasure(){
		$db = $this->getAdapter();
		$this->_name = "tb_measure";
		$sql = "SELECT id,name_kh,name_en FROM $this->_name WHERE public=1";
		return $db->fetchAll($sql);
	}
	
	public function getAllBrand(){
		$db = $this->getAdapter();
		try {
			$this->_name = "tb_brand";
			$sql = "SELECT c.`brand_id`,c.`name_en`,c.`name_kh`,c.`IsActive` FROM $this->_name AS c";
			return $db->fetchAll($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	public function getGlobalDb($sql)
  	{
  		$db=$this->getAdapter();
  		$row=$db->fetchAll($sql);
  		if(!$row) return NULL;
  		return $row;
  	}
  	public function getGlobalDbRow($sql)
  	{
  		$db=$this->getAdapter();
  		$row=$db->fetchRow($sql);
  		if(!$row) return NULL;
  		return $row;
  	}
  	public static function getActionAccess($action)
    {
    	$arr=explode('-', $action);
    	return $arr[0];    	
    }     
    public function addRecord($data,$tbl_name){
    	$this->setName($tbl_name);
    	return $this->insert($data);
    }
    
    
    /**
     * update record to table $tbl_name
     * @param array $data
     * @param int $id
     * @param string $tbl_name
     */
//     public function updateRecord($data,$id,$tbl_name){
//     	$this->setName($tbl_name);
//     	$where=$this->getAdapter()->quoteInto('pro_id=?',$id);
//     	$this->update($data,$where);
//     }
	public function updateRecord($data,$id,$updateby,$tbl_name){
		$this->setName($tbl_name);
		$where=$this->getAdapter()->quoteInto($updateby.'=?',$id);
		$this->update($data,$where);
	}
    
    public function DeleteRecord($tbl_name,$id){
    	$db = $this->getAdapter();
		$sql = "UPDATE ".$tbl_name." SET status=0 WHERE id=".$id;
		return $db->query($sql);
    }

    public function deleteRecords($sql){
    	$db = $this->getAdapter();
		return $db->query($sql);
    } 

     public function DeleteData($tbl_name,$where){
    	$db = $this->getAdapter();
		$sql = "DELETE FROM ".$tbl_name.$where;
		return $db->query($sql);
    } 
    
    public function convertStringToDate($date, $format = "Y-m-d H:i:s")
    {
    	if(empty($date)) return NULL;
    	$time = strtotime($date);
    	return date($format, $time);
    }
    /* @Desc: add or sub qty of item depend on item and stock
     * @param $stockID stock id
     * @param $itemQtys array of item id and item qty
     * @param $sign: + | -
     * */
    public function query($sql){
    	$db = $this->getAdapter();
    	return $db->query($sql);	
    }
    public function fetchArray($result){
    	$db = $this->getAdapter();
    	return mysql_fetch_assoc($result);
    }
    public function getAccessPermission($level, $str_condition,  $location_id){
    	if($level==1 OR $level==2){
    		return false;
    	}
    	else{
    		$result = "$str_condition =".$location_id;
    		return $result;
    	} 
    }
    
    public function getLocation(){
    	$sql ="SELECT * FROM tbwu_location WHERE 1";
    	return $this->getGlobalDb($sql);
    }
    public function getLocationByID($id){
    	$sql ="SELECT * FROM tbwu_location WHERE location_id=$id";
    	return $this->getGlobalDbRow($sql);
    }
}
?>