<?php 
class location_Form_FrmLocation extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmLocation($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
    		
    		$Location_code = new Zend_Form_Element_Text("location_code");
    		$Location_code->setAttribs(array('class'=>'validate[required]','placeholder'=>' Location Code'));
    		
		$Location_en = new Zend_Form_Element_Text("name_en");
		$Location_en->setAttribs(array('class'=>'validate[required]','placeholder'=>' Location Name In English',"OnChange"=>"GetLocationName(1)"));
		
		$Location_km = new Zend_Form_Element_Text("name_km");
		$Location_km->setAttribs(array('class'=>'validate[required]','placeholder'=>' Location Name In Khmer',"OnChange"=>"GetLocationName(2)"));
		
		$icon = new Zend_Form_Element_File("icon");
		
		$tel = new Zend_Form_Element_Text("tel");
		$tel->setAttribs(array('class'=>'validate[required]','placeholder'=>' Phone Number'));
		
		$fax = new Zend_Form_Element_Text("fax");
		$fax->setAttribs(array('class'=>'validate[required]','placeholder'=>' Fax Number'));
		
		$contact = new Zend_Form_Element_Text("contact");
		$contact->setAttribs(array('class'=>'validate[required]','placeholder'=>' Contact Name'));
		
		$email = new Zend_Form_Element_Text("email");
		$email->setAttribs(array('class'=>'validate[required]','placeholder'=>' Email address'));
		
		$address = new Zend_Form_Element_Text("address");
		$address->setAttribs(array('class'=>'validate[required]','placeholder'=>' Address'));
		
		$company = new Zend_Form_Element_Text("company");
		$company->setAttribs(array('class'=>'validate[required]','placeholder'=>' Company Name'));
		
		$description = new Zend_Form_Element_Text("description");
		$description->setAttribs(array('class'=>'validate[required]','placeholder'=>' Contact Name'));
		
		$this->addElements(array($Location_code,$tel,$fax,$description,$email,$address,$company,$contact,$icon,$status,$Location_en,$Location_km));
		if($frm !=""){
			$Location_code->setValue($frm["location_code"]);
			$Location_en->setValue($frm["name_en"]);
			$Location_km->setValue($frm["name_kh"]);
			$status->setValue($frm["status"]);
			$tel->setValue($frm["phone"]);
			$fax->setValue($frm["fax"]);
			$email->setValue($frm["email"]);
			$company->setValue($frm["company"]);
			$contact->setValue($frm["contact"]);
			$address->setValue($frm["address"]);
			$description->setValue($frm["description"]);
		}
		return $this;
	}
}
?>
