<?php 
class phurchase_Form_FrmPhurchaseOrder extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProCate($frm = null){
		$vendor= new Zend_Form_Element_Select("vendor");
		$vendor->setAttribs(array('class'=>'validate[required]','onclick'=>'ValueVendor()'));
		$dbv = new phurchase_Model_DbTable_DbVendor();
		$vendors = $dbv->getAllCate();
		$option_vendor = array("" =>'​ - - - ជ្រើរើសអ្នកផ្គត់ផ្គង  - - - ',-1=>" + បន្ថែមអ្នកផ្គត់ផ្គង");
		foreach ($vendors as $row_vendors){
			$option_vendor[$row_vendors["vendor_id"]] = $row_vendors["v_name"];
		}
		$vendor->setMultiOptions($option_vendor);
		$c_date = date('Y-m-d');
		$date =  new Zend_Form_Element_Text('date');
		$date->setAttribs(array('id'=>'datepicker','style'=>'float:left;width: 120px;','class'=>'form-control validate[required]','onchange'=>'SubmitValue()'));
		$date->setValue($c_date);
		$status = new Zend_Form_Element_Select('status');
		$_arr_status = array(1=>$this->tr->translate("បង់លុយ"),2=>$this->tr->translate("ជំពាក់"));
		$status->setMultiOptions($_arr_status);
		$status->setAttribs(array('class'=>'form-control validate[required]',));
		
		$amount = new Zend_Form_Element_Text("totalAmoun");
		$amount->setAttribs(array('class'=>'form-control',"readonly"=>""));
		
		$paid = new Zend_Form_Element_Text("paid");
		$paid->setAttribs(array('class'=>'form-control','onkeyup'=>"doRemain()"));
		
		$discount = new Zend_Form_Element_Text("discount");
		$discount->setAttribs(array('class'=>'form-control','onkeyup'=>"doDiscount()"));
		
		$total = new Zend_Form_Element_Text("remain");
		$total->setAttribs(array('class'=>'form-control',"readonly"=>""));
		
		$totalamount = new Zend_Form_Element_Text("totalamount");
		$totalamount->setAttribs(array('class'=>'form-control',"readonly"=>""));
		
		$db = new Application_Model_DbTable_DbGlobal();
		
		
    	$id_code = new Zend_Form_Element_Text('id_code');
    		$id_code->setAttribs(array('class'=>'form-control',"readonly"=>""));
    		$code = phurchase_Model_DbTable_DbOrder::getCallteralCode();
    		$id_code->setValue($code);
		$cat_id = new Zend_Form_Element_Select("cat_id");
		$cat_id->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$category = $db->getAllProCategories();
			$option_category = array(0=>'Choose Category');
			foreach ($category as $row_cat){
				$option_category[$row_cat["cat_id"]] = $row_cat["cat_name_km"]." - ".$row_cat["cat_name_en"];
		}
		$cat_id->setMultiOptions($option_category);  
		
		$cat_name_en = new Zend_Form_Element_Text("name_en");
		$cat_name_en->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In English',"OnChange"=>"GetCatName(1)"));
		
		
		$price = new Zend_Form_Element_Text("price");
		$price->setAttribs(array('class'=>'validate[required]',));
		$description = new Zend_Form_Element_Textarea("description");
		
		$cat_name_km = new Zend_Form_Element_Text("name_km");
		$cat_name_km->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In Khmer',"OnChange"=>"GetCatName(2)"));
		
		$icon = new Zend_Form_Element_File("icon");
		
		$this->addElements(array($amount,$paid,$vendor,$discount,$totalamount,$total,$date,$description,$price,$icon,$status,$cat_name_en,$cat_name_km,$cat_id,$id_code));
		if($frm !=""){
			$vendor->setValue($frm["vendor_id"]);
			$id_code->setValue($frm["order"]);
			$date->setValue($frm["date_in"]);
			$status->setValue($frm["status"]);
			$amount->setValue($frm["all_total"]);
			$paid->setValue($frm["paid"]);
			$discount->setValue($frm["discount"]);
			$total->setValue($frm["balance"]);
		}
		return $this;
	}
}
?>
