<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Jet\DataModel_Definition_Property_Float as Jet_DataModel_Definition_Property_Float;
use Jet\Form_Field;
use JetStudio\ClassCreator_Class;
use JetStudio\ClassCreator_Class_Property;
use JetStudio\ClassCreator_Config;

/**
 *
 */
class DataModel_Definition_Property_Float extends Jet_DataModel_Definition_Property_Float implements DataModel_Definition_Property_Interface
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
	 * @return mixed
	 */
	public function getDefaultValue() : float
	{
		return 0.0;
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property
	{
		$property = $this->createClassProperty_main( $class, 'float', 'DataModel::TYPE_FLOAT' );
		
		if(ClassCreator_Config::getPreferPropertyHooks()) {
			$property->createStdHook('float');
		}

		return $property;
	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ): array
	{
		if(ClassCreator_Config::getPreferPropertyHooks()) {
			return [];
		}
		
		$s_g_method_name = $this->getSetterGetterMethodName();
		
		$setter = $class->createMethod( 'set' . $s_g_method_name );
		$setter->addParameter( 'value' )
			->setType( 'float' );
		$setter->line( 1, '$this->' . $this->getName() . ' = $value;' );


		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->setReturnType( 'float' );
		$getter->line( 1, 'return $this->' . $this->getName() . ';' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}