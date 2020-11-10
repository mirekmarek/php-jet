<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Property_CustomData;
use Jet\Form_Field;

class DataModels_Property_CustomData extends DataModel_Definition_Property_CustomData implements DataModels_Property_Interface {

	use DataModels_Property_Trait;



	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( &$fields )
	{
		unset($fields['is_id']);
		unset($fields['is_key']);
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

		$property = $this->createClassProperty_main( $class, 'mixed',  'DataModel::TYPE_CUSTOM_DATA', $annotations);

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 */
	public function createClassMethods( ClassCreator_Class $class )
	{
		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod('set'.$s_g_method_name);
		$setter->addParameter( 'value' )
			->setType('mixed');
		$setter->line( 1, '//TODO: implement ...' );


		$getter = $class->createMethod('get'.$s_g_method_name);
		$getter->setReturnType('mixed');
		$getter->line( 1, '//TODO: implement ...');


	}

}