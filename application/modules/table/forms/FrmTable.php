<?php 
class table_Form_FrmTable extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProCate($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$id_code = new Zend_Form_Element_Text('id_code');
		$id_code->setAttribs(array('class'=>'form-control',"readonly"=>""));
		$code = table_Model_DbTable_DbTable::getCallteralCode();
		$id_code->setValue($code);
		$cat_name_en = new Zend_Form_Element_Text("name_en");
		$cat_name_en->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In English',"OnChange"=>"GetCatName(1)"));
		
		$cat_name_km = new Zend_Form_Element_Text("name_km");
		$cat_name_km->setAttribs(array('class'=>'validate[required]','placeholder'=>'Name In Khmer',"OnChange"=>"GetCatName(2)"));
	
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
		
		$icon = new Zend_Form_Element_File("icon");
		$description = new Zend_Form_Element_Textarea("description");
		$this->addElements(array($icon,$status,$cat_name_en,$cat_name_km,$id_code,$description));
		if($frm !=""){
			$id_code->setValue($frm["code"]);
			$cat_name_en->setValue($frm["name_en"]);
			$cat_name_km->setValue($frm["name_km"]);
			$status->setValue($frm["status"]);
			$description->setValue($frm["description"]);
		}
		return $this;
	}
}
?>
