<?php

namespace JetStudio;

use Jet\DataModel_Definition_Property_Bool as Jet_DataModel_Definition_Property_Bool;
use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Property_Bool extends Jet_DataModel_Definition_Property_Bool implements DataModel_Definition_Property_Interface
{
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ): void
	{
	}


	/**
	 *
	 */
	public function showEditFormFields(): void
	{
	}

	/**
	 * @return bool
	 */
	public function getDefaultValue() : bool
	{
		return false;
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property
	{

		return $this->createClassProperty_main( $class, 'bool', 'DataModel::TYPE_BOOL' );

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
			->setType( 'bool' );
		$setter->line( 1, '$this->' . $this->getName() . ' = (bool)$value;' );


		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->setReturnType( 'bool' );
		$getter->line( 1, 'return $this->' . $this->getName() . ';' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}