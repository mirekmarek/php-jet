<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_IdAutoIncrement as Jet_DataModel_Definition_Property_IdAutoIncrement;
use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Property_IdAutoIncrement extends Jet_DataModel_Definition_Property_IdAutoIncrement implements DataModel_Definition_Property_Interface {
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

		$property = $this->createClassProperty_main( $class, 'int',  'DataModel::TYPE_ID_AUTOINCREMENT');

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

		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('int');
		$getter->line( 1, 'return $this->'.$this->getName().';');

		return ['get'.$s_g_method_name];
	}

}