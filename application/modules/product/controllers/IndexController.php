<?php

class product_IndexController extends Zend_Controller_Action
{
 public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
    public function generateBarcodeAction(){
		$product_code = $this->getRequest()->getParam('product_code');
			header('Content-type: image/png');
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$barcodeOptions = array('text' => "$product_code",'barHeight' => 40);
			$rendererOptions = array();
			$renderer = Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions)->render();
	}
    public function indexAction()
    {
    	$db = new product_Model_DbTable_DbProduct();
		$db_global = new saleorder_Model_DbTable_DbSaleOrder();;
		$itemRows = $db_global->getProductOption();
		$this->view->itemsOption = $itemRows;
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
			if(isset($data['search'])){
				$search = array(
						'advance_search' => $data['txt_search'],
						'pro_id'=>$data['add_item'],
						'cate_id'=>$data['cat_id'],
						'brand'=>$data['brand'],
				);
				
			}elseif(isset($data['status'])){
    			$ids = $data['status'];
    			$db->updateUnStatus($ids);
    			$this->_redirect("/product/");
    		}elseif (isset($data['unstatus'])){
    			$ids = $data['unstatus'];
    			$db->updateStatus($ids);
    			$this->_redirect("/product/");
    		}
    		/*$id = $data['checkBox'];
    		if(isset($data['update'])&& $id !=""){
    			$this->_redirect("/product/index/edit/id/".$id);
    		}elseif(isset($data['delete'])&& $id !=""){
    			$db->deleteCat($id);
    			Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
    			Application_Form_FrmMessage::redirectUrl('/product/');
    		}else { $this->_redirect("/product/");}*/
    	
    	}else{
			$search = array(
						'advance_search' => '',
						'pro_id'=>-1,
						'cate_id'=>-1,
						'brand'=>-1,
				);
		}
		$datas = $db->getAllProduct($search);
		$this->view->getdata = $datas;
		$frm = new product_Form_Frmproduct();
		$this->view->frmSearch = $frm->frmSearch();
	}
	public function addAction(){
		$form = new product_Form_Frmproduct();
		$this->view->frmcate= $form->FrmProduct();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			$db = new product_Model_DbTable_DbProduct();
			if(isset($data['save'])){
				$db->add($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product/index/add');
			}
			elseif(isset($data['save_close'])){
				$db->add($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product');
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
		$db = new product_Model_DbTable_DbProduct();
		$data = $db->getProductById($id);
		$this->view->icon = $data["icon"];
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['save'])){
				$db->updat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product/index/edit/id/'.$id);
			}elseif(isset($data['save_add'])){
				$db->updat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product/index/add');
			}elseif(isset($data['save_close'])){
				$db->updat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product');
			}elseif(isset($data["km"]) == 2){
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 2;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			Application_Form_FrmMessage::redirectUrl('/product/index/edit/id/'.$id);
    		}elseif(isset($data["en"]) == 1){
    			$this->view->icon = $data["icon"];
    			//print_r($data);exit();
    			$dbs = new Application_Model_DbTable_DbSiteLanguages();
    			$ids = 1;
    			$lang = $dbs->getbyid($ids);
    			$session_lang = new Zend_Session_Namespace('lang');
    			$session_lang->unlock();
    			$session_lang->lang_id = $lang['id'];
    			$session_lang->lang = $lang['language'];
    			Application_Form_FrmMessage::redirectUrl('/product/index/edit/id/'.$id);
    		}   		 
		}
		$form = new product_Form_Frmproduct();
		$this->view->frmcate = $form->FrmProduct($data);
	}
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new product_Model_DbTable_DbProduct();
		$db->delete($id);
		Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		$this->_redirect('/product/');
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new product_Model_DbTable_DbProduct();
			$row = $db->getExistPro($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	public function viewAction(){
		$pro_id = $this->getRequest()->getParam("id");
		$db = new product_Model_DbTable_DbProduct();
		$data = $db->getProductById($id);
		$this->view->pro_id = $data["pro_id"];
		$this->view->data = $data;
	}
	public function barcodeviewAction(){
		$pro_id = $this->getRequest()->getParam("id");
		
		$db = new product_Model_DbTable_DbProduct();
		$data = $db->getProductCodesById($pro_id);
		$this->view->barcode = $data;
		$this->view->NameBarcode=$data;
	}
	
	public function barcodeAction(){
		$db = new product_Model_DbTable_DbProduct();
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			 $search = array(
			 	'cate_id'	=> $data["cat_id"],
			 	'brand'		=>	$data["brand"],
			 );
		}else{
			$search = array(
				'cate_id'	=> -1,
				'brand'		=>	-1,
				);
		}
		$data = $db->getBarCode($search);
		$this->view->barcode = $data;
		$frm = new product_Form_Frmproduct();
		$this->view->frmSearch = $frm->frmSearch();
	}
	
	public function adjustAction()
	{
		$db = new product_Model_DbTable_DbProduct();
		$db_global = new saleorder_Model_DbTable_DbSaleOrder();;
		$itemRows = $db_global->getProductOption();
		$this->view->itemsOption = $itemRows;
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['search'])){
				$search = array(
						'advance_search' => $data['txt_search'],
						'pro_id'=>$data['add_item'],
						'cate_id'=>$data['cat_id'],
						'brand'=>$data['brand'],
				);
			}elseif (isset($data['update'])){
				$db->adjust($data);
				$this->_redirect("/product/index/adjust");
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
		}else{
			$search = array(
						'advance_search' => '',
						'pro_id'=>-1,
						'cate_id'=>-1,
						'brand'=>-1,
				);
		}
		$datas = $db->getProductAdjust($search);
		$this->view->getdata = $datas;
		$frm = new product_Form_Frmproduct();
		$this->view->frmSearch = $frm->frmSearch();
		
		 
	}
}

