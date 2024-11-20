<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Forms;

use JetStudio\ClassMetaInfo;
use Jet\Form_Definition_Interface;
use JetStudio\JetStudio;

class FormClass extends ClassMetaInfo
{
	
	/**
	 * @return FormClass_Property[]
	 */
	public function getProperties() : array
	{
		$properties = [];
		
		/**
		 * @var Form_Definition_Interface $i
		 */
		$class = $this->getFullClassName();
		
		$i = new $class();
		
		$form_definition = $i->getFormFieldsDefinition();
		
		foreach($this->reflection->getProperties() as $property) {
			if($property->getName()[0]=='_') {
				continue;
			}
			
			if($property->isStatic()) {
				continue;
			}
			
			$p = new FormClass_Property( $this, $property->getName(), $property, $form_definition[$property->getName()]??null );
			
			$properties[$p->getName()] = $p;
		}
		
		return $properties;
	}
	
	public function generateViewScript( string $form_variable_name='form', string $form_view_property_name='form', bool $subform_is_localized=true ) : string
	{
		
		$view = JetStudio::getModule_Forms()->getView();
		$view->setVar('class', $this);
		$view->setVar('form_variable_name', $form_variable_name);
		$view->setVar('form_view_property_name', $form_view_property_name);
		$view->setVar('subform_is_localized', $subform_is_localized);
		
		return $view->render('view_script_generator');
	}
	
	public static function get( string $class_name ): ?static
	{
		return FormClass::get( $class_name );
	}
}