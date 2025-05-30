<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Jet\DataModel_Definition_Property_DateTime as Jet_DataModel_Definition_Property_DateTime;
use Jet\Form_Field;
use JetStudio\ClassCreator_Class;
use JetStudio\ClassCreator_Config;
use JetStudio\ClassCreator_UseClass;
use JetStudio\ClassCreator_Class_Property;

/**
 *
 */
class DataModel_Definition_Property_DateTime extends Jet_DataModel_Definition_Property_DateTime implements DataModel_Definition_Property_Interface
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
			new ClassCreator_UseClass( 'Jet', 'Data_DateTime' )
		);

		$property = $this->createClassProperty_main( $class, 'Data_DateTime', 'DataModel::TYPE_DATE_TIME' );
		
		if(ClassCreator_Config::getPreferPropertyHooks()) {
			$class->addUse( new ClassCreator_UseClass( 'Jet', 'Data_DateTime' ) );
			
			$property->createStdHook(
				'Data_DateTime|string|null',
				'$this->' . $this->getName() . ' = Data_DateTime::catchDateTime( $value );'
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

		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Data_DateTime' ) );

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod( 'set' . $s_g_method_name );
		$setter->addParameter( 'value' )->setType( 'Data_DateTime|string|null' );
		$setter->line( 1, '$this->' . $this->getName() . ' = Data_DateTime::catchDateTime( $value );' );


		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->setReturnType( 'Data_DateTime|null' );
		$getter->line( 1, 'return $this->' . $this->getName() . ';' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}