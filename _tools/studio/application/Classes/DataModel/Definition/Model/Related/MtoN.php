<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use http\Exception\BadQueryStringException;
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
	 * @var Form
	 */
	protected static $select_N_form;

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

		$class->setNamespace( $this->_class->getNamespace() );
		$class->setName( $this->_class->getClassName() );

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


		$N_model_class_name = $this->getNModelClassName();

		$N_class = DataModels::getClass( $N_model_class_name );
		if(!$N_class) {
			$class->addError('Unable to get N DataModel definition (N model class: '.$N_model_class_name.')');
			return;
		}



		if($N_class->getNamespace()!=DataModels::getCurrentClass()->getNamespace()) {
			$class->addUse(
				new ClassCreator_UseClass($N_class->getNamespace(), $N_class->getClassName())
			);
		}


		$class->addAnnotation(
			(new ClassCreator_Annotation('JetDataModel', 'N_model_class_name', var_export($N_class->getClassName(), true)) )
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
	 * @return Form
	 */
	public static function getSelectNForm()
	{
		if(!static::$select_N_form) {
			$cc = DataModels::getCurrentClass();

			$list = [
				'' => ''
			];

			foreach(DataModels::getClasses() as $class ) {
				if(
					$class->getFullClassName()==$cc->getFullClassName() ||
					$class->isDescendantOf($cc) ||
					$class->getDefinition() instanceof DataModel_Definition_Model_Related_MtoN
				) {
					continue;
				}

				$list[$class->getFullClassName()] = $class->getFullClassName();
			}

			$N_models = new Form_Field_Select('N_model', Tr::_('N model:'), '' );
			$N_models->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => Tr::_('Please select N model'),
				Form_Field_Select::ERROR_CODE_EMPTY => Tr::_('Please select N model')
			]);

			$N_models->setSelectOptions($list);

			static::$select_N_form = new Form('select_N_form', [
				$N_models
			] );
			static::$select_N_form->setDoNotTranslateTexts(true);
			static::$select_N_form->setAction(DataModels::getActionUrl('model/add/MtoN_generate_form'));
		}

		return static::$select_N_form;
	}

	/**
	 * @return false|string
	 */
	public static function catchSelectNForm()
	{
		$form = static::getSelectNForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		return $form->field('N_model')->getValue();
	}

	/**
	 * @param string $N_class_name
	 *
	 * @return Form
	 */
	public static function getCreateForm( $N_class_name )
	{
		if(!static::$create_form) {
			$fields = DataModel_Definition_Model_Trait::getCreateForm_mainFields();

			unset($fields['id_controller_class']);
			unset($fields['id_property_name']);

			$current_class = DataModels::getCurrentClass();
			$current_model = DataModels::getCurrentModel();

			if( $current_class ) {
				$fields['model_name']->setDefaultValue( $current_model->getModelName().'_' );
				$fields['class_name']->setDefaultValue( $current_class->getClassName().'_' );
			}

			$fields['N_class_name'] = new Form_Field_Hidden('N_class_name', '', $N_class_name);

			$M_model = DataModels::getCurrentModel();
			$N_model = DataModels::getClass($N_class_name)->getDefinition();


			$related_fields = [];

			foreach($M_model->getIdProperties() as $id_property) {
				$name = 'related_M_'.$id_property->getName();
				$label = Tr::_('Relation %name% property name:', ['name'=>$M_model->getModelName().'.'.$id_property->getName()]);
				$default_value = $M_model->getModelName().'_'.$id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label, $default_value );
				$related_fields[] = $name;
			}

			foreach($N_model->getIdProperties() as $id_property) {
				$name = 'related_N_'.$id_property->getName();
				$label = Tr::_('Relation %name% property name:', ['name'=>$N_model->getModelName().'.'.$id_property->getName()]);
				$default_value = $N_model->getModelName().'_'.$id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label, $default_value );
				$related_fields[] = $name;
			}


			foreach($related_fields as $name) {
				$field = $fields[$name];

				$field->setIsRequired(true);
				$field->setErrorMessages([
					Form_Field_Input::ERROR_CODE_EMPTY => Tr::_('Please enter property name'),
					Form_Field_Input::ERROR_CODE_INVALID_FORMAT => Tr::_('Invalid property name format')
				]);
				$field->setValidator( function( Form_Field_Input $field ) {
					return DataModel_Definition_Property::checkPropertyNameFormat( $field );
				} );

			}


			static::$create_form = new Form('create_data_model_form_MtoN', $fields );
			static::$create_form->setDoNotTranslateTexts(true);
			static::$create_form->setAction( DataModels::getActionUrl('model/add/MtoN') );
		}

		return static::$create_form;
	}



	/**
	 * @param string $N_class_name
	 *
	 * @return bool|DataModel_Definition_Model_Related_MtoN
	 */
	public static function catchCreateForm( $N_class_name )
	{
		$form = static::getCreateForm( $N_class_name );

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$class = DataModel_Definition_Model_Trait::catchCreateForm_createClass($form);

		$model = new DataModel_Definition_Model_Related_MtoN();
		$model->setClass( $class );

		$model_name = $form->field('model_name')->getValue();

		$model->setModelName( $model_name );


		$M_model = DataModels::getCurrentModel();
		$N_model = DataModels::getClass($N_class_name)->getDefinition();

		$model->setParentModel( $M_model );
		$model->setNModelClassName( $N_class_name );

		foreach($M_model->getIdProperties() as $id_property) {
			$relation_property_name = $form->field('related_M_'.$id_property->getName())->getValue();

			$class_name = get_class($id_property);

			/**
			* @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
			*/
			$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

			$relation_property->setIsKey(true);
			$relation_property->setRelatedToClassName( 'main:'.$M_model->getClassName() );
			$relation_property->setRelatedToPropertyName( $id_property->getName() );
			$model->addProperty($relation_property);

		}

		foreach($N_model->getIdProperties() as $id_property) {

			$relation_property_name = $form->field('related_N_'.$id_property->getName())->getValue();

			$class_name = get_class($id_property);

			/**
			 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
			 */
			$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

			$relation_property->setIsKey(true);
			$relation_property->setRelatedToClassName( $N_model->getModelName().':'.$N_model->getClassName() );
			$relation_property->setRelatedToPropertyName( $id_property->getName() );
			$model->addProperty($relation_property);
		}


		return $model;

	}

}