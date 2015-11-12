<?php

class phurchase_OrderController extends Zend_Controller_Action
{
    public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$db = new phurchase_Model_DbTable_DbOrder();
    	
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if(isset($data["search"])){
    			$search = array(
					'txt_search' 		=> $data['txt_search'],
					'start_date' 		=> $data['start_date'],
					'end_date'			=>	$data['end_date'],
					'status'			=>	$data['status'],
					'supplier'			=>	$data['supplier'],
					'pay_status'		=>	$data['pay_status'],
					'purchase_no'		=>	$data['purchase_no'],
			);
    			
    		}
//     		if(isset($data['status'])){
//     			$ids = $data['status'];
//     			$db->updateUnStatus($ids);
//     			$this->_redirect("/phurchase/order");
//     		}elseif (isset($data['unstatus'])){
//     			$ids = $data['unstatus'];
//     			$db->updateStatus($ids);
//     			$this->_redirect("/phurchase/order");
//     		}
    		$id = $data['checkBox'];
    		if(isset($data['update'])&& $id !=""){
    			$this->_redirect("/phurchase/order/edit/id/".$id);
    		}elseif(isset($data['delete'])&& $id !=""){
    			$db->deleteCat($id);
    			Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
    			Application_Form_FrmMessage::redirectUrl('/phurchase/order');
    		}else { $this->_redirect("/phurchase/order");}
    	
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
		$datas = $db->getAllCate($search);
		$this->view->getdata = $datas;
    	$form = new report_Form_FrmReport();
    	$this->view->frm = $form->FrmSearch();
    	
	}
	public function addAction(){
		$form = new phurchase_Form_FrmPhurchaseOrder();
		$this->view->frmcate= $form->FrmProCate();
		
		$formven = new phurchase_Form_FrmVendor();
		$this->view->frmcatevendor= $formven->FrmProCate();
		
		$items = new phurchase_Model_DbTable_DbOrder();
		$itemRows = $items->selectProductOption();
		$this->view->items = $itemRows;
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			$payment_purchase_order = new phurchase_Model_DbTable_DbOrder();
			if(isset($data['save'])){
				$payment_purchase_order->vendorPurchaseOrderPayment($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/order/add');
			}
			elseif(isset($data['save_close'])){
				$payment_purchase_order->vendorPurchaseOrderPayment($data);
				Application_Form_FrmMessage::message("ការបញ្ចូលបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/order');
			}else {
				Application_Form_FrmMessage::message("ការបញ្ចូលបរាជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/phurchase/order');
			}
		}
		
	}
	public function editAction(){
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		$db = new phurchase_Model_DbTable_DbOrder();
		$itemRows = $db->selectProductOption();
		$this->view->items = $itemRows;
		
		$orderDetail = $db->getSalesOderID($id);
		$this->view->rowsOrder = $orderDetail;
		
		$itemRowsGet = $db->getProductOption();
		$this->view->itemsOption = $itemRowsGet;
		
		$data = $db->getPhurchase($id);
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			//print_r($data);exit();
			if(isset($data['save'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product/index/edit/id/'.$id);
			}elseif(isset($data['save_add'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product/index/add');
			}elseif(isset($data['save_close'])){
				$db->updatCat($data, $id);
				Application_Form_FrmMessage::message("ការកែប្រែបានជោគជ័យ");
				Application_Form_FrmMessage::redirectUrl('/product');
			}
		}
		$form = new phurchase_Form_FrmPhurchaseOrder();
		$this->view->frmcate = $form->FrmProCate($data);
	}
	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new product_Model_DbTable_DbProduct();
		$db->deleteCat($id);
		Application_Form_FrmMessage::message("កាលុបប្រភេទអ្នកប្រើប្រាស់បានជោគជ័យ");
		$this->_redirect('/product/');
	}
	public function getfillteraddAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new product_Model_DbTable_DbProduct();
			$row = $db->getExistCat($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	public function checkWarningAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbdata = new phurchase_Model_DbTable_DbReturnStock();
			$result = $_dbdata->getQtyWarningByItemId($_data["item_id"],$_data["LocationId"]);
			$warnning = $result["qty_warn"];
			$qty_in_stock = $result["qty"];
			if(!empty($result)){
				if($qty_in_stock <= $warnning){
					$results= array("message"=>"ផលិតផលជិតអស់ពី ស្តុកហើយ!!");
				}else $results= array("message"=>"");
// 				//foreach ($result as $rs){
// 				$results= array("message"=>$result["message"].$result['qty_onhand']);
// 				//}
			}
			echo Zend_Json::encode($results);
			exit();
		}
	}
		
}

