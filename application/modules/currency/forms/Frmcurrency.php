<?php 
class currency_Form_Frmcurrency extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProCate($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$cat_name_en = new Zend_Form_Element_Text("name_en");
		$cat_name_en->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In English',"OnChange"=>"GetCatName(1)"));
		$cat_name_km = new Zend_Form_Element_Text("name_km");
		$cat_name_km->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In Khmer',"OnChange"=>"GetCatName(2)"));
	
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
		$icon = new Zend_Form_Element_Text("icon");
		$rate = new Zend_Form_Element_Text("rate");
		$rate->setAttribs(array('class'=>'form-control','placeholder'=>"00.00  ៛"));
		$this->addElements(array($icon,$status,$cat_name_en,$cat_name_km,$rate));
		if($frm !=""){
			$icon->setValue($frm["icon"]);
			$cat_name_en->setValue($frm["cu_name_en"]);
			$cat_name_km->setValue($frm["cu_name_km"]);
			$status->setValue($frm["status"]);
			$rate->setValue($frm["rate"]);
		}
		return $this;
	}
}
?>
