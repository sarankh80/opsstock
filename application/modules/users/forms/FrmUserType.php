<?php 
class users_Form_FrmUserType extends Zend_Form
{	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function frmTypeUser($frm = null)
    {
    	//user name    	
    	$user_type=new Zend_Form_Element_Text('user_type');
    	$user_type->setAttribs(array(
    		'id'=>'user_type',
    		'class'=>'form-control validate[required]',
    		'Onkeyup'=>'getfillterUserType()'
    	));
    	$this->addElement($user_type);
    	$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
    	$this->addElement($status);
    	$id = new Zend_Form_Element_Hidden('id');
    	$this->addElement($id);
    	if($frm !=""){
    		$id->setValue($frm['user_type_id']);
    		$user_type->setValue($frm['user_type']);
    		$status->setValue($frm['status']);
    	}
    	return $this;
    }
}
?>