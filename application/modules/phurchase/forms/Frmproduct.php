<?php 
class product_Form_Frmproduct extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProCate($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
    	$id_code = new Zend_Form_Element_Text('id_code');
    		$id_code->setAttribs(array('class'=>'form-control',"readonly"=>""));
    		$code = product_Model_DbTable_DbProduct::getCallteralCode();
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
		
		$currency= new Zend_Form_Element_Select("currency");
		$currency->setAttribs(array('class'=>'form-control validate[required]'));
		$dbp = new product_Model_DbTable_DbProduct();
		$currencys = $dbp->getAllPro();
		$option_currency = array(0=>'Choose Currency');
		foreach ($currencys as $row_currency){
			$option_currency[$row_currency["cu_id"]] = $row_currency["cu_name_km"]." - ".$row_currency["cu_name_en"];
		}
		$currency->setMultiOptions($option_currency);
		
		$price = new Zend_Form_Element_Text("price");
		$price->setAttribs(array('class'=>'validate[required]',));
		$description = new Zend_Form_Element_Textarea("description");
		
		$cat_name_km = new Zend_Form_Element_Text("name_km");
		$cat_name_km->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In Khmer',"OnChange"=>"GetCatName(2)"));
		
		$icon = new Zend_Form_Element_File("icon");
		
		$this->addElements(array($description,$price,$icon,$status,$cat_name_en,$cat_name_km,$cat_id,$id_code,$currency));
		if($frm !=""){
			$cat_id->setValue($frm["cat_id"]);
			$cat_name_en->setValue($frm["pro_name_en"]);
			$cat_name_km->setValue($frm["pro_name_km"]);
			$status->setValue($frm["status"]);
			$currency->setValue($frm["cu_id"]);
			$price->setValue($frm["price_out"]);
			$description->setValue($frm["description"]);
		}
		return $this;
	}
}
?>
