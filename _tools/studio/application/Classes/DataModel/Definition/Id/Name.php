<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\Form_Field_Input;
use Jet\Tr;
use Jet\Form_Field;

/**
 *
 */
class DataModel_Definition_Id_Name extends DataModel_Definition_Id_Abstract {

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_IdDefinition( ClassCreator_Class $class ) : void
	{
		parent::createClass_IdDefinition( $class );

		$id_property_name = $this->getSelectedIdPropertyName( DataModel::TYPE_ID );
		$get_name_method_name = $this->model->getIDControllerOption('get_name_method_name', 'getName');

		if(!$id_property_name) {
			$class->addError( Tr::_('There is not property which is DataModel::TYPE_ID type and is marked as ID', []) );
		} else {
			$id_controller_options = [
				'id_property_name' => $id_property_name,
				'get_name_method_name' => $get_name_method_name
			];

			$class->setAttribute( 'DataModel_Definition', 'id_controller_options', $id_controller_options );

		}
	}




	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ) : array
	{
		$get_name_method_name = $this->model->getIDControllerOption('get_name_method_name', 'getName');

		if(!$class->hasMethod( $get_name_method_name )) {
			$setter = $class->createMethod( $get_name_method_name );
			$setter->line( 1, '//TODO: implement ...' );
		}

		return [$get_name_method_name];
	}

	/**
	 * @return array
	 */
	public function getOptionsList() : array
	{
		return [
			'id_property_name',
			'get_name_method_name'
		];
	}

	/**
	 *
	 * @return Form_Field[]
	 */
	public function getOptionsFormFields() : array
	{
		$id_property_name = $this->getOptionsFormField_idProperty( DataModel::TYPE_ID );

		$get_name_method_name = new Form_Field_Input('get_name_method_name', 'Name getter:', $this->model->getIDControllerOption('get_name_method_name', 'getName'));
		$get_name_method_name->setIsRequired(true);
		$get_name_method_name->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter name getter'
		]);
		$get_name_method_name->setCatcher( function($value) {
			$this->model->setIDControllerOption('get_name_method_name', $value);
		} );


		return [
			$id_property_name,
			$get_name_method_name
		];
	}



}