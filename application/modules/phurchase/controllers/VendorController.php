<?php

class phurchase_VendorController extends Zend_Controller_Action
{
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new phurchase_Model_DbTable_DbVendor();
    	$datas = $db->getAllCate();
    	$this->view->getdata = $datas;
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if(isset($data['status'])){
    			$ids = $data['status'];
    			$db->updateUnStatus($ids);
    			$this->_redirect("/phurchase/vendor");
    		}elseif (isset($data['unstatus'])){
    			$ids = $data['unstatus'];
    			$db->updateStatus($ids);
    			$this->_redirect("/phurchase/vendor");
    		}
    		$id = $data['checkBox'];
    		if(isset($data['update'])&& $id !=""){
    			$this->_redirect("/phurchase/vendor/edit/id/".$id);
    		}elseif(isset($data['delete'])&& $id !=""){
    			$db->deleteCat($id);
    			Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
    			Application_Form_FrmMessage::redirectUrl('/phurchase/vendor');
    		}else { $this->_redirect("/phurchase/vendor");}
    	
    	}
    	
	}
	public function addAction(){
		$form = new phurchase_Form_FrmVendor();
		$this->view->frmcate= $form->FrmProCate();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			$db = new phurchase_Model_DbTable_DbVendor();
			if(isset($data['save'])){
				$db->addProCate($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/vendor/add');
			}
			elseif(isset($data['save_close'])){
				$db->addProCate($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/vendor');
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
	public function editAction(){
		$id = $this->getRequest()->getParam("id");
		$db = new phurchase_Model_DbTable_DbVendor();
		$data = $db->getCateById($id);
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['save'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/vendor/edit/id/'.$id);
			}elseif(isset($data['save_add'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/vendor/add');
			}elseif(isset($data['save_close'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/vendor');
			}
		}
		$form = new phurchase_Form_FrmVendor();
		$this->view->frmcate = $form->FrmProCate($data);
	}
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new phurchase_Model_DbTable_DbVendor();
		$db->deleteCat($id);
		Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		$this->_redirect('/phurchase/vendor');
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new phurchase_Model_DbTable_DbVendor();
			$row = $db->getExistCat($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
}

