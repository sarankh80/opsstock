<?php

class users_IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new users_Model_DbTable_DbUser();
    	$datas = $db->getUserShow();
    	$this->view->getdata = $datas;
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if(isset($data['status'])){
    			$ids = $data['status'];
    			$db->updateUnStatus($ids);
    			$this->_redirect("/users/");   			
    		}elseif (isset($data['unstatus'])){
    			$ids = $data['unstatus'];
    			$db->updateStatus($ids);
    			$this->_redirect("/users/");
    		}elseif(isset($data["km"]) == 2){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 2;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//print_r($lang);exit();
    		}elseif(isset($data["en"]) == 1){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 1;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//$this->_redirect('/login');
    			//exit();
    		}   		
    		$id = $data['checkBox']; 
    		if(isset($data['update'])&& $id !=""){   			
    			$this->_redirect("/users/index/edit/id/".$id);
    		}elseif(isset($data['delete'])&& $id !=""){
		    	$db->deleteUser($id);
		    	Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		    	Application_Form_FrmMessage::redirectUrl('/users/');
    		}else { $this->_redirect("/users/");}
    		
    	}
	}
	public function addAction(){
		$form = new users_Form_FrmUser();
		$this->view->frmusers = $form->frmuser();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new users_Model_DbTable_DbUser();
			if(isset($data['save'])){
				$db->insertUser($data);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
			}
			if(isset($data['save_close'])){
				$db->insertUser($data);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users');
			}elseif(isset($data["km"]) == 2){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 2;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//print_r($lang);exit();
    		}elseif(isset($data["en"]) == 1){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 1;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//$this->_redirect('/login');
    			//exit();
    		}   		
		}
	}
	public function editAction()
	{
		$user_id = $this->getRequest()->getParam('id');
		$this->view->ids = $user_id;
		$db = new users_Model_DbTable_DbUser();
		$row = $db->getUser($user_id);
		$this->view->photo = $row['photo'];	
		$form = new users_Form_FrmUser();
		$this->view->frmusers = $form->frmuser($row);		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			if(isset($data['save'])){
				$db->updateUser($data,$user_id);
				$row = $db->getUser($user_id);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users/index/edit/id/'.$user_id);
			}
			if(isset($data['save_add'])){
				$db->updateUser($data,$user_id);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users/index/add');
			}
			if(isset($data['save_close'])){
				$db->updateUser($data,$user_id);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users');
			}elseif(isset($data["km"]) == 2){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 2;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//print_r($lang);exit();
    		}elseif(isset($data["en"]) == 1){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 1;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//$this->_redirect('/login');
    			//exit();
    		}   		
		}
	}
	public  function profileAction(){
		//$this->_helper->layout()->disableLayout();
		$db = new users_Model_DbTable_DbUser();
		$user_id = $this->getRequest()->getParam("id");
		$rs=$db->getuserprofile($user_id);
		//print_r($rs);exit();
		$this->view->profile=$rs;
	}		 
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new users_Model_DbTable_DbUser();
		$db->deleteUser($id);
		Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		$this->_redirect('/users/');
	}
	public function changePasswordAction(){
		$id = $this->getRequest()->getParam('id');
		$form = new users_Form_FrmUser();
		$this->view->frmusers = $form->frmuser();
		if($this->getRequest()->isPost()){			
			$data = $this->getRequest()->getPost();
			
			$db = new users_Model_DbTable_DbUser();
			$old_password = $this->getRequest()->getParam('old_password');			
			$password = $this->getRequest()->getParam('password');
			if(isset($data['save'])){			
				if($db->getpassword($id,$old_password)){
					$db->changePassword($id, md5($password));
					Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				}else {Application_Form_FrmMessage::message("កាបញ្ចូលមិនបានជោគជ័យ");}
			}
			if(isset($data['save_close'])){
				if($db->getpassword($id,$old_password)){
					$db->changePassword($id, md5($password));
					Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
					Application_Form_FrmMessage::redirectUrl('/users');
				}else {Application_Form_FrmMessage::message("កាបញ្ចូលមិនបានជោគជ័យ");}
			}elseif(isset($data["km"]) == 2){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 2;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//print_r($lang);exit();
    		}elseif(isset($data["en"]) == 1){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 1;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			//$this->_redirect('/login');
    			//exit();
    		}   		
		}
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$username = $data['username'];
			$sql= "SELECT username FROM tbwu_acl_user WHERE username = '$username'";
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getGlobalDbRow($sql);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	public function addxslAction(){
		// Optional added for consistency
		parent::init();
		// Excel format context
		$excelConfig =
		array(
		'excel' => array(
		'suffix'  => 'excel',
		'headers' => array(
		'Content-type' => 'application/vnd.ms-excel')),
		);		 
		// Init the Context Switch Action helper
		$contextSwitch = $this->_helper->contextSwitch();
		// Add the new context
		$contextSwitch->setContexts($excelConfig);		 
		// Set the new context to the reports action
		$contextSwitch->addActionContext('report', 'excel');
	 
		// Initializes the action helper
		$contextSwitch->initContext();
	}
}

