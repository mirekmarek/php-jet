<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_IdAutoIncrement;

use Jet\Form_Field;

class DataModels_Property_IdAutoIncrement extends DataModel_Definition_Property_IdAutoIncrement implements DataModels_Property_Interface {

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

		$property = $this->createClassProperty_main( $class, 'int',  'DataModel::TYPE_ID_AUTOINCREMENT');

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class )
	{
		$s_g_method_name = $this->getSetterGetterMethodName();

		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('int');
		$getter->line( 1, 'return $this->'.$this->getName().';');

	}


}