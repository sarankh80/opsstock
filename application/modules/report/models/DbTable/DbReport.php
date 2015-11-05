<?php

class report_Model_DbTable_DbReport extends Zend_Db_Table_Abstract
{
	protected $_name ="tb_product";
	
	public function getProduct($search){
		$db = $this->getAdapter();
		$sql="SELECT 
			  p.`name_en`,
			  p.`name_kh`,
			  p.`item_code`,
			  p.`qty_onhand`,
			  p.`qty_onorder`,
			  p.`qty_onsold`,
			  p.`unit_sale_price`,
			  p.`price_per_qty`,
			  (SELECT c.`icon` FROM `tb_currency` AS c WHERE c.`cu_id`=p.cu_id) as cu_icon,
			  (SELECT CONCAT(c.`cat_name_en`,'<br />',c.`cat_name_km`) FROM `tb_pro_category` AS c WHERE p.cate_id=c.cat_id) AS cate_name,
			  (SELECT CONCAT(b.`name_kh`,'<br />',b.`name_en`) FROM `tb_brand` AS b WHERE p.`brand_id` = b.`brand_id`) AS brand,
			  p.`item_size` 
			FROM
			  `tb_product` AS p WHERE 1";
		$where ="";
		if($search["txt_search"]!=""){
			$s_where = array();
    		$s_search = $search['txt_search'];
    		$s_where[] = "p.name_en LIKE '%{$s_search}%'";
    		$s_where[] = "p.name_kh LIKE '%{$s_search}%'";
    		$s_where[] = "p.item_code LIKE '%{$s_search}%'";
    		
    		$s_where[] = "c.`cat_name_en` LIKE '%{$s_search}%'";
    		$s_where[] = "c.`cat_name_kh` LIKE '%{$s_search}%'";
    		
    		$s_where[] = "b.`name_en` LIKE '%{$s_search}%'";
    		$s_where[] = "b.`name_kh` LIKE '%{$s_search}%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search["add_item"]>0){
			$where.= " AND p.pro_id=".$search["add_item"];
		}
		if($search["cat_id"]>0){
			$where.= " AND p.cate_id=".$search["cat_id"];
		}
		if($search["brand"]>0){
			$where.= " AND p.brand_id=".$search["brand"];
		}
		if($search["status"]>0){
			$where.= " AND p.status=".$search["status"];
		}
		return $db->fetchAll($sql.$where);
	}
	
	public function getSale($search){
		$db = $this->getAdapter();
		$start_date = $search['start_date'];
		$end_date = $search['end_date'];
		$sql="SELECT s.`order_no`,s.`so_id`,s.`net_total`,s.`paid`,s.`balance`,c.`name_kh`,c.`name_en`,s.date,s.currency_id,(select e.rate from  tb_exchange as e where e.from_cu = 1 and to_cu = s.currency_id) as rate FROM `tb_sale_order` AS s,`tb_customer` AS c WHERE s.`customer_id`=c.`customer_id` ";
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
		if(!empty($search['invoice_no']>0)){
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
 		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	
	public function getPurchase($search){
		$db = $this->getAdapter();
		$start_date = $search['start_date'];
		$end_date = $search['end_date'];
		$sql="SELECT p.`order`,p.`order_id`,p.`all_total`,p.`paid`,p.`balance`,p.`discount`,p.`date_in`,CONCAT(v.`contact_name`,'-',v.`phone`) AS suplier FROM `tb_purchase_order` AS p,`tb_vendor` AS v WHERE p.`vendor_id`=v.`vendor_id`";
		$where ="";
		if($search["txt_search"]!=""){
			$s_where = array();
			$s_search = $search['txt_search'];
			$s_where[] = "v.contact_name LIKE '%{$s_search}%'";
			$s_where[] = "v.v_name LIKE '%{$s_search}%'";
			$s_where[] = "v.phone LIKE '%{$s_search}%'";
	
			$s_where[] = "v.email LIKE '%{$s_search}%'";
			$s_where[] = "p.order LIKE '%{$s_search}%'";
	
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if(!empty($search['start_date']) or !empty($search['end_date'])){
			$where.=" AND p.`date_in` BETWEEN '$start_date' AND '$end_date'";
		}
		if($search["supplier"]>0){
			$where.= " AND v.vendor_id=".$search["supplier"];
		}
		if($search["purchase_no"]>0){
			$where.= " AND p.order_id=".$search["purchase_no"];
		}
		if($search["status"]>0){
			$where.= " AND p.is_active=".$search["status"];
		}
		if($search["pay_status"]>0){
			$where.= " AND p.status=".$search["pay_status"];
		}
		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	
	public function getSaleOrderView($id){
		$db = $this->getAdapter();
		$sql= "SELECT s.`so_id`,s.`order_no`,s.`all_total`,s.`paid`,s.`balance`,s.`date`,CONCAT(c.`name_kh`,'-',c.`name_en`) AS customer,CONCAT(cu.`cu_name_km`,'-',cu.`cu_name_en`) AS currency,cu.`icon` FROM `tb_sale_order` AS s,`tb_customer` AS c,`tb_currency` AS cu WHERE s.`currency_id`=cu.`cu_id` AND s.`customer_id`=c.`customer_id` AND s.`so_id`=$id";
		return $db->fetchRow($sql);
	}
	public function getPurchaseView($id){
		$db = $this->getAdapter();
		$sql= "SELECT p.`date_in`,p.`order_id`,p.`order`,p.`all_total`,p.`paid`,p.`balance`,CONCAT(v.`v_name`,'-',v.`phone`) AS supplier FROM `tb_purchase_order` AS p ,`tb_vendor` AS v WHERE p.`vendor_id`=v.`vendor_id` AND p.`order_id`=$id";
		return $db->fetchRow($sql);
	}
}

