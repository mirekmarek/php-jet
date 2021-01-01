<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_CustomData as Jet_DataModel_Definition_Property_CustomData;
use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Property_CustomData extends Jet_DataModel_Definition_Property_CustomData implements DataModel_Definition_Property_Interface {
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ) : void
	{
		unset($fields['is_id']);
		unset($fields['is_key']);
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

		$property = $this->createClassProperty_main( $class, 'mixed',  'DataModel::TYPE_CUSTOM_DATA' );

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
			->setType('mixed');
		$setter->line( 1, '//TODO: implement ...' );


		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('mixed');
		$getter->line( 1, '//TODO: implement ...');


		return ['set'.$s_g_method_name, 'get'.$s_g_method_name];
	}

}