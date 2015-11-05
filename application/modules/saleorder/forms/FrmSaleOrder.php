<?php 
class saleorder_Form_FrmSaleOrder extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmSaleOrder($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$db_table = new saleorder_Model_DbTable_DbSaleOrder();
		$rs_table = $db_table->getAllTable();
		$option_table = array('0'=>$this->tr->translate("CHOOSE_TABLE"));
		foreach ($rs_table as $row){
			$option_table[$row["tab_id"]] = $row["name_en"]."-".$row["name_km"];
		}
		$table = new Zend_Form_Element_Select("table");
		$table->setAttribs(array('class'=>'select validate[required]','style'=>'width:100%'));
		$table->setMultiOptions($option_table);
		
		$c_date = date('Y-m-d');
		$date =  new Zend_Form_Element_Text('date');
		$date->setAttribs(array('id'=>'datepicker','style'=>'float:left;width:100%','class'=>'form-control'));
		$date->setValue($c_date);
		
		$row_customer = $db->getCustomer();
		foreach ($row_customer as $rs_customer){
			$option_cu[$rs_customer["customer_id"]] = $rs_customer["name_kh"]."-".$rs_customer["name_en"];
		}
		$customer = new Zend_Form_Element_Select("customer");
		$customer->setAttribs(array('class'=>'select validate[required]','style'=>'width:100%'));
		$customer->setMultiOptions($option_cu);
		
		$saleorder_num = $db_table->getSaleOrderNo();
		$saleorder_no = new Zend_Form_Element_Text("saleorder_no");
		$saleorder_no->setAttribs(array('class'=>'validate[required]','readOnly'=>'readOnly','style'=>'color:red'));
		$saleorder_no->setValue($saleorder_num);
		
		$row_currency_setting = $db->getSettingByCode(1);
		$currency = new Zend_Form_Element_Select("currency");
		$currency->setAttribs(array('class'=>'validate[required]','style'=>'width:100%','onChange'=>'getRate();'));
		$row_currency = $db->getAllCurrency();
		foreach ($row_currency as $rs_currency){
			$option_currency[$rs_currency["cu_id"]] = $rs_currency["cu_name_km"]."-".$rs_currency["cu_name_en"];
		}
		$currency->setValue($row_currency_setting);
		$currency->setMultiOptions($option_currency);
		
		$grand_total = new Zend_Form_Element_Text("gran_total");
		$grand_total->setAttribs(array('class'=>'validate[required]'));
		
		$grand_pay = new Zend_Form_Element_Text("gran_pay");
		$grand_pay->setAttribs(array('class'=>'validate[required]','onChange'=>"doBalance();"));
		
		$balance = new Zend_Form_Element_Text("balance");
		$balance->setAttribs(array('class'=>'validate[required]'));
		
		
		$this->addElements(array($balance,$grand_pay,$grand_total,$currency,$customer,$table,$date,$saleorder_no));
		if($frm !=""){
			$saleorder_no->setValue($frm["order_no"]);
			$customer->setValue($frm["customer_id"]);
			$currency->setValue($frm["currency_id"]);
			$date->setValue($frm["date"]);
		}
		return $this;
	}
	
	public function FrmSaleOrderUpdate($frm = null){
	
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$rs_table = $db->getAllTable();
		
		$option_table = array('0'=>$this->tr->translate("CHOOSE_TABLE"));
		foreach ($rs_table as $row){
			$option_table[$row["tab_id"]] = $row["code"].":".$row["name_en"]."-".$row["name_km"];
		}
		$table = new Zend_Form_Element_Select("tables");
		$table->setAttribs(array('class'=>'select validate[required]','style'=>'width:100%'));
		$table->setMultiOptions($option_table);
		
		$rs_product = $db->getAllProduct();
		$option_product = array(0=>$this->tr->translate('CHOOSE_PRODUCT'));
		foreach ($rs_product as $row){
			$option_product[$row["pro_id"]] = $row["item_code"].'-'.$row["name_kh"].'-'.$row["name_en"];
		}
		$product = new Zend_Form_Element_Select("product");
		$product->setAttribs(array('class'=>'select form-control','style'=>'width:100%'));
		$product->setMultiOptions($option_product);
	
		$c_date = date('Y-m-d');
		$date =  new Zend_Form_Element_Text('dates');
		$date->setAttribs(array('id'=>'dates','style'=>'float:left;width:100%','class'=>'form-control validate[required]'));
		//$date->setValue($c_date);
	
		$saleorder_num = $db->getSaleOrderNo();
		$saleorder_no = new Zend_Form_Element_Text("saleorder_nos");
		$saleorder_no->setAttribs(array('class'=>'validate[required]','readOnly'=>'readOnly','style'=>'color:red'));
		$saleorder_no->setValue($saleorder_num);
	
		$this->addElements(array($product,$table,$date,$saleorder_no));
		if($frm !=""){
			$saleorder_no->setValue($frm["saleorder_no"]);
			$table->setValue($frm["tab_id"]);
			$date->setValue($frm["date"]);
		}
		return $this;
	}
}
?>
