<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Definition_Model_Related_MtoN as Jet_DataModel_Definition_Model_Related_MtoN;
use Jet\DataModel_Exception;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;
use Jet\Tr;

/**
 */
class DataModel_Definition_Model_Related_MtoN extends Jet_DataModel_Definition_Model_Related_MtoN implements DataModel_Definition_Model_Related_Interface {

	use DataModel_Definition_Model_Related_Trait;


	/**
	 * @return bool
	 */
	public function canHaveRelated()
	{
		return false;
	}

	/**
	 * @var string
	 */
	protected $internal_type = DataModels::MODEL_TYPE_RELATED_MTON;

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
			$fields = DataModel_Definition_Model_Trait::getCreateForm_mainFields( 'MtoN' );

			$current_class = DataModels::getCurrentClass();
			$current_model = DataModels::getCurrentModel();

			if( $current_class ) {
				$fields['model_name']->setDefaultValue( $current_model->getModelName().'_' );
				$fields['class_name']->setDefaultValue( $current_class->getClassName().'_' );
			}

			static::$create_form = new Form('create_data_model_form', $fields );


			static::$create_form->setAction( DataModels::getActionUrl('model/add') );
		}

		return static::$create_form;
	}


	/**
	 * @return Form
	 */
	public function getEditForm()
	{

		if(!$this->__edit_form) {

			$model_name_field = new Form_Field_Input('model_name', 'Model name:', $this->model_name);
			$model_name_field->setIsRequired(true);
			$model_name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel name'
			]);
			$model_name_field->setCatcher( function( $value ) {
				$this->model_name =  $value;
			} );





			$database_table_name_field = new Form_Field_Input('database_table_name', 'Table name:', $this->database_table_name);
			$database_table_name_field->setCatcher( function( $value ) {
				$this->setDatabaseTableName( $value );
			} );

			$n_model_field = new Form_Field_Select('N_model_class_name', 'N DataModel:', $this->N_model_class_name );
			$n_model_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select N DataModel'
			]);

			$n_classes = [
				'' => ''
			];
			foreach( DataModels::getClasses() as $class ) {
				$model = $class->getDefinition();
				if(
					!$model instanceof DataModel_Definition_Model_Main ||
					$model->getClassName()==$this->getRelevantParentModel()->getClassName()
				) {
					continue;
				}

				$n_classes[$model->getClassName()] = $model->getModelName().' ('.$model->getClassName().')';
			}
			$n_model_field->setSelectOptions( $n_classes );
			$n_model_field->setCatcher( function( $value ) {
				$this->setNModel( $value );
			} );

			$default_order_by_field = new Form_Field_Hidden( 'default_order_by', '', implode('|', $this->getDefaultOrderBy()) );
			$default_order_by_field->setCatcher( function( $value ) {
				if(!$value) {
					$value = [];
				} else {
					$value = explode('|', $value);
				}
				$this->setDefaultOrderBy( $value );
			} );


			$fields = [
				$model_name_field,
				$database_table_name_field,
				$n_model_field,
				$default_order_by_field
			];

			$this->__edit_form = new Form('edit_model_form', $fields );
			$this->__edit_form->setAction( DataModels::getActionUrl('model/edit') );

		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();

		return true;
	}

	/**
	 * @return string
	 */
	public function getNModelClassName()
	{
		return $this->N_model_class_name;
	}

	/**
	 * @param string $N_model_class_name
	 */
	public function setNModelClassName( $N_model_class_name )
	{
		$this->N_model_class_name = $N_model_class_name;
	}

	/**
	 *
	 * @param string $n_model_id
	 */
	public function setNModel( $n_model_id )
	{
		$this->N_model_class_name = $n_model_id;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN|null
	 */
	public function getNModel()
	{
		if(!$this->N_model_class_name) {
			return null;
		}

		return DataModels::getClass($this->N_model_class_name)->getDefinition();
	}

	/**
	 * @return array
	 */
	public function getOrderByOptions()
	{
		$res = [];

		foreach( $this->getProperties() as $property ) {
			if(
				$property->getRelatedToClassName() ||
				$property->getType()==DataModel::TYPE_CUSTOM_DATA
			) {
				continue;
			}

			$res[$this->getClassName().'.'.$property->getName()] = $this->getModelName().'.'.$property->getName();
		}

		$n = $this->getNModel();
		if($n) {
			foreach( $n->getProperties() as $property ) {
				if(
					$property->getDataModelClassName() ||
					$property->getType()==DataModel::TYPE_CUSTOM_DATA
				) {
					continue;
				}

				$res[$n->getClassName().'.'.$property->getName()] = $n->getModelName().'.'.$property->getName();
			}
		}


		return $res;
	}


	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{

		$class = new ClassCreator_Class();

		$class->setName( $this->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Related_MtoN') );

		$class->setExtends( $this->createClass_getExtends($class, 'DataModel_Related_MtoN') );

		return $class;
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class )
	{

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'name', var_export($this->getModelName(), true)) )
		);

		if($this->getDatabaseTableName()) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getDatabaseTableName(), true)) )
			);
		} else {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'database_table_name', var_export($this->getModelName(), true)) )
			);
		}

		$parent_id = $this->getParentModelClassName();
		if(!$parent_id) {
			$parent_id = $this->getMainModelClassName();
		}

		$parent_class = DataModels::getClass( $parent_id );
		if(!$parent_class) {
			$class->addError( Tr::_('Fatal: unknown parent class!') );

			return;
		}

		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'parent_model_class_name', var_export($parent_class->getClassName(), true) ))
		);


		$N_model_id = $this->getNModelClassName();
		$N_model = DataModels::getClass( $N_model_id );
		if(!$N_model) {
			$class->addError('Unable to get N DataModel definition (N model ID: '.$N_model_id.')');
			return;
		}

		$N_model_class_name = $N_model->getClassName();


		if($N_model->getNamespace()!=DataModels::getCurrentClass()->getNamespace()) {
			$class->addUse(
				new ClassCreator_UseClass($N_model->getNamespace(), $N_model->getClassName())
			);
		}


		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'N_model_class_name', var_export($N_model_class_name, true)) )
		);


		$order_by = [];
		foreach( $this->getDefaultOrderBy() as $ob ) {
			$order_by[] = var_export($ob, true);
		}

		if($order_by) {
			$class->addAnnotation(
				(new ClassCreator_Annotation('JetDataModel', 'default_order_by', $order_by))
			);
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_ID( ClassCreator_Class $class )
	{
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

		$model = new DataModel_Definition_Model_Related_1toN();
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

		//TODO:

		return $model;
	}

}