<?php

class brand_IndexController extends Zend_Controller_Action
{
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new brand_Model_DbTable_DbBrand();
    	$datas = $db->getAllBrand();
    	$this->view->getdata = $datas;
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if(isset($data['status'])){
    			$ids = $data['status'];
    			$db->updateUnStatus($ids);
    			$this->_redirect("/brand/");
    		}elseif (isset($data['unstatus'])){
    			$ids = $data['unstatus'];
    			$db->updateStatus($ids);
    			$this->_redirect("/brand/");
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
    			$this->_redirect("/brand/index/edit/id/".$id);
    		}elseif(isset($data['delete'])&& $id !=""){
    			$db->deleteCat($id);
    			Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
    			Application_Form_FrmMessage::redirectUrl('/brand/');
    		}else { $this->_redirect("/brand/");}
    	
    	}
    	
	}
	public function addAction(){
		$form = new brand_Form_FrmBrand();
		$this->view->frmcate= $form->FrmBrand();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
// 			print_r($data);exit();
			$db = new brand_Model_DbTable_DbBrand();
			if(isset($data['save'])){
				$db->add($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/brand/index/add');
			}
			elseif(isset($data['save_close'])){
				$db->add($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/brand');
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
		$db = new brand_Model_DbTable_DbBrand();
		$data = $db->getBrandById($id);
		$this->view->icon = $data["icon"];
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['save'])){
				$db->updat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/brand/index/edit/id/'.$id);
			}
			if(isset($data['save_add'])){
				$db->updat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/brand/index/add');
			}
			if(isset($data['save_close'])){
				$db->updat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/brand');
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
		$form = new brand_Form_FrmBrand();
		$this->view->frmcate = $form->FrmBrand($data);
	}
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new brand_Model_DbTable_DbBrand();
		$db->delete($id);
		Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		$this->_redirect('/brand/');
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new brand_Model_DbTable_DbBrand();
			$row = $db->getExistName($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
}

