<?php

class Application_Model_DbSingIn extends Zend_Db_Table_Abstract
{
	protected $_name = 'tb_user';

	public function AddSigninMember($data){
		$db = $this->getAdapter();
		$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
		$acc_no = $db->fetchOne($sql);
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre = "";
		for($i = $acc_no;$i<5;$i++){
			$pre.='0';
		}
		$codeauto =  "WSF-".$pre.$new_acc_no;
		try {
		  $arraydata = (array(
		  		'id_code'=>$codeauto,
		  		'name'=> $data['fullname'],
		  		'user_name'=> $data['username'],
		  		'user_type'=> 3,
		  		'phone'=> $data['phone'],
		  		'email'=> $data['email'],
		  		'password'=> md5($data['password']),
		  		'code_active'=> md5($data['email']),
		  		'address'=> $data['address'],
		  		'is_active'=> 0 ,
		  		'status'=> 1,
		  		'created_date'=> new Zend_Date(),
		  ));
		  $this->insert($arraydata);
		}catch (Exception $e){
			echo $e->getMessage();
			exit();
		}
	}
}

