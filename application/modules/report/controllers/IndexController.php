<?php

class report_IndexController extends Zend_Controller_Action
{
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }
	public function productAction(){
		$db = new report_Model_DbTable_DbReport();
		$db_global = new Application_Model_DbTable_DbGlobal();
		$form = new report_Form_FrmReport();
		
		$itemRows = $db_global->getProductOption();
		$this->view->itemsOption = $itemRows;
		
		$company_name = $db_global->getSettingByCode(4);
		$slogan = $db_global->getSettingByCode(5);
		$address = $db_global->getSettingByCode(2);
		$tel = $db_global->getSettingByCode(3);
		$qty_warning = $db_global->getSettingByCode(6);
		$this->view->company_name = $company_name;
		$this->view->slogan = $slogan;
		$this->view->address = $address;
		$this->view->tel = $tel;
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$search = array(
						'txt_search' 		=> $data['txt_search'],
						'add_item' 			=> $data['add_item'],
						'status'			=>	$data['status'],
						'cat_id'			=>	$data['cat_id'],
						'brand'				=>	$data['brand'],
			);
		}else{
			$search = array(
					'txt_search' 		=> '',
					'add_item' 			=> -1,
					'status'			=>'-1',
					'cat_id'			=>-1,
					'brand'				=>-1,
			);
		}
		$product = $db->getProduct($search);
		$this->view->product = $product;
		$this->view->frm = $form->FrmSearch();
		
	}
	public function saleAction(){
		$db = new report_Model_DbTable_DbReport();
		$db_global = new Application_Model_DbTable_DbGlobal();
		$form = new report_Form_FrmReport();
		
		$itemRows = $db_global->getProductOption();
		$this->view->itemsOption = $itemRows;
		$company_name = $db_global->getSettingByCode(4);
		$slogan = $db_global->getSettingByCode(5);
		$address = $db_global->getSettingByCode(2);
		$tel = $db_global->getSettingByCode(3);
		$qty_warning = $db_global->getSettingByCode(6);
		$this->view->company_name = $company_name;
		$this->view->slogan = $slogan;
		$this->view->address = $address;
		$this->view->tel = $tel;
		
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
		$sale= $db->getSale($search);
		$this->view->sale = $sale;
		$this->view->frm = $form->FrmSearch();
	}
	
	public function purchaseAction(){
		$db = new report_Model_DbTable_DbReport();
		$db_global = new Application_Model_DbTable_DbGlobal();
		$form = new report_Form_FrmReport();
	
		$itemRows = $db_global->getProductOption();
		$this->view->itemsOption = $itemRows;
		
		$company_name = $db_global->getSettingByCode(4);
		$slogan = $db_global->getSettingByCode(5);
		$address = $db_global->getSettingByCode(2);
		$tel = $db_global->getSettingByCode(3);
		$qty_warning = $db_global->getSettingByCode(6);
		$this->view->company_name = $company_name;
		$this->view->slogan = $slogan;
		$this->view->address = $address;
		$this->view->tel = $tel;
	
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$search = array(
					'txt_search' 		=> $data['txt_search'],
					'start_date' 		=> $data['start_date'],
					'end_date'			=>	$data['end_date'],
					'status'			=>	$data['status'],
					'supplier'			=>	$data['supplier'],
					'pay_status'		=>	$data['pay_status'],
					'purchase_no'		=>	$data['purchase_no'],
			);
		}else{
			$search = array(
					'txt_search' 		=> '',
					'start_date'		=> date('Y-m-d'),
					'end_date'			=>date('Y-m-d'),
					'status'			=>-1,
					'supplier'			=>	-1,
					'pay_status'		=> -1,
					'purchase_no'		=>	-1,
			);
		}
		$sale= $db->getPurchase($search);
		$this->view->sale = $sale;
		$this->view->frm = $form->FrmSearch();
	}
	
	public function saleviewAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new report_Model_DbTable_DbReport();
		$db_global = new Application_Model_DbTable_DbGlobal();
		
		$row_sale_order = $db->getSaleOrderView($id);
		$row_sale_order_item = $db_global->getSaleOrderDetail($id);
		
		$this->view->row_sale_order = $row_sale_order;
		
		$this->view->row_sale_order_item = $row_sale_order_item;
		
		
	}
	public function purchaseviewAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new report_Model_DbTable_DbReport();
		$db_p = new phurchase_Model_DbTable_DbOrder();
	
		$row_pu_order = $db->getPurchaseView($id);
		$row_pu_order_item = $db_p->getSalesOderID($id);
	
		$this->view->row_pu_order = $row_pu_order;
	
		$this->view->row_pu_order_item = $row_pu_order_item;
	
	
	}
}

