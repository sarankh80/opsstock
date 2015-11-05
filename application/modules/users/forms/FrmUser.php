<?php 
class users_Form_FrmUser extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function frmuser($frm = null)
    {
    	$db = new Application_Model_DbTable_DbGlobal();
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
			
    	$id_code = new Zend_Form_Element_Text('id_code');
    		$id_code->setAttribs(array('class'=>'form-control validate[required]',));
    		
    	$fullname = new Zend_Form_Element_Text('fullname');
    		$fullname->setAttribs(array('class'=>'form-control validate[required]',));
    	$username = new Zend_Form_Element_Text('username');    	
    		$username->setAttribs(array('class'=>'form-control validate[required]','Onkeyup'=>'getfillterUserType()'));
    	$email = new Zend_Form_Element_Text('email');    	
    		$email->setAttribs(array('class'=>'validate[required,custom[email]] form-control','Onkeyup'=>'getfillterUserType()'));
		$old_password = new Zend_Form_Element_Password('old_password');    	
    		$old_password->setAttribs(array('class'=>'validate[required,minSize[5]] form-control',));
    	$password = new Zend_Form_Element_Password('password');
    	
    		$password->setAttribs(array('class'=>'validate[required,minSize[5]] form-control',));
    	$confirm_password = new Zend_Form_Element_Password('confirm_password');    	
    		$confirm_password->setAttribs(array('class'=>'validate[required,equals[password]] form-control',));
    	
    	
		$rs = $db->getGlobalDb('SELECT user_type_id,user_type FROM tb_user_type where user_type_id');
		$options = array(''=>'--- ជ្រើសរើសប្រភេទអ្នកប្រើប្រាស់ ---');		
		foreach($rs as $read) $options[$read['user_type_id']]= $read['user_type'];
		$user_type_id = new Zend_Form_Element_Select('user_type');		
    	$user_type_id->setMultiOptions($options);
    	$user_type_id->setAttribs(array(
    		'id'=>'user_type_id',
    		'class'=>'form-control chzn-select validate[required]',
    	));
    	$id = new Zend_Form_Element_Hidden('id');
    	$photo = new Zend_Form_Element_File('photo');
    	$this->addElements(array($status,$id_code,$photo,$id,$fullname,$username,$email,$password,$confirm_password,$user_type_id,$old_password));    	
    	if($frm !=""){
    		$id->setValue($frm['user_id']);
    		$fullname->setValue($frm['name']);
    		$username->setValue($frm['user_name']);
    		$email->setValue($frm['email']);
    		$id_code->setValue($frm['user_code']);
//     		$password->setValue($frm['password']);
    		$user_type_id->setValue($frm['user_type']);
    		$photo->setValue($frm['photo']);
    		$status->setValue($frm['status']);
    	}
    	return $this;
     }
}
?>
