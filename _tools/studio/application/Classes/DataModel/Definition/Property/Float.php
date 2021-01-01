<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_Float as Jet_DataModel_Definition_Property_Float;
use Jet\Form_Field;
use Jet\Form_Field_Float;

/**
 *
 */
class DataModel_Definition_Property_Float extends Jet_DataModel_Definition_Property_Float implements DataModel_Definition_Property_Interface {
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ) : void
	{
		$default_value_field = new Form_Field_Float('default_value', 'Default value', $this->getDefaultValue());
		$default_value_field->setCatcher( function( $value ) {
			$this->default_value = $value;
		} );


		$fields[$default_value_field->getName()] = $default_value_field;
	}


	/**
	 *
	 */
	public function showEditFormFields() : void
	{
		$form = $this->getEditForm();

		echo $form->field('default_value');
	}



	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ) : ClassCreator_Class_Property
	{

		$property = $this->createClassProperty_main( $class, 'float',  'DataModel::TYPE_FLOAT' );

		return $property;
	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ) : array
	{

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod('set'.$s_g_method_name);
		$setter->addParameter( 'value' )
			->setType('float');
		$setter->line( 1, '$this->'.$this->getName().' = $value;' );


		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('float');
		$getter->line( 1, 'return $this->'.$this->getName().';');

		return ['set'.$s_g_method_name, 'get'.$s_g_method_name];
	}

}