<?php 
class report_Form_FrmReport extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmSearch($data = null){
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$db = new Application_Model_DbTable_DbGlobal();
		$search = new Zend_Form_Element_Text("txt_search");
		$search->setValue($request->getParam("txt_search"));
		
		$start_date = new Zend_Form_Element_Text("start_date");
		$c_date = date('Y-m-d');
		$start_date->setAttribs(array('id'=>'datepicker','style'=>'float:left;width:100%','class'=>'form-control'));
		$_date_s = $request->getParam("start_date");
		if(empty($_date_s)){
			$_date_s = date('Y-m-d');
		}
		$start_date->setValue($_date_s);
		
		$end_date = new Zend_Form_Element_Text("end_date");
		$end_date->setAttribs(array('class'=>'datepicker','style'=>'width:100% !important'));
		$_date_t = $request->getParam("end_date");
		if(empty($_date_t)){
			$_date_t = date('Y-m-d');
		}
		$end_date->setValue($_date_t);
		
		$pay_status = new Zend_Form_Element_Select("pay_status");
		$options = array('-1'=>$this->tr->translate("SELECT"),'1'=>$this->tr->translate("FULL_PAID"),'2'=>$this->tr->translate("BALANCE"));
		$pay_status->setMultiOptions($options);
		$pay_status->setValue($request->getParam("pay_status"));
		
		$option = array('-1'=>$this->tr->translate("SELECT"),'1'=>$this->tr->translate("ACTIVE"),'0'=>$this->tr->translate("DEACTIVE"));
		$status = new Zend_Form_Element_Select("status");
		$status->setMultiOptions($option);
		$status->setValue($request->getParam("status"));
		// Product Blog
		$cat_id = new Zend_Form_Element_Select("cat_id");
		$cat_id->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$category = $db->getAllProCategories();
			$option_category = array(0=>$this->tr->translate("CHOOSE_CATEGORY"));
			foreach ($category as $row_cat){
				$option_category[$row_cat["cat_id"]] = $row_cat["cat_name_km"]." - ".$row_cat["cat_name_en"];
		}
		$cat_id->setMultiOptions($option_category); 
		$cat_id->setValue($request->getParam("cat_id"));
		
		$brand = new Zend_Form_Element_Select("brand");
		$brand->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$row_brand = $db->getAllBrand();
		$option_brand= array(0=>$this->tr->translate("CHOOSE_BRAND"));
		foreach ($row_brand as $rs){
			$option_brand[$rs["brand_id"]] = $rs["name_kh"]." - ".$rs["name_en"];
		}
		$brand->setMultiOptions($option_brand);
		$brand->setValue($request->getParam("brand"));
		
		// Sale Blog
		$row_cu = $db->getCustomer();
		$option_cu = array(0=>$this->tr->translate("CHOOSE_CUSTOMER"));
		foreach ($row_cu as $rs_cu){
			$option_cu[$rs_cu["customer_id"]] = $rs_cu["name_kh"]."-".$rs_cu["name_en"];
		}
		$customer = new Zend_Form_Element_Select("customer");
		$customer->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$customer->setMultiOptions($option_cu);
		$customer->setValue($request->getParam("customer"));
		
		$row_order = $db->getOrderNo();
		$option_order = array(-1=>$this->tr->translate("CHOOSE_ORDER_NO"));
		foreach ($row_order as $rs_order){
			$option_order[$rs_order["so_id"]] = $rs_order["order_no"];
		}
		$invoice_no = new Zend_Form_Element_Select("invoice_no");
		$invoice_no->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$invoice_no->setMultiOptions($option_order);
		$invoice_no->setValue($request->getParam("invoice_no"));
		// Purchase Blog
		
		$supplier = new Zend_Form_Element_Select("supplier");
		$dbv = new phurchase_Model_DbTable_DbVendor();
		$vendors = $dbv->getAllCate();
		$option_vendor = array("" =>'​ - - - ជ្រើរើសអ្នកផ្គត់ផ្គង  - - - ',-1=>" + បន្ថែមអ្នកផ្គត់ផ្គង");
		foreach ($vendors as $row_vendors){
			$option_vendor[$row_vendors["vendor_id"]] = $row_vendors["v_name"];
		}
		
			$supplier = new Zend_Form_Element_Select("supplier");
			$supplier->setMultiOptions($option_vendor);
		$supplier->setValue($request->getParam("supplier"));
		
		$row_pu = $db->getPurchaseNo();
		$options = array("-1" =>'​ - - - ជ្រើរើសអ្នកលេខវិក៍យបត្រ  - - - ');
		foreach ($row_pu as $rs){
			$options[$rs["order_id"]] = $rs["order"];
		}
		$purchase_no = new Zend_Form_Element_Select("purchase_no");
		$purchase_no->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$purchase_no->setMultiOptions($options);
		$purchase_no->setValue($request->getParam("purchase_no"));
		if($data!=null){
		}
		
		return $this->addElements(array($search,$start_date,$end_date,$pay_status,$status,$cat_id,$brand,$customer,$invoice_no,$supplier,$purchase_no));
		
		
	}
}
?>
