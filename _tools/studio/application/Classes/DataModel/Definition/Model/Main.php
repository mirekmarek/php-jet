<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Main as Jet_DataModel_Definition_Model_Main;
use Jet\DataModel_Exception;
use Jet\Form;
use Jet\Form_Field_Input;

/**
 */
class DataModel_Definition_Model_Main extends Jet_DataModel_Definition_Model_Main implements DataModel_Definition_Model_Interface {

	use DataModel_Definition_Model_Trait;


	/**
	 * @var string
	 */
	protected $internal_type = DataModels::MODEL_TYPE_MAIN;

	/**
	 *
	 * @var string
	 */
	protected $id_controller_class_name = 'Jet\DataModel_IDController_AutoIncrement';



	/**
	 * @var Form
	 */
	protected static $create_form;




	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {
			$fields = DataModel_Definition_Model_Trait::getCreateForm_mainFields( 'Main' );

			$id_property_name = new Form_Field_Input('id_property_name', 'ID property name:', 'id');
			$id_property_name->setIsRequired(true);
			$id_property_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter property name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format'
			]);
			$id_property_name->setValidator( function( Form_Field_Input $field ) {
				return DataModel_Definition_Property::checkPropertyName( $field );
			} );

			$fields[] = $id_property_name;

			static::$create_form = new Form('create_data_model_form', $fields );


			static::$create_form->setAction( DataModels::getActionUrl('model/add') );

		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Model_Interface
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$model = new DataModel_Definition_Model_Main();

		$namespace = $form->field('namespace')->getValue();
		$class_name = $form->field('class_name')->getValue();
		$script_path = $form->field('script_path')->getValue();
		$model_name = $form->field('model_name')->getValue();
		$id_controller_class = $form->field('id_controller_class')->getValue();
		$id_property_name = $form->field('id_property_name')->getValue();

		$class = new DataModel_Class(
			$script_path,
			$namespace,
			$class_name
		);

		$class->setIsNew( true );

		$model->setClass( $class );

		$model->setModelName( $model_name );
		$model->setIDControllerClassName(  $id_controller_class);


		switch($id_controller_class) {
			case 'Jet\DataModel_IDController_AutoIncrement':
				$id_property = new DataModel_Definition_Property_IdAutoIncrement( $class->getFullClassName(), $id_property_name);
				$id_controller_option = 'id_property_name';
				break;
			case 'Jet\DataModel_IDController_UniqueString':
			case 'Jet\DataModel_IDController_Name':
				$id_property = new DataModel_Definition_Property_Id( $class->getFullClassName(), $id_property_name);
				$id_controller_option = 'id_property_name';
				break;
			case 'Jet\DataModel_IDController_Passive':
				$id_property = new DataModel_Definition_Property_Id( $class->getFullClassName(), $id_property_name);
				$id_controller_option = '';
				break;
			default:
				throw new DataModel_Exception('Unknown ID controller class '.$id_controller_class);
		}

		$id_property->setIsId(true);
		$model->addProperty($id_property);

		if($id_controller_option) {
			$model->getIDController()->setOptions([
				$id_controller_option => $id_property_name
			]);
		}


		return $model;
	}

}