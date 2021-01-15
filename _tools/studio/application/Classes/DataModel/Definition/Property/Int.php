<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetStudio;

use Jet\DataModel_Definition_Property_Int as Jet_DataModel_Definition_Property_Int;
use Jet\Form_Field;
use Jet\Form_Field_Int;

/**
 *
 */
class DataModel_Definition_Property_Int extends Jet_DataModel_Definition_Property_Int implements DataModel_Definition_Property_Interface
{
	use DataModel_Definition_Property_Trait;


	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ): void
	{
		$default_value_field = new Form_Field_Int( 'default_value', 'Default value', $this->getDefaultValue() );
		$default_value_field->setCatcher( function( $value ) {
			$this->default_value = $value;
		} );


		$fields[$default_value_field->getName()] = $default_value_field;
	}


	/**
	 *
	 */
	public function showEditFormFields(): void
	{
		$form = $this->getEditForm();

		echo $form->field( 'default_value' );
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property
	{
		$property = $this->createClassProperty_main( $class, 'int', 'DataModel::TYPE_INT' );

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ): array
	{

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod( 'set' . $s_g_method_name );
		$setter->addParameter( 'value' )
			->setType( 'int' );
		$setter->line( 1, '$this->' . $this->getName() . ' = $value;' );


		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->setReturnType( 'int' );
		$getter->line( 1, 'return $this->' . $this->getName() . ';' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}