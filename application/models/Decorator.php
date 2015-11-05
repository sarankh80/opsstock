<?php

class Application_Model_Decorator
{
	public static function removeAllDecorator($form)
	{
		$elements=$form->getElements();
		foreach($elements as $element){
			$element->removeDecorator('HtmlTag');
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label');		
			$element->removeDecorator('Errors');	
		}
		
	}	
	public static function removeAllDecoratorExceptError($form)
	{
		$elements=$form->getElements();
		foreach($elements as $element){
			$element->removeDecorator('HtmlTag');
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label');		
		}
		
	}
}

