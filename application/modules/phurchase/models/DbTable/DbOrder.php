<?php

class phurchase_Model_DbTable_DbOrder extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_purchase_order";
	
	public function getAllCate($search){
		$db = $this->getAdapter();
		$start_date = $search['start_date'];
		$end_date = $search['end_date'];
		$sql ="SELECT o.*,CONCAT(v.v_name,' - ',v.phone) AS vendor_name FROM tb_purchase_order AS o , tb_vendor AS v WHERE o.vendor_id = v.vendor_id AND o.is_active = 1";
	$where ="";
		if($search["txt_search"]!=""){
			$s_where = array();
			$s_search = $search['txt_search'];
			$s_where[] = "v.contact_name LIKE '%{$s_search}%'";
			$s_where[] = "v.v_name LIKE '%{$s_search}%'";
			$s_where[] = "v.phone LIKE '%{$s_search}%'";
	
			$s_where[] = "v.email LIKE '%{$s_search}%'";
			$s_where[] = "o.order LIKE '%{$s_search}%'";
	
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if(!empty($search['start_date']) or !empty($search['end_date'])){
			$where.=" AND o.`date_in` BETWEEN '$start_date' AND '$end_date'";
		}
		if($search["supplier"]>0){
			$where.= " AND v.vendor_id=".$search["supplier"];
		}
		if($search["purchase_no"]>0){
			$where.= " AND o.order_id=".$search["purchase_no"];
		}
		if($search["status"]>0){
			$where.= " AND o.is_active=".$search["status"];
		}
		if($search["pay_status"]>0){
			$where.= " AND o.status=".$search["pay_status"];
		}
		$order = " ORDER BY o.order_id DESC";
		//echo $sql;
		return $db->fetchAll($sql.$where.$order);
	}
	public function getPhurchase($id){
		$db = $this->getAdapter();
		$sql ="SELECT * FROM $this->_name WHERE order_id=$id ";
		return $db->fetchRow($sql);
	}
	public function vendorPurchaseOrderPayment($data)
	{
		try{
			$db_global = new Application_Model_DbTable_DbGlobal();
			$db = $this->getAdapter();	
			$db->beginTransaction();
			$older = $data['id_code'];
			$sql ="SELECT p.`order` FROM tb_purchase_order AS p WHERE p.`order`= '$older'";
			$order_code =  $db->fetchOne($sql);
			//print_r($sql);exit();
			if(!empty($order_code)){
				$order = $this->getCallteralCode();
			}else {
				$order = $older;
			}
		
			$session_user=new Zend_Session_Namespace('auth');
			$userName=$session_user->user_name;
			$GetUserId= $session_user->user_id;			
			$info_purchase_order=array(
					"vendor_id"      => 	$data['vendor'],
					"order"      => 	$order,
					"date_in"     	 => 	$data['date'],
					"status"         => 	$data['status'],
					"user_mod"       => 	$GetUserId,
					"timestamp"      => 	new Zend_Date(),
					"paid"           => 	$data['paid'],
					"discount"           => 	$data['discount'],
					"all_total"      => 	$data['totalAmoun'],
					"balance"        => 	$data['remain']
			);
			 $this->_name="tb_purchase_order";
			$purchase_id = $this->insert($info_purchase_order); 			
			unset($datainfo); unset($idrecord);		
			$ids=explode(',',$data['identity']);
			foreach ($ids as $i)
			{
				$itemId=$data['item_id_'.$i];				
				$qtyrecord=$data['qty_unit_'.$i];//qty on 1 record		
				$data_history[$i] = array(
						'pro_id'	 	=> 	$data['item_id_'.$i],
						'type'		 	=> 	1,
						'order'		 	=> 	$purchase_id,
						'customer_id'	=> 	$data['vendor'],
						'date'		 	=> 	new Zend_Date(),
						'status'	 	=> 	$data['status'],
						'qty_unit'	=> 	$data['qty_unit_'.$i],
						'qty_per_unit'	=> 	$data['qty_per_unit_'.$i],
						'qty' 	=> 	$data['qty'.$i],
						'price'  	=> 	$data['price'.$i],
						"discount"           => 	$data['discount'],
						'total'  	=> 	$data['total'.$i],
				);
				$order_history = $db_global->addRecord($data_history[$i],"tb_purchase_order_history");
				unset($data_history[$i]);
				$data_item[$i]= array(
						'order_id'	  => 	$purchase_id,
						'pro_id'	  => 	$data['item_id_'.$i],
						'qty_unit'	=> 	$data['qty_unit_'.$i],
						'qty_per_unit'	=> 	$data['qty_per_unit_'.$i],
						'qty' 	=> 	$data['qty'.$i],
						'price'  	=> 	$data['price'.$i],
						'total_befor' => 	$data['total'.$i],
						"discount"           => 	$data['discount'],
						'sub_total'	  => $data['total'.$i],
				);
				$recieved_order = $db->insert("tb_purchase_order_item", $data_item[$i]);
				$data_item_history[$i]= array(
						'order_id'	  => 	$purchase_id,
						'pro_id'	  => 	$data['item_id_'.$i],
						'qty_unit'	=> 	$data['qty_unit_'.$i],
						'qty_per_unit'	=> 	$data['qty_per_unit_'.$i],
						'qty' 	=> 	$data['qty'.$i],
						'price'  	=> 	$data['price'.$i],
						'total_befor' => 	$data['total'.$i],
						'sub_total'	  => $data['total'.$i],
				);
				$recieved_order_history = $db->insert("tb_purchase_order_item_history", $data_item[$i]);
				$rows=$db_global->InventoryExist($itemId);
				//print_r($rows);exit();
				if($rows)
				{
					//$qty_onhand   = $rowitem["QuantityOnHand"]+$data['qty'.$i];
					echo $rows["qty_perunit"];
					$itemOnOrder   = array(
							'qty_onorder'    	=>  $rows["qty_onorder"]+($data['qty_unit_'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_onhand' 		=>	$rows["qty_onhand"]+($data['qty_unit_'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_available' 	=>	$rows["qty_available"]+($data['qty_unit_'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'pro_id'		    => 	$itemId
					);
					//update total stock
					$db_global->updateRecord($itemOnOrder,$itemId,"pro_id","tb_product");
				}
				else
				{
					$dataInventory= array(
							'pro_id'            => $itemId,
							'qty_onorder'    	=>  $rows["qty_onorder"]+($data['qty_unit_'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_onhand' =>$rows["qty_onhand"]+($data['qty_unit_'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_available' =>$rows["qty_available"]+($data['qty_unit_'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'last_mod_date'      => new Zend_date()
					);
					$db->insert("tb_product", $dataInventory);
					//add move hostory
				}
			}
			
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			$e->getMessage();exit();
		}
	} 
	public function updatCat($data,$id){
		try{
			$db_global = new Application_Model_DbTable_DbGlobal();
			$db = $this->getAdapter();	
			$db->beginTransaction();
			$older = $data['id_code'];
			$sql ="SELECT p.`order` FROM tb_purchase_order AS p WHERE p.`order`= '$older'";
			$session_user=new Zend_Session_Namespace('auth');
			$userName=$session_user->user_name;
			$GetUserId= $session_user->user_id;			
			$info_purchase_order=array(
					"vendor_id"      => 	$data['vendor'],
					//"order"      => 	$order,
					"date_in"     	 => 	$data['date'],
					"status"         => 	$data['status'],
					"user_mod"       => 	$GetUserId,
					"timestamp"      => 	new Zend_Date(),
					"paid"           => 	$data['paid'],
					"discount"       => $data['discount'],
					"all_total"      => 	$data['totalAmoun'],
					"balance"        => 	$data['remain']
			);
			 $this->_name="tb_purchase_order";
			 $where = $db->quoteInto("order_id", $id);
			$this->update($info_purchase_order, $where);		
			
			$row_old_order = $this->getSalesOderID($id);
			
			if($row_old_order){
				foreach ($row_old_order as $rs){
					
					$pro_exist = $db_global->InventoryExist($rs["pro_id"]);
					
					//foreach ($pro_exist as $rs_pro){
						//print_r($pro_exist["qty_onorder"]);exit();
					$itemOnOrder   = array(
							'qty_onorder'    	=>  $pro_exist["qty_onorder"]-($rs['qty_unit']*$pro_exist["qty_perunit"]+$rs['qty']),
							'qty_onhand' 		=>	$pro_exist["qty_onhand"]-($rs['qty_unit']*$pro_exist["qty_perunit"]+$rs['qty']),
							'qty_available' 	=>	$pro_exist["qty_available"]-($rs['qty_unit']*$pro_exist["qty_perunit"]+$rs['qty']),
							//'pro_id'		    => 	$itemId
					);
					//update total stock
					$db_global->updateRecord($itemOnOrder,$rs["pro_id"],"pro_id","tb_product");
					//}
				}
			}
			
				$sql = "DELETE FROM tb_purchase_order_item WHERE order_id=$id";
				$db->query($sql);
			
			
			
			$ids=explode(',',$data['identity']);
			foreach ($ids as $i)
			{
				$itemId=$data['item_id'.$i];				
				$qtyrecord=$data['unit_qty'.$i];//qty on 1 record		
				$data_history[$i] = array(
						'pro_id'	 	=> 	$data['item_id'.$i],
						'type'		 	=> 	1,
						'order'		 	=> 	$id,
						'customer_id'	=> 	$data['vendor'],
						'date'		 	=> 	new Zend_Date(),
						'status'	 	=> 	$data['status'],
						'qty_unit'	=> 	$data['unit_qty'.$i],
						'qty_per_unit'	=> 	$data['unit_price'.$i],
						'qty' 	=> 	$data['qty'.$i],
						'price'  	=> 	$data['price_per_qty'.$i],
						"discount"           => 	$data['discount'],
						'total'  	=> 	$data['total_price'.$i],
				);
				$order_history = $db_global->addRecord($data_history[$i],"tb_purchase_order_history");
				unset($data_history[$i]);
				$data_item[$i]= array(
						'order_id'	  => 	$id,
						'pro_id'	  => 	$data['item_id'.$i],
						'qty_unit'	=> 	$data['unit_qty'.$i],
						'qty_per_unit'	=> 	$data['unit_price'.$i],
						'qty' 	=> 	$data['qty'.$i],
						'price'  	=> 	$data['price_per_qty'.$i],
						'total_befor' => 	$data['total_price'.$i],
						"discount"           => 	$data['discount'],
						'sub_total'	  => $data['total_price'.$i],
				);
				$recieved_order = $db->insert("tb_purchase_order_item", $data_item[$i]);
				$data_item_history[$i]= array(
						'order_id'	  => 	$id,
						'pro_id'	  => 	$data['item_id'.$i],
						'qty_unit'	=> 	$data['unit_qty'.$i],
						'qty_per_unit'	=> 	$data['unit_price'.$i],
						'qty' 	=> 	$data['qty'.$i],
						'price'  	=> 	$data['price_per_qty'.$i],
						'total_befor' => 	$data['total_price'.$i],
						'sub_total'	  => $data['total_price'.$i],
				);
				$recieved_order_history = $db->insert("tb_purchase_order_item_history", $data_item[$i]);
				$rows=$db_global->InventoryExist($itemId);
				//print_r($rows);exit();
				if($rows)
				{
					//$qty_onhand   = $rowitem["QuantityOnHand"]+$data['qty'.$i];
					echo $rows["qty_perunit"];
					$itemOnOrder   = array(
							'qty_onorder'    	=>  $rows["qty_onorder"]+($data['unit_qty'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_onhand' 		=>	$rows["qty_onhand"]+($data['unit_qty'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_available' 	=>	$rows["qty_available"]+($data['unit_qty'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'pro_id'		    => 	$itemId
					);
					//update total stock
					$db_global->updateRecord($itemOnOrder,$itemId,"pro_id","tb_product");
				}
				else
				{
					$dataInventory= array(
							'pro_id'            => $itemId,
							'qty_onorder'    	=>  $rows["qty_onorder"]+($data['unit_qty'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_onhand' =>$rows["qty_onhand"]+($data['unit_qty'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'qty_available' =>$rows["qty_available"]+($data['unit_qty'.$i]*$rows["qty_perunit"])+$data['qty'.$i],
							'last_mod_date'      => new Zend_date()
					);
					$db->insert("tb_product", $dataInventory);
					//add move hostory
				}
			}
// 			exit();
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			$e->getMessage();exit();
		}
    }
    public function updateStatus($arr)
    {
    	$array_data = array(
    			"is_active"	=>	1
    	);
    	$where = $this->getAdapter()->quoteInto('order_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function updateUnStatus($arr)
    {
    	$array_data = array(
    			"is_active"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('order_id=?', $arr);
    	$this->update($array_data, $where);
    		
    }
    public function deleteCat($id) {
//     	$db = $this->getAdapter();
//     	$sql = "Delete From $this->_name WHERE order_id=".$id;
//     	$row = $db->query($sql);
//     	return $row;
    	$array_data = array(
    			"is_active"	=>	0
    	);
    	$where = $this->getAdapter()->quoteInto('order_id=?', $id);
    	$this->update($array_data, $where);
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
    	$sql = "SELECT COUNT(order_id) AS amount FROM tb_purchase_order";
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
    public function selectProductOption(){//not add item to this select box
    	$db = $this->getAdapter();
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	$sql_cate = 'SELECT * FROM tb_pro_category';
    
    	$row_cate = $db->fetchAll($sql_cate);
    	$option='<option value="0"​ > - - - - - ជ្រើរើសផលិផល  - - - - -  </optgroup>';
    	
    	foreach($row_cate as $cate){
    		$option .= '<optgroup  label="'.htmlspecialchars($cate['cat_name_en']." - ".$cate['cat_name_km'] , ENT_QUOTES).'">';
    		if($result["level"]==1 OR $result["level"]==2){
    			$sql = "SELECT pro_id,`name_kh`,`name_en`,item_code FROM tb_product WHERE `status` = 1 AND cate_id = ".$cate['cat_id']."
				AND `name_en`!='' ORDER BY last_mod_date DESC";
    		}else{
    			$sql = "SELECT p.pro_id,p.name_kh,p.name_en,p.item_code FROM tb_product AS p
						INNER JOIN tb_prolocation  AS pl ON p.pro_id = pl.pro_id
				 WHERE p.status = 1 AND p.cate_id = ".$cate['cat_id']." ORDER BY p.last_mod_date DESC ";
    		}
    			
    			
    		$rows = $db->fetchAll($sql);
    		if($rows){
    			foreach($rows as $value){
    				$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['name_kh']." - ".$value['name_en'], ENT_QUOTES).'">'.
    						htmlspecialchars($value['name_kh']." - ".$value['name_en'], ENT_QUOTES)." ".htmlspecialchars($value['item_code'], ENT_QUOTES)
    						.'</option>';
    			}
    		}
    		$option.="</optgroup>";
    	}
    	return $option;
    }
    public function getProductOption(){
    	$db = $this->getAdapter();
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	$sql_cate = 'SELECT * FROM tb_pro_category';
    
    	$row_cate = $db->fetchAll($sql_cate);
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	$option='<option value="0"​ > - - - - - ជ្រើរើសផលិផល  - - - - -  </optgroup>';
    	foreach($row_cate as $cate){
    		$option .= '<optgroup  label="'.htmlspecialchars($cate['cat_name_en']." - ".$cate['cat_name_km'], ENT_QUOTES).'">';
    		if($result["level"]==1 OR $result["level"]==2){
    			$sql = "SELECT pro_id,`name_kh`,`name_en`,item_code FROM tb_product WHERE `status` = 1 AND cate_id = ".$cate['cat_id']."
				AND `name_en`!='' ORDER BY last_mod_date DESC";
    		}else{
    			$sql = "SELECT p.pro_id,p.name_kh,p.name_en,p.item_code FROM tb_product AS p
						INNER JOIN tb_prolocation  AS pl ON p.pro_id = pl.pro_id
				 WHERE p.status = 1 AND p.cate_id = ".$cate['cat_id']." ORDER BY p.last_mod_date DESC ";
    		}
    		$rows = $db->fetchAll($sql);
    		if($rows){
    			foreach($rows as $value){
    				$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['name_kh']." - ".$value['name_en'], ENT_QUOTES).'">'.
    						htmlspecialchars($value['name_kh']." - ".$value['name_en'], ENT_QUOTES)." ".htmlspecialchars($value['item_code'], ENT_QUOTES)
    						.'</option>';
    			}
    		}
    		$option.="</optgroup>";
    	}
    	if($result["level"]==1 OR $result["level"]==2){
    		$option .= '<option value="-1" label="Add New Product">Add New Product</option>';
    	}
    	return $option;
    }
	public function getSalesOderID($id){
		$db = $this->getAdapter();
		$sql = "SELECT si.order_id,CONCAT(p.name_kh,'-',p.name_en) AS name_en ,
				p.pro_id,si.qty_unit,si.qty_per_unit,si.qty,si.price,si.total_befor,si.discount,si.sub_total,si.is_free
				FROM tb_purchase_order_item AS si,tb_product AS p  
				WHERE p.pro_id = si.pro_id  AND  si.order_id=".$id;
		$row = $db->fetchAll($sql);
		return $row;
	}	
}

