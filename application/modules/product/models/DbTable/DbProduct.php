<?php

class product_Model_DbTable_DbProduct extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_product";
	
	public static function getProductCode(){
		$db = new Application_Model_DbTable_DbGlobal();
		$sql = "SELECT COUNT(pro-id) AS amount FROM $this->_name";
		$acc_no= $db->getGlobalDbRow($sql);
		$acc_no=$acc_no['amount'];
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre = "";
		for($i = $acc_no;$i<2;$i++){
			$pre.='0';
		}
		return "KEM-".$pre.$new_acc_no;
	}
	public function getBarCode($search){
		$db = $this->getAdapter();
		$sql ="SELECT CONCAT(p.`item_code`,'-',p.`name_kh`,'-',p.`name_en`) AS `name`,p.`item_code` FROM `tb_product` AS p WHERE 1";
		$where = '';
		if($search["cate_id"]>0){
			$where.=" AND p.cate_id=".$search["cate_id"];
		}
		if($search["brand"]>0){
			$where.=" AND p.brand_id=".$search["brand"];
		}
		$order = " ORDER BY p.name_en ASC";
		//echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);
	}
	public function getProductAdjust($search){
		$db = $this->getAdapter();
		$sql="SELECT p.`pro_id`,p.`name_kh`,p.`name_en`,p.`item_code`,p.`item_size`,p.`qty_onhand`,p.`qty_available`,p.`qty_perunit`,p.`measure_id`,m.name_kh as measure FROM `tb_product` AS p, tb_measure AS m WHERE p.measure_id = m.id AND p.status=1";
		$where ='';
		if($search["advance_search"] !=''){
			$s_where = array();
			$s_search = $search['advance_search'];
			$s_where[] = " p.name_en LIKE '%{$s_search}%'";
			$s_where[] = " p.name_kh LIKE '%{$s_search}%'";
			$s_where[] = " p.item_code LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search["cate_id"] >0){
			$where .=" AND p.cate_id=".$search["cate_id"];
		}
		if($search["brand"] >0){
			$where .=" AND p.brand_id=".$search["brand"];
		}
		if($search["pro_id"] >0){
			$where .=" AND p.pro_id=".$search["pro_id"];
		}
		//echo $sql;
		return $db->fetchAll($sql.$where);
	}
	public function getAllProduct($search){
		$db = $this->getAdapter();
		$sql ="SELECT p.*,(SELECT c.`cat_name_km` FROM `tb_pro_category` AS c WHERE c.`cat_id`= p.cate_id LIMIT 1)AS cat_name,
				c.`cu_name_en`,c.`cu_name_km`,c.icon as icons FROM $this->_name AS p,`tb_currency` AS c WHERE p.cu_id = c.cu_id AND p.status = 1 AND p.name_en !='' ";
				
		$where ='';
		if($search["advance_search"] !=''){
			$s_where = array();
			$s_search = $search['advance_search'];
			$s_where[] = " p.name_en LIKE '%{$s_search}%'";
			$s_where[] = " p.name_kh LIKE '%{$s_search}%'";
			$s_where[] = " p.item_code LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search["cate_id"] >0){
			$where .=" AND p.cate_id=".$search["cate_id"];
		}
		if($search["brand"] >0){
			$where .=" AND p.brand_id=".$search["brand"];
		}
		if($search["pro_id"] >0){
			$where .=" AND p.pro_id=".$search["pro_id"];
		}
		//echo $sql;
		$order = " ORDER BY p.item_code DESC";
		return $db->fetchAll($sql.$where.$order);
	}
	public function getProductExist($id){
		$db = $this->getAdapter();
		$sql = "SELECT p.`qty_onhand` AS p_qty_onhand,p.`qty_available` AS p_qty_aavailable,pl.`qty_onhand`,pl.`qty_available` FROM `tb_product` AS p , tb_prolocation AS pl WHERE p.`pro_id`=pl.`pro_id` AND pl.`location_id`=1 AND p.`pro_id`=$id";
		return $db->fetchRow($sql);
	}
	public function getProductCodesById($id){
		$db = $this->getAdapter();
		$sql ="SELECT p.name_en,p.name_kh,p.item_code,p.item_size FROM $this->_name AS p WHERE p.pro_id='$id' ";
		return $db->fetchRow($sql);
	}
	public function getProductById($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE pro_id=$id ";
		return $db->fetchRow($sql);
	}
    public function add($data){
    	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$image = str_replace(" ", "_", $data["pro_code"]) . '.jpg';
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
	    		'cate_id'			=>	$data["cat_id"],
	    		'name_en'			=>	$data["name_en"],
	    		'name_kh'			=>	$data["name_km"],
	    		'item_code'			=>	$data["pro_code"],
	    		'cu_id'				=>	$data["currency"],
	    		'unit_sale_price'	=>	$data["unit_price"],
	    		'price_per_qty'		=>	$data["qty_price"],
	    		'brand_id'			=>	$data["brand"],
	    		'measure_id'		=>	$data["measure"],
	    		'label'				=>	$data["label"],
	    		'stock_type'		=>	$data["stock_type"],
	    		'item_size'			=>	$data["pro_size"],
	    		'qty_perunit'		=>	$data["qty_per_unit"],
	    		'qty_onhand'		=>	0,//($data["qty_unit_onhand"]*$data["qty_per_unit"])+$data["qty_onhand"],
	    		'qty_available'		=>	0,//($data["qty_unit_onhand"]*$data["qty_per_unit"])+$data["qty_onhand"],
	    		//'qty_onorder'		=>	$data[""],
	    		//'qty_onsold'		=>	$data[""],
	    		//'remark'			=>	$data[""],
	    		'description'		=>	$data["description"],
	    		'status'			=>	$data["status"],
	    		'last_mod_date'		=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'last_usermod'			=>	$GetUserId,
	    		
	    	);
	    	$id = $this->insert($arr);
	    	
	    	$arr_l = array(
	    			'pro_id'			=>	$id,
	    			'location_id'		=>	1,
	    			'unit_sale_price'	=>	$data["unit_price"],
	    			'price_per_qty'		=>	$data["qty_price"],
	    			'qty_onhand'		=>	0, //($data["qty_unit_onhand"]*$data["qty_per_unit"])+$data["qty_onhand"],
	    			'qty_available'		=>	0, //($data["qty_unit_onhand"]*$data["qty_per_unit"])+$data["qty_onhand"],
	    			//'remark'			=>	$data[""],
	    			'last_mod_date'		=>	date("y-m-d"),
	    			'last_usermod'			=>	$GetUserId,
	    			 
	    	);
	    	$this->_name="tb_prolocation";
	    	$this->insert($arr_l);
	    	
	    	$data_history = array(
	    			'transaction_type'  => 1,
	    			'pro_id'     		=> $id,
	    			'date'				=> new Zend_Date(),
	    			'location_id' 		=> 1,
	    			//'Remark'			=> $post['remark'],
	    			'qty_edit'        	=> "0 -> 0",
	    			'qty_before'        => 0,//qty have in recode table
	    			'qty_after'        	=> 0,//$data["qty"],
	    			'user_mod'			=> $GetUserId
	    	);
	    	$db->insert("tb_move_history", $data_history);
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
    			$sql = "SELECT icon FROM $this->_name WHERE pro_id =".$id;
    			$old_photo = $db->fetchRow($sql);
    			foreach ($old_photo as $old_photos){
    				$data['icon'] = $old_photos;
    			}
	    	}
// 	    	$pro_exist = $this->getProductExist($id);
// 	    	if(!empty($pro_exist)){
// 	    		$p_qtyohand = $pro_exist["p_qty_onhand"];
// 	    		$p_qtyavailable = $pro_exist["p_qty_available"];
	    		
// 	    		$pl_qtyohand = $pro_exist["pl_qty_onhand"];
// 	    		$pl_qtyavailable = $pro_exist["pl_qty_available"];
// 	    	}
	    	$arr = array(
	    		'cate_id'			=>	$data["cat_id"],
	    		'name_en'			=>	$data["name_en"],
	    		'name_kh'			=>	$data["name_km"],
	    		'item_code'			=>	$data["pro_code"],
	    		'cu_id'				=>	$data["currency"],
	    		'unit_sale_price'	=>	$data["unit_price"],
	    		'price_per_qty'		=>	$data["qty_price"],
	    		'brand_id'			=>	$data["brand"],
	    		'measure_id'		=>	$data["measure"],
	    		'label'				=>	$data["label"],
	    		'stock_type'		=>	$data["stock_type"],
	    		'item_size'			=>	$data["pro_size"],
	    		'qty_perunit'		=>	$data["qty_per_unit"],
	    		//'qty_onhand'		=>	0,//$data["qty"],
	    		//'qty_available'		=>	0,//$data["qty"],
	    		//'qty_onorder'		=>	$data[""],
	    		//'qty_onsold'		=>	$data[""],
	    		//'remark'			=>	$data[""],
	    		'description'		=>	$data["description"],
	    		'status'			=>	$data["status"],
	    		'last_mod_date'		=>	date("y-m-d"),
	    		'icon'				=>	$data["icon"],
	    		'last_usermod'			=>	$GetUserId,
	    		
	    	);
	    	$where = $db->quoteInto("pro_id=?", $id);
	    	$this->update($arr, $where);
	    	
	    	$arr_l = array(
// 	    			'pro_id'			=>	$id,
// 	    			'location_id'		=>	1,
	    			'unit_sale_price'	=>	$data["unit_price"],
	    			'price_per_qty'		=>	$data["qty_price"],
	    			//'qty_onhand'		=>	$data["qty"],
	    			//'qty_available'		=>	$data["qty"],
	    			//'remark'			=>	$data[""],
	    			'last_mod_date'		=>	date("y-m-d"),
	    			'last_usermod'			=>	$GetUserId,
	    			 
	    	);
	    	
	    	$this->_name="tb_prolocation";
	    	$where = array("pro_id=?"=> $id,"location_id=?"=>1);
	    	$db->getProfiler()->setEnabled(true);
	    	$this->update($arr_l, $where);
	    	Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
	    	Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
	    	$db->getProfiler()->setEnabled(false);
	    	
	    	$data_history = array(
	    			'transaction_type'  => 1,
	    			'pro_id'     		=> $id,
	    			'date'				=> new Zend_Date(),
	    			'location_id' 		=> 1,
	    			//'Remark'			=> $post['remark'],
	    			'qty_edit'        	=> "0 -> ".$data["qty"],
	    			'qty_before'        => 0,//qty have in recode table
	    			'qty_after'        	=> $data["qty"],
	    			'user_mod'			=> $GetUserId
	    	);
	    	$db->insert("tb_move_history", $data_history);
// 	    	exit();
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    		exit();
    	}
    }
    
    public function adjust($data){
    	$session = new Zend_Session_Namespace('auth');
    	$GetUserId = $session->user_id;
    	$user_type = $session->level;
    	$db = $this->getAdapter();
    	$db_pro = new Application_Model_DbTable_DbGlobal();
    	$db->beginTransaction();
    	try {
    		$identify = explode(',',$data['identity']);
    		foreach ($identify as $i){
    				$arr_product_update = array(
							'qty_onhand'		=>	$data["total_qty".$i],
							'qty_available'		=>	$data["total_qty".$i],
					);
					$this->_name ="tb_product";
					$where = $db->quoteInto("pro_id=?", $data["item_id".$i]);
					$this->update($arr_product_update, $where);
					
					$arr_prolo_update = array(
							'qty_onhand'		=>	$data["total_qty".$i],
							'qty_available'		=>	$data["total_qty".$i],
					);
					$this->_name ="tb_prolocation";
					$where = array('pro_id=?'=>$data["item_id".$i],'location_id=?'=>1);
					$this->update($arr_prolo_update, $where);
    		}
//     		exit();
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
    public function delete($id) {
    	$db = $this->getAdapter();
		$array_data = array(
    			"status"	=>	0
    	);
		$where = $this->getAdapter()->quoteInto('pro_id=?', $id);
    	$this->update($array_data, $where);
    }
    public function getExistPro($data){
    	$db = $this->getAdapter();
    	$cat_name = $data["name"];
    	$type = $data["type"];
    	
    	if($type==1){
    		$sql = "SELECT name_en as cat_name FROM $this->_name WHERE name_en = '$cat_name'";
    	}elseif($type==2){
    		$sql = "SELECT name_kh as cat_name FROM $this->_name WHERE name_kh = '$cat_name'";
    	}else{
    		$sql = "SELECT item_code as cat_name FROM $this->_name WHERE item_code = '$cat_name'";
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
}

