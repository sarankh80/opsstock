<?php

class table_IndexController extends Zend_Controller_Action
{
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new table_Model_DbTable_DbTable();
    	$datas = $db->getAllCate();
    	$this->view->getdata = $datas;
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if(isset($data['status'])){
    			$ids = $data['status'];
    			$db->updateUnStatus($ids);
    			$this->_redirect("/table/");
    		}elseif (isset($data['unstatus'])){
    			$ids = $data['unstatus'];
    			$db->updateStatus($ids);
    			$this->_redirect("/table/");
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
    			$this->_redirect("/table/index/edit/id/".$id);
    		}elseif(isset($data['delete'])&& $id !=""){
    			$db->deleteCat($id);
    			Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
    			Application_Form_FrmMessage::redirectUrl('/table/');
    		}else { $this->_redirect("/table/");}
    	
    	}
    	
	}
	public function addAction(){
		$form = new table_Form_FrmTable();
		$this->view->frmcate= $form->FrmProCate();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			$db = new table_Model_DbTable_DbTable();
			if(isset($data['save'])){
				$db->addProCate($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/table/index/add');
			}
			elseif(isset($data['save_close'])){
				$db->addProCate($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/table');
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
		$db = new table_Model_DbTable_DbTable();
		$data = $db->getCateById($id);
		$this->view->icon = $data["icon"];
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['save'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/table/index/edit/id/'.$id);
			}
			if(isset($data['save_add'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/table/index/add');
			}
			if(isset($data['save_close'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/table');
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
		$form = new table_Form_FrmTable();
		$this->view->frmcate = $form->FrmProCate($data);
	}
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new table_Model_DbTable_DbTable();
		$db->deleteCat($id);
		Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		$this->_redirect('/table/');
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new table_Model_DbTable_DbTable();
			$row = $db->getExistCat($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
}

