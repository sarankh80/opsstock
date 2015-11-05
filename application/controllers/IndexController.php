<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction(){
        // action body
        $this->_helper->layout()->disableLayout();
        if($this->getRequest()->isPost()){
        	$data = $this->getRequest()->getPost();        	
        	if($data["lange"] == 2){
        		$dbs = new Application_Model_DbTable_DbSiteLanguages();
        		$ids = 2;
        		$lang = $dbs->getbyid($ids);
        		$session_lang = new Zend_Session_Namespace('lang');
        		$session_lang->unlock();
        		$session_lang->lang_id = $lang['id'];
        		$session_lang->lang = $lang['language'];
        	}if($data["lange"] == 1){
        			$dbs = new Application_Model_DbTable_DbSiteLanguages();
        			$ids = 1;
        			$lang = $dbs->getbyid($ids);
        			$session_lang = new Zend_Session_Namespace('lang');
        			$session_lang->unlock();
        			$session_lang->lang_id = $lang['id'];
        			$session_lang->lang = $lang['language'];
        	}
        	$db_user = new Application_Model_DbTable_DbUsers();
        	$login = $data['login'];
        	$password = $data['password'];
        	if($db_user->checkUsr($login)){
        		if($db_user->userAuthenticate($login,$password)){
        			$user_id = $db_user->getUserID($login);
        			$user_info = $db_user->getUserInfo($user_id);
        			if($user_info['user_type'] ==1){
        				$session_user = new Zend_Session_Namespace('auth');
        				$session_user->unlock();
        				$session_user->user_id = $user_id;
        				$session_user-> fullname = $user_info['name'];
        				$session_user->user_name=$user_info['user_name'];
        				$session_user->level = $user_info['user_type'];
        				$session_user->email = $user_info['email'];
        				Application_Form_FrmMessage::redirector('/index/pos');
        			}else {
        				$session_user = new Zend_Session_Namespace('auth');
        				$session_user->unlock();
        				$session_user->user_id = $user_id;
        				$session_user-> fullname = $user_info['name'];
        				$session_user->user_name=$user_info['user_name'];
        				$session_user->level = $user_info['user_type'];
        				$session_user->email = $user_info['email'];
        				Application_Form_FrmMessage::redirector('/index/home');
        			}
        		}elseif (!$db_user->checkStatusBy($login)){
					$this->view->msg = (' Login  Fall Comfirm ! ');
				}else{
					$this->view->msg  = (' User Name or Password Incorect ! ');
				}        		
        	}else{
        		$this->view->msg = (' Login  not Success ! ');
        	}
        }  
    }
	public function getQtyWarningAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getQtyWarning($data);
			echo Zend_Json::encode($row);
			exit();
		}
}
	public function homeAction(){
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$datas = $db->getAllProduct();
		$saleorder = $db->getAllSaleOrderList();		
		$this->view->getdata = $datas;
		$this->view->saleorder = $saleorder;
		$form = new saleorder_Form_FrmSaleOrder();
		$this->view->frm =$form->FrmSaleOrder();
		
		$this->view->FrmSaleOrderUpdate = $form->FrmSaleOrderUpdate();
		
		$itemRows = $db->getProductOption();
		$this->view->itemsOption = $itemRows;
		
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			if(isset($data["save"])){
				//print_r($data);exit();
				if(!empty($data["table"])){
					$db->addSaleOrder($data);
				}
// 				Application_Form_FrmMessage::redirectUrl('/saleorder/index/add');
			}elseif (isset($data["saveUpdate"])){
				if(!empty($data["id_record"])){
					$db->updateSaleOrder($data);
					Application_Form_FrmMessage::message("Sussess!");
					Application_Form_FrmMessage::redirectUrl('/index/home');
				}
				//print_r($data);exit();
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
	public function posAction(){
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$db_global = new Application_Model_DbTable_DbGlobal();
		$session = $session_user = new Zend_Session_Namespace('auth');
		$user_id = $session_user-> fullname;
		$this->view->user = $user_id;
		$datas = $db->getAllProduct();
		//$saleorder = $db->getAllSaleOrderList();
		$this->view->getdata = $datas;
		//$this->view->saleorder = $saleorder;
		$form = new saleorder_Form_FrmSaleOrder();
		$this->view->frm =$form->FrmSaleOrder();
		
		$currency_setting = $db_global->getSettingByCode(1);
		$company_name = $db_global->getSettingByCode(4);
		$slogan = $db_global->getSettingByCode(5);
		$address = $db_global->getSettingByCode(2);
		$tel = $db_global->getSettingByCode(3);
		$qty_warning = $db_global->getSettingByCode(6);
		$this->view->qty_warning = $qty_warning;
		$this->view->currency_setting = $currency_setting;
		$this->view->company_name = $company_name;
		$this->view->slogan = $slogan;
		$this->view->address = $address;
		$this->view->tel = $tel;
	
		$this->view->FrmSaleOrderUpdate = $form->FrmSaleOrderUpdate();
	
		$itemRows = $db->getProductOption();
		$this->view->itemsOption = $itemRows;
	
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			if(isset($data["save"])){
				//print_r($data);exit();
				
					$db->addOrder($data);
				Application_Form_FrmMessage::redirectUrl('/index/pos');
			}
			elseif(isset($data["km"]) == 2){
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
	
	public function poseditAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$db_global = new Application_Model_DbTable_DbGlobal();
		$session = $session_user = new Zend_Session_Namespace('auth');
		$user_id = $session_user-> fullname;
		$this->view->user = $user_id;
		
		$qty_warning = $db_global->getSettingByCode(6);
		$this->view->qty_warning = $qty_warning;
		$row_sale_order = $db_global->getSaleOrderById($id);
		
		$row_sale_order_detail = $db_global->getSaleOrderDetail($id);
		$this->view->data_sale_order = $row_sale_order_detail;
		$form = new saleorder_Form_FrmSaleOrder();
		$this->view->frm =$form->FrmSaleOrder($row_sale_order);
		
		$currency_setting = $db_global->getSettingByCode(1);
		$company_name = $db_global->getSettingByCode(4);
		$slogan = $db_global->getSettingByCode(5);
		$address = $db_global->getSettingByCode(2);
		$tel = $db_global->getSettingByCode(3);
		
		$this->view->currency_setting = $currency_setting;
		$this->view->company_name = $company_name;
		$this->view->slogan = $slogan;
		$this->view->address = $address;
		$this->view->tel = $tel;
	
		$itemRows = $db->getProductOption();
		$this->view->itemsOption = $itemRows;
	
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			if(isset($data["save"])){
// 				print_r($data);exit();
				
					$db->editOrder($data,$id);
				Application_Form_FrmMessage::redirectUrl('/index/pos');
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
	public function logoutAction()
	{
		if($this->getRequest()->getParam('value')==1){
			$aut=Zend_Auth::getInstance();
			$aut->clearIdentity();
			$session_user=new Zend_Session_Namespace('auth');
			$session_user->unsetAll();
			$session_stock=new Zend_Session_Namespace('stock');
			$session_stock->unsetAll();
			$this->_redirect('/');
			exit();
		}
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
	
	public function ajaxAddOrderAction(){
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db->addOrder($data);
		}
	}
	public function ajaxEditOrderAction(){
		$db = new saleorder_Model_DbTable_DbSaleOrder();
		$id = $this->getRequest()->getParam('id');
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db->editOrder($data,$id);
		}
	}
	
	public function getProductPriceAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new saleorder_Model_DbTable_DbSaleOrder();
			$row = $db->getProductPrice($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	public function getProductByIdAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getProductById($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	public function getRateAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getRateByCurrencyId($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
	
	public function getCurrencyIconAction(){
		if($this->getRequest()->IsPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getCurrencyIcon($data);
			echo Zend_Json::encode($row);
			exit();
		}
	}
}

