<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Jet\DataModel_Definition_Property_Locale as Jet_DataModel_Definition_Property_Locale;
use Jet\Form_Field;
use JetStudio\ClassCreator_Class;
use JetStudio\ClassCreator_Config;
use JetStudio\ClassCreator_UseClass;
use JetStudio\ClassCreator_Class_Property;

/**
 *
 */
class DataModel_Definition_Property_Locale extends Jet_DataModel_Definition_Property_Locale implements DataModel_Definition_Property_Interface
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
	public function getDefaultValue() : mixed
	{
		return null;
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property
	{

		$class->addUse(
			new ClassCreator_UseClass( 'Jet', 'Locale' )
		);

		$property = $this->createClassProperty_main( $class, 'Locale', 'DataModel::TYPE_LOCALE' );
		
		if(ClassCreator_Config::getPreferPropertyHooks()) {
			$class->addUse( new ClassCreator_UseClass( 'Jet', 'Locale' ) );
			
			$property->createStdHook(
				'null|Locale|string',
				'$this->' . $this->getName() . ' = ($value instanceof Locale) ? $value : new Locale( $value );',
			);
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

		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Locale' ) );

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod( 'set' . $s_g_method_name );
		$setter->addParameter( 'value' )
			->setType( 'Locale|string' );
		$setter->line( 1, '$this->' . $this->getName() . ' = ($value instanceof Locale) ? $value : new Locale( $value );' );


		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->setReturnType( 'Locale' );
		$getter->line( 1, 'return $this->' . $this->getName() . ';' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}