<?php 
class procategory_Form_FrmProCategory extends Zend_Form
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
		$parent = new Zend_Form_Element_Select("parent_id");
		$parent->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$category = $db->getAllProCategories();
		if(empty($category)){
			$option_category = array(''=>'No Category To Select');
		}else {
			$option_category = array(''=>'Choose Category');
			foreach ($category as $row_cat){
				$option_category[$row_cat["cat_id"]] = $row_cat["cat_name_km"];
			}
		}
		$parent->setMultiOptions($option_category);
		
		$cat_name_en = new Zend_Form_Element_Text("name_en");
		$cat_name_en->setAttribs(array('class'=>'validate[required]','placeholder'=>' Category Name In English',"OnChange"=>"GetCatName(1)"));
		
		$cat_name_km = new Zend_Form_Element_Text("name_km");
		$cat_name_km->setAttribs(array('class'=>'validate[required]','placeholder'=>' Category Name In Khmer',"OnChange"=>"GetCatName(2)"));
		
		$icon = new Zend_Form_Element_File("icon");
		
		$this->addElements(array($icon,$status,$cat_name_en,$cat_name_km,$parent));
		if($frm !=""){
			$parent->setValue($frm["parent_id"]);
			$cat_name_en->setValue($frm["cat_name_en"]);
			$cat_name_km->setValue($frm["cat_name_km"]);
			$status->setValue($frm["status"]);
		}
		return $this;
	}
}
?>
