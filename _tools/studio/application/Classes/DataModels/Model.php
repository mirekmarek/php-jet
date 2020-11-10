<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel_Definition_Model;
use Jet\DataModel_Definition_Model_Main;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Tr;

class DataModels_Model extends DataModel_Definition_Model implements DataModels_Model_Interface {
	use DataModels_Model_Trait;

	const MODEL_TYPE_MAIN = 'Main';

	const MODEL_TYPE_RELATED_1TON = 'Related_1toN';
	const MODEL_TYPE_RELATED_1TO1 = 'Related_1to1';
	const MODEL_TYPE_RELATED_MTON = 'Related_MtoN';

	const ID_C_CLASS_AUTOINCREMENT = 'Jet\DataModel_IDController_AutoIncrement';
	const ID_C_CLASS_UNIQUE_STRING = 'Jet\DataModel_IDController_UniqueString';
	const ID_C_CLASS_NAME = 'Jet\DataModel_IDController_Name';
	const ID_C_CLASS_PASSIVE = 'Jet\DataModel_IDController_Passive';

	/**
	 * @var array
	 */
	protected static $types = [
		self::MODEL_TYPE_RELATED_1TON => 'Related DataModel 1toN',
		self::MODEL_TYPE_RELATED_1TO1 => 'Related DataModel 1to1',
		self::MODEL_TYPE_RELATED_MTON => 'Related DataModel MtoN',

	];

	/**
	 * @var array
	 */
	protected static $id_controllers = [
		self::ID_C_CLASS_AUTOINCREMENT => 'AutoIncrement',
		self::ID_C_CLASS_UNIQUE_STRING => 'UniqueString',
		self::ID_C_CLASS_NAME          => 'Name',
		self::ID_C_CLASS_PASSIVE       => 'Passive',
	];

	/**
	 * @var string
	 */
	protected $internal_type = DataModels_Model::MODEL_TYPE_MAIN;

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
	 * @param DataModels_Parser_Class $class
	 *
	 * @return DataModels_Model_Interface
	 */
	public static function createByParser( DataModels_Parser_Class $class )
	{
		$model = new static();

		$model->namespace_id = $class->getNamespace()->getId();
		$model->class_name = $class->getClassName();

		foreach( $class->getClassParameters() as $param ) {
			$param_name = $param->getName();

			switch( $param->getName() ) {
				case 'name':
					$model->model_name = $param->getValue();
					break;
				case 'database_table_name':
				case 'id_controller_class_name':
					$model->{$param_name} = $param->getValue();
					break;
				case 'key':
					static::createByParser_keys( $class, $model, $param );
					break;
				case 'relation':
					static::createByParser_relations( $class, $model, $param );
					break;
			}
		}

		static::createByParser_properties( $class, $model );

		return $model;
	}

	/**
	 * @return array
	 */
	public static function getDataModelTypes()
	{
		$types = [];

		foreach( static::$types as $type=>$label ) {
			$types[$type] = Tr::_($label);
		}

		return $types;
	}

	/**
	 * @return array
	 */
	public static function getIDControllers()
	{
		$id_controllers = [];

		foreach( static::$id_controllers as $class=> $label ) {
			$id_controllers[$class] = Tr::_($label);
		}

		return $id_controllers;

	}


	/**
	 * @param Form_Field_Input $field
	 * @param DataModels_Model_Interface $model
	 *
	 * @return bool
	 */
	public static function checkModelName( Form_Field_Input $field, DataModels_Model_Interface $model=null )
	{
		$name = $field->getValue();

		if(!$name)	{
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^[a-z0-9\_]{2,}$/i', $name)
		) {
			$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

			return false;
		}

		return true;

	}


	/**
	 * @param Form_Field_Input $field
	 * @param DataModels_Model_Interface $model
	 *
	 * @return bool
	 */
	public static function checkClassName( Form_Field_Input $field, DataModels_Model_Interface $model=null )
	{
		$name = $field->getValue();

		if(!$name)	{
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^[a-z0-9\_]{2,}$/i', $name) ||
			strpos($name, '__')!==false
		) {
			$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

			return false;
		}

		foreach( DataModels::getModels() as $m ) {

			if($m->getNamespaceId()!=Project::getCurrentNamespaceId()) {
				continue;
			}

			if($model && $model->getInternalId()==$m->getInternalId()) {
				continue;
			}

			if($m->getClassName()==$name) {
				$field->setCustomError(
					Tr::_('DataModel with the same class name already exists'),
					'data_model_class_is_not_unique'
				);

				return false;
			}
		}

		return true;

	}



	/**
	 * @param Form_Field_Input $field
	 * @param DataModels_Model_Interface $model
	 *
	 * @return bool
	 */
	public static function checkTableName( Form_Field_Input $field, DataModels_Model_Interface $model=null )
	{
		$name = $field->getValue();

		if(!$name)	{
			return true;
		}


		if(
			!preg_match('/^[a-z0-9\_]{2,}$/i', $name) ||
			strpos($name, '__')!==false
		) {
			$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

			return false;
		}

		$exists = false;

		foreach( DataModels::getModels() as $m ) {

			if($m->getNamespaceId()!=Project::getCurrentNamespaceId()) {
				continue;
			}

			if($model && $model->getInternalId()==$m->getInternalId()) {
				continue;
			}

			if(
				$m->getDatabaseTableName()==$name ||
				$m->getModelName()==$name
			) {
				$exists = true;
				break;
			}
		}

		if(
			$exists
		) {
			$field->setCustomError(
				Tr::_('DataModel with the same custom table name already exists'),
				'data_model_table_is_not_unique'
			);

			return false;
		}

		return true;

	}


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
				return DataModels_Model::checkModelName( $field );
			} );



			$class_name = new Form_Field_Input('class_name', 'Class name:', '');
			$class_name->setIsRequired(true);
			$class_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel class name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel class name format'
			]);
			$class_name->setValidator( function( Form_Field_Input $field ) {
				return DataModels_Model::checkClassName( $field );
			} );


			$fields = [
				$model_name,
				$class_name,
			];

			if( $current_model ) {
				$model_name->setDefaultValue( $current_model->getModelName().'_' );
				$class_name->setDefaultValue( $current_model->getClassName().'_' );


				$types = static::getDataModelTypes();

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
	 * @return bool|DataModels_Model_Interface
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

	/**
	 *
	 */
	public function checkIdProperties()
	{
	}


}