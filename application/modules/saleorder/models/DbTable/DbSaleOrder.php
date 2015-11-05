<?php

class saleorder_Model_DbTable_DbSaleOrder extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_product";
	
	public function getAllProduct(){
		$db = $this->getAdapter();
		$sql = "SELECT p.`pro_id`,p.`item_code`,p.`name_en`,p.`name_kh`,p.`price_per_qty`,p.icon,p.`cu_id`,p.cate_id,
				(SELECT `cat_name_en` FROM `tb_pro_category` WHERE `cat_id` = p.cate_id)AS cat_name_en,
				(SELECT `cat_name_km` FROM `tb_pro_category` WHERE `cat_id` = p.cate_id)AS cat_name_km,
				c.`icon` AS cu_icon,c.`rate`,p.status FROM tb_product AS p,`tb_currency` AS c WHERE p.`cu_id`=c.`cu_id` AND p.status=1";
		//echo $sql;
		return $db->fetchAll($sql);
	}
	public function getAllTable(){
		$db = $this->getAdapter();
		$this->_name="tb_table";
		$sql = "SELECT  t.`tab_id`,t.`code`,t.`name_en`,t.`name_km` FROM $this->_name AS t WHERE status = 1";
		return $db->fetchAll($sql);
	}
	public function getAllProductName(){
		$db = $this->getAdapter();
		$sql = "SELECT pro_id,CONCAT(`item_code`,' - ', `name_en`,' - ',`name_km`)AS pro_name FROM `tb_product` WHERE status = 1";
		return $db->fetchAll($sql);
	}
	public function getAllCode(){
		$db = $this->getAdapter();
		$sql = "SELECT so_id,order_no FROM `tb_sale_order` WHERE status = 1";
		return $db->fetchAll($sql);
	}
	public function getAllSaleOrderList($search){
		$db = $this->getAdapter();
		$start_date = $search['start_date'];
		$end_date = $search['end_date'];
		$sql = "SELECT s.`so_id`,s.`order_no`,s.`net_total`,c.`name_kh`,cu.`icon`,s.`status`,s.date FROM `tb_sale_order` AS s ,`tb_customer` AS c,`tb_currency` AS cu WHERE s.`customer_id`=c.`customer_id` AND s.`currency_id`=cu.`cu_id`";
		$where ="";
		if($search["txt_search"]!=""){
			$s_where = array();
			$s_search = $search['txt_search'];
			$s_where[] = "s.`order_no` LIKE '%{$s_search}%'";
			$s_where[] = "c.`name_kh` LIKE '%{$s_search}%'";
			$s_where[] = "c.`name_en` LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if(!empty($search['start_date']) or !empty($search['end_date'])){
			$where.=" AND s.`date` BETWEEN '$start_date' AND '$end_date'";
		}
		if($search['invoice_no']>0){
			$where.=" AND s.so_id=".$search["invoice_no"];
		}
		if($search["customer"]>0){
			$where.= " AND s.customer_id=".$search["customer"];
		}
		if($search["status"]>0){
			$where.= " AND s.is_active=".$search["status"];
		}
		if($search["pay_status"]>0){
			$where.= " AND s.status=".$search["pay_status"];
		}
		$order =" ORDER by s.`order_no` DESC";
		//echo $sql.$where;
		return $db->fetchAll($sql.$where.$order);
	}
	public function getAllOrderProduct(){
		$db = $this->getAdapter();
		$sql = "SELECT s.*,t.code,t.name_en,t.name_km FROM tb_sale_order AS s,`tb_table` AS t WHERE s.tab_id = t.tab_id AND s.status = 1 ORDER BY s.order_no DESC";
		return $db->fetchAll($sql);
	}
	public function getAllOrder($search=null){
		$db = $this->getAdapter();
		$date = $search['date'];
		$date_end = $search['date_end'];
		
		$sql = "SELECT s.`so_id`,s.`order_no`, s.`date`,t.`code`,t.`name_en`,t.name_km,sd.qty,sd.unit_price,sd.amount,sd.pro_id
				,(SELECT CONCAT(`pro_code`,' - ', `pro_name_en`,' - ',`pro_name_km`) FROM `tb_product` WHERE `pro_id` = sd.pro_id) AS pro_name
				,(SELECT (SELECT c.icon FROM `tb_currency` AS c WHERE c.cu_id = p.cu_id)FROM `tb_product` AS p WHERE p.`pro_id` = sd.pro_id) AS currency
				,(SELECT (SELECT c.cu_id FROM `tb_currency` AS c WHERE c.cu_id = p.cu_id)FROM `tb_product` AS p WHERE p.`pro_id` = sd.pro_id) AS cu_id
				FROM `tb_sale_order` AS s,`tb_table` AS t,tb_sale_order_detail AS sd
				WHERE s.tab_id = t.tab_id AND s.so_id = sd.so_id ";
		$where='';
		if($search['code']>0){
			$where.=" AND s.so_id= ".$search['code'];
		}
		if(!empty($search['date']) or !empty($search['date_end'])){
			$where.=" AND s.date BETWEEN '$date' AND '$date_end'";
		}
		if($search['product']>0){
			$where.=" AND sd.pro_id= ".$search['product'];
		}
		if($search['table']>0){
			$where.=" AND s.tab_id= ".$search['table'];
		}
		$order = ' ORDER BY order_no DESC ';
		//echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);
	}
	public function getOrderById($id){
		$db = $this->getAdapter();
		$id = $id["ids"];
		$sql = "SELECT 
				  s.`so_id`,
				  s.`order_no`,
				  s.`tab_id`,
				  s.`total_dollar`,
				  s.`total_reil`,
				  s.`date`,
				  sd.`pro_id`,
				  sd.`sod_id`,
				  p.`pro_name_en`,
				  sd.`unit_price`,
				  sd.`amount`,
				  sd.`qty`,
				  p.`cu_id`,
				  (SELECT c.`icon` FROM `tb_currency` AS c WHERE p.`cu_id`=c.`cu_id`) AS cu_icon,
				  (SELECT c.`rate` FROM tb_currency AS c WHERE p.`cu_id`=c.`cu_id`) AS rate
				FROM
				  `tb_sale_order` AS s,
				  `tb_sale_order_detail` AS sd,
				  `tb_product` AS p 
				WHERE s.`so_id` = sd.`so_id` 
				  AND s.`so_id` = $id 
				  AND sd.`pro_id` = p.`pro_id` ";
		//return $sql;
		return $db->fetchAll($sql);
	}
	public function getUser(){
		
	}
	public function getSaleOrderNo(){
		$this->_name='tb_sale_order';
		$db = $this->getAdapter();
		$sql=" SELECT s.`so_id` FROM $this->_name AS s ORDER BY s.`so_id` DESC LIMIT 1 ";
		$order_no = $db->fetchOne($sql);
		$new_order_no= (int)$order_no+1;
		$order_no= strlen((int)$order_no+1);
		$pre = "";
		$pre_fix="SO-";
		for($i = $order_no;$i<5;$i++){
			$pre.='0';
		}
		return $pre_fix.$pre.$new_order_no;
	}
public function addOrder($data){
		$db = $this->getAdapter();
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$db->beginTransaction();
		$session = new Zend_Session_Namespace('auth');
		$GetUserId = $session->user_id;
		$user_type = $session->level;
		try{
			$order_no = $data["saleorder_no"];
			$sql = "SELECT s.`so_id` FROM `tb_sale_order` AS s WHERE s.`order_no`='$order_no'";
			$order = $db->fetchOne($sql);
			if($order!=""){
				$order_no = $this->getOrderNo();
			}else{
				$order_no = $data["saleorder_no"];
			}
			// Add order
			if($data["gran_pay"]>=$data["gran_total"]){
				$status =1;
			}else {
				$status =2;
			}
			
			$arr_order = array(
				'customer_id'		=>	$data["customer"],
				'location_id'		=>1,
				'order_no'			=>	$order_no,
				'user_id'			=>	$GetUserId,
				'currency_id'		=>	$data["currency"],
				'net_total'			=>	$data["gran_total"],
				'all_total'			=> $data["gran_total"],
				'paid'				=>	$data["gran_pay"],
				'balance'			=>	$data["balance"],
				'date'				=>	$data["date"],
				'status'			=>	$status
			);
			$this->_name = "tb_sale_order";
			$sale_order = $this->insert($arr_order);
			
			$arr_order_his = array(
					'customer_id'		=>	$data["customer"],
					'so_id'				=>	$sale_order,
					'type'				=>	1,
					'location_id'		=>1,
					'order_no'			=>	$order_no,
					'user_id'			=>	$GetUserId,
					'currency_id'		=>	$data["currency"],
					'sub_total'			=>	$data["gran_total"],
					'paid'				=>	$data["gran_pay"],
					'balance'			=>	$data["balance"],
					'date'				=>	$data["date"],
					'status'			=>	1
			);
			$this->_name = "tb_sale_order_history";
			$sale_order_his = $this->insert($arr_order_his);
			
			$identify = $data["identity"];
			$ids = explode(',',$identify);
			foreach ($ids as $i){
				$arr_order_detail = array(
					'so_id'			=>	$sale_order,
					'pro_id'		=>	$data["item_id".$i],
					//'unit_qty'		=>	$data["unit_qty".$i],
					'qty'			=>	$data["qty".$i],
					'total_qty'		=>	$data["total_qty".$i],
					//'unit_price'	=>	$data["unit_price".$i],
					'price_per_unit'	=>	$data["price_per_qty".$i],
					'total_price'	=>	$data["total_price".$i],
					'sub_total'		=>	$data["total_price".$i],
					'status'		=>	1
				);
				$this->_name="tb_sale_order_detail";
				$this->insert($arr_order_detail);
				
				$row_product_exist = $db_globle->getQtyProductExist($data["item_id".$i]);
				if($row_product_exist){
					$arr_product = array(
							'qty_onhand'		=>	$row_product_exist["qty_onhand"]-$data["total_qty".$i],
							'qty_available'		=>	$row_product_exist["qty_available"]-$data["total_qty".$i],
							'qty_onsold'		=>	$row_product_exist["qty_onsold"]+$data["total_qty".$i]
					);
					$this->_name ="tb_product";
					$where = $db->quoteInto("pro_id=?", $data["item_id".$i]);
					$this->update($arr_product, $where);
				}
				
				$row_prolocation_exist=$db_globle->getQtyProLocationExist($data["item_id".$i]);
				if($row_prolocation_exist){
					$arr_prolocation = array(
							'qty_onhand'		=>	$row_prolocation_exist["qty_onhand"]-$data["total_qty".$i],
							'qty_available'		=>	$row_prolocation_exist["qty_available"]-$data["total_qty".$i],
							'qty_onsold'		=>	$row_prolocation_exist["qty_onsold"]+$data["total_qty".$i]
					);
					$this->_name ="tb_prolocation";
					$where = array("pro_id=?"=>$data["item_id".$i],"location_id=?"=>1);
					$this->update($arr_prolocation, $where);
				}
				
				$arr_order_his_detail = array(
						'hs_id'			=>	$sale_order_his,
						'pro_id'		=>	$data["item_id".$i],
						//'unit_qty'		=>	$data["unit_qty".$i],
						'qty'			=>	$data["qty".$i],
						'total_qty'		=>	$data["total_qty".$i],
						//'unit_price'	=>	$data["unit_price".$i],
						'price_per_unit'	=>	$data["price_per_qty".$i],
						'total_price'	=>	$data["total_price".$i],
						'sub_total'		=>	$data["total_price".$i]
				);
				$this->_name="tb_sale_order_history_detail";
				$this->insert($arr_order_his_detail);
			}
		$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			$e->getMessage()."in".$e->getFile()."code".$e->getCode()."on line".$e->getLine();exit();
		}
		
	}
	
	public function editOrder($data,$id){
		$db = $this->getAdapter();
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$db->beginTransaction();
		$session = new Zend_Session_Namespace('auth');
		$GetUserId = $session->user_id;
		$user_type = $session->level;
		try{
			$arr_order = array(
					'customer_id'		=>	$data["customer"],
					'location_id'		=>1,
					//'order_no'			=>	$order_no,
					'user_id'			=>	$GetUserId,
					'currency_id'		=>	$data["currency"],
					'net_total'			=>	$data["gran_total"],
					'all_total'			=> $data["gran_total"],
					'paid'				=>	$data["gran_pay"],
					'balance'			=>	$data["balance"],
					'date'				=>	$data["date"],
					'status'			=>	1
			);
			$where = $db->quoteInto("so_id=?", $id);
			$this->_name = "tb_sale_order";
			$this->update($arr_order, $where);
			
			
				
			$arr_order_his = array(
					'customer_id'		=>	$data["customer"],
					'so_id'				=>	$id,
					'type'				=>	2,
					'location_id'		=>	1,
					'order_no'			=>	$data["saleorder_no"],
					'user_id'			=>	$GetUserId,
					'currency_id'		=>	$data["currency"],
					'sub_total'			=>	$data["gran_total"],
					'paid'				=>	$data["gran_pay"],
					'balance'			=>	$data["balance"],
					'date'				=>	$data["date"],
					'status'			=>	1
			);
			$this->_name = "tb_sale_order_history";
			$sale_order_his = $this->insert($arr_order_his);
			
			$row_old_product = $db_globle->getSaleOrderDetail($id);
			if($row_old_product){
				foreach ($row_old_product as $rs_old_product){
					$row_product_exist = $db_globle->getQtyProductExist($rs_old_product["pro_id"]);
					$arr_product_update = array(
							'qty_onhand'		=>	$row_product_exist["qty_onhand"]+$rs_old_product["total_qty"],
							'qty_available'		=>	$row_product_exist["qty_available"]+$rs_old_product["total_qty"],
							'qty_onsold'		=>	$row_product_exist["qty_onsold"]-$rs_old_product["total_qty"]
					);
					$this->_name ="tb_product";
					$where = $db->quoteInto("pro_id=?", $rs_old_product["pro_id"]);
					$this->update($arr_product_update, $where);
			
					$row_prolocation_exist=$db_globle->getQtyProLocationExist($rs_old_product["pro_id"]);
					if($row_prolocation_exist){
						$arr_prolocation = array(
								'qty_onhand'		=>	$row_prolocation_exist["qty_onhand"]+$rs_old_product["total_qty"],
								'qty_available'		=>	$row_prolocation_exist["qty_available"]+$rs_old_product["total_qty"],
								'qty_onsold'		=>	$row_prolocation_exist["qty_onsold"]-$rs_old_product["total_qty"]
						);
						$this->_name ="tb_prolocation";
						$where = array("pro_id=?"=>$rs_old_product["pro_id"],"location_id=?"=>1);
						$this->update($arr_prolocation, $where);
					}
				}
			}
			$arr_order_detail_update = array(
						'status'			=>	0
				);
				$this->_name="tb_sale_order_detail";
				$where = $db->quoteInto("so_id=?", $id);
				$this->update($arr_order_detail_update, $where);
				
			$identify = $data["identity"];
			$ids = explode(',',$identify);
			foreach ($ids as $i){
				$arr_order_detail = array(
						'so_id'			=>	$id,
						'pro_id'		=>	$data["item_id".$i],
						//'unit_qty'		=>	$data["unit_qty".$i],
						'qty'			=>	$data["qty".$i],
						'total_qty'		=>	$data["total_qty".$i],
						//'unit_price'	=>	$data["unit_price".$i],
						'price_per_unit'	=>	$data["price_per_qty".$i],
						'total_price'	=>	$data["total_price".$i],
						'sub_total'		=>	$data["total_price".$i],
						'status'			=>	1
				);
				$this->_name="tb_sale_order_detail";
				$this->insert($arr_order_detail);
				$arr_order_his_detail = array(
						'hs_id'			=>	$sale_order_his,
						'pro_id'		=>	$data["item_id".$i],
						//'unit_qty'		=>	$data["unit_qty".$i],
						'qty'			=>	$data["qty".$i],
						'total_qty'		=>	$data["total_qty".$i],
						//'unit_price'	=>	$data["unit_price".$i],
						'price_per_unit'	=>	$data["price_per_qty".$i],
						'total_price'	=>	$data["total_price".$i],
						'sub_total'		=>	$data["total_price".$i]
				);
				$this->_name="tb_sale_order_history_detail";
				$this->insert($arr_order_his_detail);
				
				
								$row_product_exist = $db_globle->getQtyProductExist($data["item_id".$i]);
								if($row_product_exist){
									print_r($row_product_exist);
									$arr_product = array(
											'qty_onhand'		=>	$row_product_exist["qty_onhand"]-$data["total_qty".$i],
											'qty_available'		=>	$row_product_exist["qty_available"]-$data["total_qty".$i],
											'qty_onsold'		=>	$row_product_exist["qty_onsold"]+$data["total_qty".$i]
									);
									$this->_name ="tb_product";
									$where = $db->quoteInto("pro_id=?", $data["item_id".$i]);
									$this->update($arr_product, $where);
								}
				
								$row_prolocation_exist=$db_globle->getQtyProLocationExist($data["item_id".$i]);
								if($row_prolocation_exist){
									
									$arr_prolocation = array(
											'qty_onhand'		=>	$row_prolocation_exist["qty_onhand"]-$data["total_qty".$i],
											'qty_available'		=>	$row_prolocation_exist["qty_available"]-$data["total_qty".$i],
											'qty_onsold'		=>	$row_prolocation_exist["qty_onsold"]+$data["total_qty".$i]
									);
									$this->_name ="tb_prolocation";
									$where = array("pro_id=?"=>$data["item_id".$i],"location_id=?"=>1);
									$this->update($arr_prolocation, $where);
									
								}
			}
			//exit();
			$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			$e->getMessage()."in".$e->getFile()."code".$e->getCode()."on line".$e->getLine();exit();
		}
	
	}
	public function updateOrder($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		$session = new Zend_Session_Namespace('auth');
		$GetUserId = $session->user_id;
		$user_type = $session->level;
		$id = $data["update_id"];
		//printf($id);exit();
		try{
			$arr_order = array(
					'tab_id'	=>	$data["tables"],
					//'order_no'	=>	$order_no,
					'user_id'	=>	$GetUserId,
					'total_reil'		=>	$data["totalupdateriel"],
					'total_dollar'		=>	$data["totalupdatedollar"],
					'date'		=>	$data["dates"],
					'status'	=>	1
			);
			$this->_name = "tb_sale_order";
			$where = $db->quoteInto('so_id=?', $id);
			$sale_order = $this->update($arr_order, $where);
			
			$sql="DELETE FROM tb_sale_order_detail WHERE so_id=$id";
			$db->query($sql);
			
			$identify = $data["id_record"];
			$ids = explode(',',$identify);
			foreach ($ids as $i){
				$arr_order_detail = array(
						'so_id'			=>	$id,
						'pro_id'		=>	$data["item_id_".$i],
						'qty'			=>	$data["qty".$i],
						'unit_price'	=>	$data["price_".$i],
						'amount'		=>	$data["qty".$i]*$data["price_".$i]
				);
				$this->_name="tb_sale_order_detail";
				$this->insert($arr_order_detail);
			}
			$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			$e->getMessage()."in".$e->getFile()."code".$e->getCode()."on line".$e->getLine();exit();
		}
	
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
				$sql = "SELECT p.`pro_id`,p.`item_code`,p.`name_en`,p.`name_kh`  FROM `tb_product` AS p WHERE p.`cate_id`=$cat_id AND p.`name_en`!='' AND p.status=1";
			$rows = $db->fetchAll($sql);
			if($rows){
				foreach($rows as $value){
					/*$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['item_code'], ENT_QUOTES).'">'.htmlspecialchars($value['item_code'], ENT_QUOTES)." -<br />".
							htmlspecialchars($value['name_en'], ENT_QUOTES)." -<br />".htmlspecialchars($value['name_kh'], ENT_QUOTES)
							.'</option>';*/
				
				$option .= '<option value="'.$value['pro_id'].'">'.$value['item_code']." -<br />".$value['name_en']." -<br />".$value['name_kh']
							.'</option>';
				}
				
			}
			$option.="</optgroup>";
		}
		return $option;
	}
	public function getProductPrice($data){
		$id = $data["id"];
		$db = $this->getAdapter();
		$sql="SELECT 
				  p.`unit_sale_price`,
				  p.`price_per_qty`,
				  p.`cu_id`,
				  (SELECT c.`icon` FROM tb_currency AS c WHERE p.`cu_id`=c.`cu_id`) AS cu_icon,
				  (SELECT c.`rate` FROM tb_currency AS c WHERE p.`cu_id`=c.`cu_id`) AS rate
				FROM
				  tb_product AS p 
				WHERE p.pro_id =$id";
		return $db->fetchRow($sql);
	}
}

