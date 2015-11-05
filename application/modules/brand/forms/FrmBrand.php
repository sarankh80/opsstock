<?php 
class brand_Form_FrmBrand extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmBrand($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
		$parent = new Zend_Form_Element_Select("parent_id");
		$parent->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$category = $db->getAllBrand();
		if(empty($category)){
			$option_category = array('0'=>'No Brand To Select');
		}else {
			$option_category = array('0'=>'Choose Brand');
			foreach ($category as $row_cat){
				$option_category[$row_cat["brand_id"]] = $row_cat["name_kh"];
			}
		}
		$parent->setMultiOptions($option_category);
		
		$brand_en = new Zend_Form_Element_Text("name_en");
		$brand_en->setAttribs(array('class'=>'validate[required]','placeholder'=>' Brand Name In English',"OnChange"=>"GetBrandName(1)"));
		
		$brand_km = new Zend_Form_Element_Text("name_km");
		$brand_km->setAttribs(array('class'=>'validate[required]','placeholder'=>' Brand Name In Khmer',"OnChange"=>"GetBrandName(2)"));
		
		$icon = new Zend_Form_Element_File("icon");
		
		$this->addElements(array($icon,$status,$brand_en,$brand_km,$parent));
		if($frm !=""){
			$parent->setValue($frm["parent_id"]);
			$brand_en->setValue($frm["name_en"]);
			$brand_km->setValue($frm["name_kh"]);
			$status->setValue($frm["status"]);
		}
		return $this;
	}
}
?>
