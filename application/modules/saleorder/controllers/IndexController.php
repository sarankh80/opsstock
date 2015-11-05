<?php

class saleorder_IndexController extends Zend_Controller_Action
{
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new saleorder_Model_DbTable_DbSaleOrder();
    if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$search = array(
					'txt_search' 		=> $data['txt_search'],
					'start_date' 		=> $data['start_date'],
					'end_date'			=>	$data['end_date'],
					'status'			=>	$data['status'],
					'customer'			=>	$data['customer'],
					'pay_status'		=>	$data['pay_status'],
					'invoice_no'		=>	$data['invoice_no'],
			);
		}else{
			$search = array(
					'txt_search' 		=> '',
					'start_date'		=> date('Y-m-d'),
					'end_date'			=> date('Y-m-d'),
					'status'			=>-1,
					'customer'			=>	-1,
					'pay_status'		=>	-1,
					'invoice_no'		=> -1,
			);
		}
		
		$datas = $db->getAllSaleOrderList($search);
		$this->view->getdata = $datas;
		
		$form = new report_Form_FrmReport();
		$this->view->frm = $form->FrmSearch();
	}
	public function addAction(){
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$datas = $db->getAllProduct();
		$saleorder = $db->getAllSaleOrder();		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db->addSaleOrder($data);
			Application_Form_FrmMessage::redirectUrl('/saleorder/index/add');
		}
		$this->view->getdata = $datas;
		$this->view->saleorder = $saleorder;
		$form = new saleorder_Form_FrmSaleOrder();
		$this->view->frm =$form->FrmSaleOrder();
		
		$this->view->FrmSaleOrderUpdate = $form->FrmSaleOrderUpdate();
		
		$itemRows = $db->getProductOption();
		$this->view->itemsOption = $itemRows;
		
		
	}
	public function editAction(){
		$id = $this->getRequest()->getParam("id");
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$saleorder = $db->getSaleOrderById($id);
		$datas = $db->getAllProduct();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['save'])){
			}
			if(isset($data['save_add'])){
			}
			if(isset($data['save_close'])){
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
    		}   		
		}
		$form = new saleorder_Form_FrmSaleOrder();
		$this->view->frm = $form->FrmSaleOrder($saleorder);
		$this->view->getdata = $datas;
		$this->view->saleorder = $saleorder;
	}
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new procategory_Model_DbTable_DbProCate();
		$db->deleteCat($id);
		Application_Form_FrmMessage::message("Ã¡Å¾â‚¬Ã¡Å¾Â¶Ã¡Å¾â€ºÃ¡Å¾Â»Ã¡Å¾â€�Ã¡Å¾â€�Ã¡Å¸â€™Ã¡Å¾Å¡Ã¡Å¾â€”Ã¡Å¸ï¿½Ã¡Å¾â€˜Ã¡Å¾Â¢Ã¡Å¸â€™Ã¡Å¾â€œÃ¡Å¾â‚¬Ã¡Å¾â€�Ã¡Å¸â€™Ã¡Å¾Å¡Ã¡Å¾Â¾Ã¡Å¾â€�Ã¡Å¸â€™Ã¡Å¾Å¡Ã¡Å¾Â¶Ã¡Å¾Å¸Ã¡Å¸â€¹Ã¡Å¾â€�Ã¡Å¾Â¶Ã¡Å¾â€œÃ¡Å¾â€¡Ã¡Å¸â€žÃ¡Å¾â€šÃ¡Å¾â€¡Ã¡Å¸ï¿½Ã¡Å¾â„¢");
		$this->_redirect('/procategory/');
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new procategory_Model_DbTable_DbProCate();
			$row = $db->getExistCat($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	public function getSaleOrderByIdAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new saleorder_Model_DbTable_DbSaleOrder();
			$row = $db->getSaleOrderById($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
}

