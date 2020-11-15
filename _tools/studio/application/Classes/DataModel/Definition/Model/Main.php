<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model_Main as Jet_DataModel_Definition_Model_Main;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;

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
			$current_model = DataModels::getCurrentModel();

			$model_name = new Form_Field_Input('model_name', 'Model name:', '');
			$model_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel name format'
			]);
			$model_name->setIsRequired(true);
			$model_name->setValidator( function( Form_Field_Input $field ) {
				return DataModels::checkModelName( $field );
			} );



			$class_name = new Form_Field_Input('class_name', 'Class name:', '');
			$class_name->setIsRequired(true);
			$class_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel class name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel class name format'
			]);
			$class_name->setValidator( function( Form_Field_Input $field ) {
				return DataModels::checkClassName( $field );
			} );


			$fields = [
				$model_name,
				$class_name,
			];

			if( $current_model ) {
				$model_name->setDefaultValue( $current_model->getModelName().'_' );
				$class_name->setDefaultValue( $current_model->getClassName().'_' );


				$types = DataModels::getDataModelTypes();

				$type = new Form_Field_Select('type', 'Type:', '');
				$type->setSelectOptions( $types );
				$type->setIsRequired( true );
				$type->setErrorMessages([
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please select DataModel type',
					Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select DataModel type'
				]);

				$fields[] = $type;
			}

			static::$create_form = new Form('create_data_model_form', $fields );


			static::$create_form->setAction( DataModels::getActionUrl('add') );

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



		$model_name = $form->field('model_name')->getValue();
		$class_name = $form->field('class_name')->getValue();

		if( ($current_model=DataModels::getCurrentModel()) ) {
			$type = $form->field('type')->getValue();

			$creator = 'createModel_'.$type;

			$new_model = DataModels::{$creator}(
				$model_name,
				$class_name,
				$current_model
			);


		} else {
			$new_model = DataModels::createModel(
				$model_name,
				$class_name
			);

		}

		return $new_model;
	}

}