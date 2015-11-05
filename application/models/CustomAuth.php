<?php

class Application_Model_CustomAuth extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @var Zend_Auth
	 */
	protected $_auth;	
// 	protected $_rewrite_url = array(
// 									/* "rsvAcl/user/index"=>"/rsvAcl#ui-tabs-1",
// 									"rsvAcl/user-type/index"=>"/rsvAcl#ui-tabs-2",
// 									"rsvAcl/acl/index"=>"/rsvAcl#ui-tabs-3",
// 									"rsvAcl/user-access/index"=>"/rsvAcl#ui-tabs-4",
// 									"idt/cso/index"=>"/idt#ui-tabs-1"  */
// 								);
	
// 	protected $_exception_url = array(
// 										"default/index/index",
// 										"default/error/error",
// 										"default/index/changepassword",
// 										"default/index/logout",
// 										"exchange/index/check-rate" 
// 								);
 	
	public function __construct(Zend_Auth $auth)
	{		
		$this->_auth = $auth;
	}
 

		
	public function preDispatch(Zend_Controller_Request_Abstract $request)
 	{ 	
 		
 		$session_user=new Zend_Session_Namespace('auth');
 		
 		$module = $request->getModuleName();
 		$controller = $request->getControllerName();
 		$action = $request->getActionName();
 		$url = $module."/".$controller."/".$action;
 		$_url = "";
 		
 		

			
 	}
 	
 	protected function rewriteUrl($url){
 		if(!empty($this->_rewrite_url[$url])){
 			return $this->_rewrite_url[$url];
 		}
 		return "";
 	}
	
 	/**
 	 * redirect url to any where not base url (currencysmart.localserver/)
 	 * @param unknown_type $request
 	 * @param unknown_type $controller
 	 * @param unknown_type $action
 	 * @param unknown_type $module
 	 */
	protected function _redirect_homepage($request, $controller, $action, $module)
	{
		if ($request->getControllerName() == $controller
			&& $request->getActionName()  == $action
			&& $request->getModuleName()  == $module)
		{
			return TRUE;
		}
 
		$url = Zend_Controller_Front::getInstance()->getBaseUrl(); 
		$url .= '/'   . $action; 
		
 
	   return $this->_response->setRedirect($url);
	}
	
	/**
 	 * redirect url to any in base url (currencysmart.localserver)
 	 * @param unknown_type $request
 	 * @param unknown_type $controller
 	 * @param unknown_type $action
 	 * @param unknown_type $module
 	 */
	protected function _redirect($request, $controller, $action, $module)
	{
		if ($request->getControllerName() == $controller
			&& $request->getActionName()  == $action
			&& $request->getModuleName()  == $module)
		{
			return TRUE;
		}
 
		$url = Zend_Controller_Front::getInstance()->getBaseUrl(); 
		$url .= '/'   . $module
			 .=  ($controller === 'index')? '':'/'.$controller
			 .=  ($action === 'index')? '':'/'.$action;
	
			 
	   return $this->_response->setRedirect($url);
	}
	
	private function clearSession(){
		$req = Zend_Controller_Front::getInstance()->getRequest();
		$module = $req->getModuleName();
		$controller = $req->getControllerName();
		$action = $req->getActionName();
		$clr = array(
				array("s"=>"search_hotline", "m"=>"cs", "c"=>"cs-reports", "a"=>"index")
		);
	
		foreach ($clr as $key => $r) {
			if($module !== $r['m'] && $controller !== $r['c'] && $action !== $r['a']){
				$ses_search = new Zend_Session_Namespace($r['s']);
				$ses_search->unsetAll();
			}
		}
	}
	
}

