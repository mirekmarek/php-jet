<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_Date as Jet_DataModel_Definition_Property_Date;
use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Property_Date extends Jet_DataModel_Definition_Property_Date implements DataModel_Definition_Property_Interface {
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ) : void
	{
	}


	/**
	 *
	 */
	public function showEditFormFields() : void
	{

	}


	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ) : ClassCreator_Class_Property
	{
		$class->addUse(
			new ClassCreator_UseClass('Jet', 'Data_DateTime')
		);

		$property = $this->createClassProperty_main( $class, 'Data_DateTime',  'DataModel::TYPE_DATE');

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class ) : void
	{

		$class->addUse( new ClassCreator_UseClass('Jet', 'Data_DateTime') );

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod('set'.$s_g_method_name);
		$setter->addParameter( 'value' )
			->setType('Data_DateTime|string');

		$setter->line( 1, 'if( $value===null ) {' );
		$setter->line( 2, '$this->'.$this->getName().' = null;' );
		$setter->line( 2, 'return;' );
		$setter->line( 1, '}' );
		$setter->line( 1, '' );
		$setter->line( 1, 'if( !( $value instanceof Data_DateTime ) ) {' );
		$setter->line( 2, '$value = new Data_DateTime( (string)$value );' );
		$setter->line( 1, '}' );
		$setter->line( 1, '' );
		$setter->line( 1, '$this->'.$this->getName().' = $value;' );


		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('Data_DateTime|null');
		$getter->line( 1, 'return $this->'.$this->getName().';');

	}
}