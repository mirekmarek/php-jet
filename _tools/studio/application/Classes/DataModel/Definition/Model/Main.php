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
			$fields = DataModel_Definition_Model_Trait::getCreateForm_mainFields();

			static::$create_form = new Form('create_data_model_form_Main', $fields );


			static::$create_form->setAction( DataModels::getActionUrl('model/add/Main') );

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