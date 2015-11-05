<?php

class users_UserTypeController extends Zend_Controller_Action
{

	
    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new users_Model_DbTable_DbUserType();
    	$data = $db->getUserTypes();
    	$this->view->getdata = $data;
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if(isset($data['status'])){
    			//print_r($data);exit();
    			$ids = $data['status'];
    			$db->updateUnStatus($ids);
    			$this->_redirect("/users/user-type");   			
    		}elseif (isset($data['unstatus'])){
    			$ids = $data['unstatus'];
    			$db->updateStatus($ids);
    			$this->_redirect("/users/user-type");
    		}
    		$id = $data['checkBox']; 
    		if(isset($data['update']) && $id !=""){    			
    			$this->_redirect("/users/user-type/edit/id/".$id);
    		}elseif(isset($data['delete']) && $id !=""){
		    	$db->deleteUserType($id);
		    	Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		    	Application_Form_FrmMessage::redirectUrl('/users/user-type');
    		}else { $this->_redirect("/users/user-type");}
    		
    	}
    }
	public function addAction(){
		$frmuser = new users_Form_FrmUserType();
		$this->view->frmusers = $frmuser->frmTypeUser();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new users_Model_DbTable_DbUserType();
			if(isset($data['save'])){	
				$db->insertUserType($data);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
			}
			if(isset($data['save'])){
				$db->insertUserType($data);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				$id = $this->getRequest()->getParam("id");
			}
			if(isset($data['save_close'])){
				$db->insertUserType($data);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users/user-type');
			}
		}
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$user_type = $data['user_type'];
			$sql= "SELECT user_type FROM tbwu_acl_user_type WHERE user_type = '$user_type'";
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getGlobalDb($sql);
			echo Zend_Json::encode($row);
			exit();
		}
	}
    public function editAction()
    {	
    	$user_type_id=$this->getRequest()->getParam('id');
    	$db = new users_Model_DbTable_DbUserType();
    	$row = $db->getUserType($user_type_id);
    	$frmuser = new users_Form_FrmUserType();
    	$this->view->frmusers = $frmuser->frmTypeUser($row);
    	if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
    		if(isset($data['save'])){	
				$db->updateUserType($data,$user_type_id);
				Application_Form_FrmMessage::redirectUrl('/users/user-type/edit/id/'.$user_type_id);
			}
			if(isset($data['save_add'])){
				$db->updateUserType($data,$user_type_id);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users/user-type/add');
			}
			if(isset($data['save_close'])){
				$db->updateUserType($data,$user_type_id);
				Application_Form_FrmMessage::message("កាបញ្ចូលប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/users/user-type');
			}
		}
    }
    public function deleteAction(){
    	$id = $this->getRequest()->getParam('id');
    	$db = new users_Model_DbTable_DbUserType();
    	$db->deleteUserType($id);
    	Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
    	$this->_redirect('/users/user-type');
    }
}