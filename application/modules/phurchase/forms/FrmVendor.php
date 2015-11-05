<?php 
class phurchase_Form_FrmVendor extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProCate($frm = null){
		
		$vendor_name = new Zend_Form_Element_Text("vendor_name");
		$vendor_name->setAttribs(array('class'=>'validate[required]','placeholder'=>'ឈ្មោះអ្នផ្ឌត់ផ្ឌង់'));
		
		$phone = new Zend_Form_Element_Text("phone");
		$phone->setAttribs(array('class'=>'validate[required]','placeholder'=>''));
		
		$contact = new Zend_Form_Element_Text("contact");
		$contact->setAttribs(array('class'=>'validate[required]','placeholder'=>''));
		
		$email = new Zend_Form_Element_Text("email");
		$email->setAttribs(array('class'=>'validate[required]','placeholder'=>''));
		
		$address = new Zend_Form_Element_Textarea("address");
		$address->setAttribs(array('class'=>'','placeholder'=>''));
		$status = new Zend_Form_Element_Select('status');
		$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
		$status->setMultiOptions($_arr_status);
		$status->setAttribs(array('class'=>'form-control validate[required]',));
		$this->addElements(array($vendor_name,$phone,$contact,$email,$address,$status));
		if($frm !=""){
			$vendor_name->setValue($frm["v_name"]);
			$phone->setValue($frm["phone"]);
			$contact->setValue($frm["contact_name"]);
			$email->setValue($frm["email"]);
			$address->setValue($frm["vendor_remark"]);
			$status->setValue($frm["is_active"]);
		}
		return $this;
	}
}
?>
