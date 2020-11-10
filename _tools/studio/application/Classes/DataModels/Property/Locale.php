<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_Locale;

use Jet\Form_Field;
use Jet\Locale;

class DataModels_Property_Locale extends DataModel_Definition_Property_Locale implements DataModels_Property_Interface {

	use DataModels_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( &$fields )
	{
	}


	/**
	 *
	 */
	public function showEditFormFields()
	{
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class )
	{

		$annotations = [];

		$class->addUse(
			new ClassCreator_UseClass('Jet', 'Locale')
		);

		$property = $this->createClassProperty_main( $class, 'Locale',  'DataModel::TYPE_LOCALE', $annotations);

		return $property;

	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class )
	{

		$class->addUse( new ClassCreator_UseClass('Jet', 'Locale') );

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod('set'.$s_g_method_name);
		$setter->addParameter( 'value' )
			->setType('Locale|string');
		$setter->line( 1, 'if( !( $value instanceof Locale ) ) {' );
		$setter->line( 2, '$value = new Locale( (string)$value );' );
		$setter->line( 1, '}' );
		$setter->line( 1, '' );
		$setter->line( 1, '$this->'.$this->getName().' = $value;' );


		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('Locale');
		$getter->line( 1, 'return $this->'.$this->getName().';');
	}

}