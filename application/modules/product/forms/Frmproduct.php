<?php 
class product_Form_Frmproduct extends Zend_Form
{
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmProduct($frm = null){
		$db = new Application_Model_DbTable_DbGlobal();
		$status = new Zend_Form_Element_Select('status');
			$_arr_status = array(1=>$this->tr->translate("ACTIVE"),0=>$this->tr->translate("DACTIVE"));
    		$status->setMultiOptions($_arr_status);
    		$status->setAttribs(array('class'=>'form-control validate[required]',));
    		
    	$pro_code = new Zend_Form_Element_Text("pro_code");
    	$pro_code->setAttribs(array('class'=>'form-control validate[required]','placeholder'=>$this->tr->translate("P_CODE"),"OnChange"=>"GetCatName(3)"));
    	$row_pro_code = $db->getProductCode();
    	$pro_code->setValue($row_pro_code);
    	
    	$measure = new Zend_Form_Element_Select("measure");
    	$measure->setAttribs(array('class'=>'select','style'=>'width:100%'));
    	
    	$row_measure = $db->getMeasure();
    	//$option_measure = array(0=>$this->tr->translate("CHOOSE_MEASURE"));
    	foreach ($row_measure as $rs){
    		$option_measure[$rs["id"]] = $rs["name_kh"];
    	}
    	$measure->setMultiOptions($option_measure);
    	
    	$stock_type = new Zend_Form_Element_Select("stock_type");
    	$stock_type->setAttribs(array('class'=>'','style'=>'width:100%'));
    	 
    	$option_st_type = array(1=>$this->tr->translate("CALCULATE_STOCK"));
    	$stock_type->setMultiOptions($option_st_type);
    	
    	$pro_size = new Zend_Form_Element_Text("pro_size");
    	$pro_size->setAttribs(array('class'=>'form-control validate[required]','placeholder'=>$this->tr->translate("SIZE")));
    	
    	$qty_unit = new Zend_Form_Element_Text("qty_unit");
    	$qty_unit->setAttribs(array('class'=>'form-control validate[required]','placeholder'=>$this->tr->translate("QTY_UNIT"),'readonly'=>true));
    	$qty_unit->setValue(1);
    	
    	$qty_per_unit = new Zend_Form_Element_Text("qty_per_unit");
		$qty_per_unit->setValue(1);
    	$qty_per_unit->setAttribs(array('class'=>'form-control validate[required]','placeholder'=>$this->tr->translate("QTY_PER_UNIT")));
    	
    	$label = new Zend_Form_Element_Text("label");
    	$label->setAttribs(array('class'=>'','placeholder'=>$this->tr->translate("LABEL")));
    	
		$cat_id = new Zend_Form_Element_Select("cat_id");
		$cat_id->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$category = $db->getAllProCategories();
			$option_category = array(0=>$this->tr->translate("CHOOSE_CATEGORY"));
			foreach ($category as $row_cat){
				$option_category[$row_cat["cat_id"]] = $row_cat["cat_name_km"]." - ".$row_cat["cat_name_en"];
		}
		$cat_id->setMultiOptions($option_category);  
		
		$brand = new Zend_Form_Element_Select("brand");
		$brand->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$row_brand = $db->getAllBrand();
		$option_brand= array(0=>$this->tr->translate("CHOOSE_BRAND"));
		foreach ($row_brand as $rs){
			$option_brand[$rs["brand_id"]] = $rs["name_kh"]." - ".$rs["name_en"];
		}
		$brand->setMultiOptions($option_brand);
		
		$name_en = new Zend_Form_Element_Text("name_en");
		$name_en->setAttribs(array('class'=>'validate[required]','placeholder'=>$this->tr->translate("NAME_EN"),"OnChange"=>""));
		
		$currency= new Zend_Form_Element_Select("currency");
		$currency->setAttribs(array('class'=>'form-control validate[required]','readonly'=>'readonly'));
		$currencys = $db->getAllCurrency();
		$option_currency = array(0=>$this->tr->translate("CHOOSE_CURRENCY"));
		foreach ($currencys as $row_currency){
			$option_currency[$row_currency["cu_id"]] = $row_currency["cu_name_km"]." - ".$row_currency["cu_name_en"];
		}
		$currency->setMultiOptions($option_currency);
		$currency->setValue(1);
		
		$price = new Zend_Form_Element_Text("price");
		$price->setAttribs(array('class'=>'validate[required]','placeholder'=>$this->tr->translate("PRICE")));
		
		$description = new Zend_Form_Element_Textarea("description");
		$description->setAttribs(array('class'=>'','placeholder'=>$this->tr->translate("DESCRIPTION")));
		
		$name_km = new Zend_Form_Element_Text("name_km");
		$name_km->setAttribs(array('class'=>'validate[required]','placeholder'=>$this->tr->translate("NAME_KM"),"OnChange"=>""));
		
		$icon = new Zend_Form_Element_File("icon");
		
		$unit_price = new Zend_Form_Element_Text("unit_price");
		$unit_price->setAttribs(array('class'=>'validate[required]','placeholder'=>$this->tr->translate("UNIT_PRICE"),));
		$unit_price->setValue(0);
		$qty_price = new Zend_Form_Element_Text("qty_price");
		$qty_price->setAttribs(array('class'=>'validate[required]','placeholder'=>$this->tr->translate("PRICE_PER_QTY"),));
		$qty_price->setValue(0);
		$qty_onhand = new Zend_Form_Element_Text("qty_onhand");
		$qty_onhand->setAttribs(array('placeholder'=>$this->tr->translate("QTY"),));
		$qty_onhand->setValue(0);
		
		
		$qty_unit_onhand = new Zend_Form_Element_Text("qty_unit_onhand");
		$qty_unit_onhand->setAttribs(array('placeholder'=>$this->tr->translate("QTY"),));
		$qty_unit_onhand->setValue(0);
		
		
		
		$this->addElements(array($qty_unit_onhand,$description,$price,$icon,$status,$name_en,$name_km,$cat_id,$pro_code,$currency,$brand,$qty_per_unit,$qty_unit,$label,$stock_type,$measure,$pro_size,$unit_price,$qty_onhand,$qty_price));
		if($frm !=""){
			$cat_id->setValue($frm["cate_id"]);
			$name_en->setValue($frm["name_en"]);
			$name_km->setValue($frm["name_kh"]);
			$status->setValue($frm["status"]);
			$currency->setValue($frm["cu_id"]);
			$brand->setValue($frm["brand_id"]);
			$label->setValue($frm["label"]);
			$pro_code->setValue($frm["item_code"]);
			$measure->setValue($frm["measure_id"]);
			$stock_type->setValue($frm["stock_type"]);
			//$qty_->setValue($frm["qty_onhand"]);
			$qty_unit->setValue(1);
			$qty_per_unit->setValue($frm["qty_perunit"]);
			//$qty_unit_onhand->setValue($frm[""]);
			$pro_size->setValue($frm["item_size"]);
			$unit_price->setValue($frm["unit_sale_price"]);
			$qty_price->setValue($frm["price_per_qty"]);
			$description->setValue($frm["description"]);
		}
		return $this;
	}
	
	public function frmSearch($data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Application_Model_DbTable_DbGlobal();
		$search_text = new Zend_Form_Element("txt_search");
		$search_text->setValue($request->getParam("txt_search"));
		
		$cat_id = new Zend_Form_Element_Select("cat_id");
		$cat_id->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$category = $db->getAllProCategories();
			$option_category = array(0=>$this->tr->translate("CHOOSE_CATEGORY"));
			foreach ($category as $row_cat){
				$option_category[$row_cat["cat_id"]] = $row_cat["cat_name_km"]." - ".$row_cat["cat_name_en"];
		}
		$cat_id->setMultiOptions($option_category); 
		$cat_id->setValue($request->getParam("cat_id"));		
		
		$brand = new Zend_Form_Element_Select("brand");
		$brand->setAttribs(array('class'=>'select','style'=>'width:100%'));
		$row_brand = $db->getAllBrand();
		$option_brand= array(0=>$this->tr->translate("CHOOSE_BRAND"));
		foreach ($row_brand as $rs){
			$option_brand[$rs["brand_id"]] = $rs["name_kh"]." - ".$rs["name_en"];
		}
		$brand->setMultiOptions($option_brand);
		
		$brand->setValue($request->getParam("brand"));
		
		if($data!=null){
			
			
			
		}
		
		return $this->addElements(array($search_text,$cat_id,$brand));
	}
}
?>
