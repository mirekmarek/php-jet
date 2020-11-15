<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Relations;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;
use Jet\Reflection;
use Jet\Tr;
use Jet\DataModel_Exception;

/**
 */
trait DataModel_Definition_Model_Trait {

	/**
	 * @var DataModel_Class
	 */
	protected $_class;

	/**
	 * @var bool
	 */
	protected $_is_abstract = false;

	/**
	 * @var string
	 */
	protected $_extends = '';


	/**
	 * @var array
	 */
	protected $_implements = [];

	/**
	 * @var ClassCreator_Class
	 */
	protected $__class;


	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @var Form
	 */
	protected $__sort_properties_form;



	/**
	 * @return DataModel_Class
	 */
	public function getClass()
	{
		return $this->_class;
	}

	/**
	 * @param DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class )
	{
		$this->_class = $_class;

		$this->_is_abstract = $_class->isAbstract();
		$this->_extends = $_class->getExtends();
		$this->_implements = $_class->getImplements();

		foreach($this->properties as $property) {
			$property->setClass( $_class );
		}
	}

	/**
	 */
	protected function _initDatabaseTableName()
	{
		$this->database_table_name = Reflection::get( $this->class_name, 'database_table_name', '' );
	}

	/**
	 *
	 */
	public function _initExternalRelations()
	{

		$class = $this->class_name;

		$relations_definitions_data = Reflection::get( $class, 'data_model_external_relations_definition', [] );

		foreach( $relations_definitions_data as $definition_data ) {
			$relation = new DataModel_Definition_Relation_External( $class, $definition_data );

			DataModel_Relations::add(
				$class,
				$relation
			);
		}

	}


	/**
	 * @return bool
	 */
	public function isAbstract()
	{
		return $this->_is_abstract;
	}

	/**
	 * @return bool
	 */
	public function canHaveRelated()
	{
		return true;
	}

	/**
	 * @return array
	 */
	public function getExtendsScope()
	{
		$extends_scope = [
			'' => '- default -',
		];

		foreach( DataModels::getClasses() as $e_class ) {
			if(
				/*
				get_class($this)!=get_class($e_model) ||
				*/
				$e_class->getFullClassName()==$this->_class->getFullClassName() ||
				$e_class->isDescendantOf( $this->_class )
			) {
				continue;
			}

			$extends_scope[$e_class->getFullClassName()] = $e_class->getFullClassName();
		}


		return $extends_scope;
	}


	/**
	 * @return DataModel_Definition_Property_Interface|\Jet\DataModel_Definition_Property
	 */
	public function getProperties()
	{
		return $this->properties;
	}
	/**
	 *
	 * @param string $property_name
	 *
	 * @return DataModel_Definition_Property_Interface|\Jet\DataModel_Definition_Property
	 */
	public function getProperty( $property_name )
	{
		return $this->properties[$property_name];
	}


	/**
	 * @param string $name
	 * @param string $type
	 * @param array  $key_properties
	 *
	 * @throws DataModel_Exception
	 */
	public function addKey( $name, $type, array $key_properties )
	{

		if( isset( $this->keys[$name] ) ) {
			throw new DataModel_Exception(
				'Class \''.$this->getClassName().'\': duplicate key \''.$name.'\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$my_properties = $this->getProperties();

		foreach( $key_properties as $property_name ) {
			if( !isset( $my_properties[$property_name] ) ) {
				throw new DataModel_Exception(
					'Unknown key property \''.$property_name.'\'. Class: \''.$this->class_name.'\' Key: \''.$name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}
		}

		$this->keys[$name] = new DataModel_Definition_Key( $name, $type, $key_properties );
	}


	/**
	 * @return DataModel_Definition_Id_Abstract|null
	 */
	public function getIDControllerDefinition()
	{

		if(!$this->getIDControllerClassName()) {
			return null;
		}

		$class_name = __NAMESPACE__.'\DataModel_Definition_Id_'.str_replace('Jet\DataModel_IDController_', '', $this->getIDControllerClassName());

		return new $class_name( $this );
	}



	/**
	 * @return ClassCreator_Class|null
	 */
	public function createClass()
	{
		if(!$this->__class) {
			$class = $this->createClass_initClass();

			$this->createClass_main( $class );
			$this->createClass_ID( $class );
			$this->createClass_customKeys( $class );
			$this->createClass_externalRelations( $class );
			$this->createClass_properties( $class );
			$this->createClass_methods( $class );


			$dm = new ClassCreator_ActualizeDecisionMaker();

			$remove_getters = [];
			$remove_setters = [];

			$dm->update_class_annotation = function() {
				return true;
			};

			$dm->update_property = function( ClassCreator_Class_Property $new_property, ClassParser_Class_Property $current_property ) {
				return true;
			};

			$dm->remove_property = function( ClassParser_Class_Property $property ) use (&$remove_getters, &$remove_setters) {

				if(
					$property->doc_comment &&
					strpos($property->doc_comment->text, '@JetDataModel:')
				) {
					$method_name = DataModel_Definition_Property_Trait::generateSetterGetterMethodName( $property->name );

					$remove_getters[] = 'get'.$method_name;
					$remove_setters[] = 'set'.$method_name;

					return true;
				}

				return false;
			};

			$dm->remove_method = function( ClassParser_Class_Method $method ) use (&$remove_getters, &$remove_setters) {
				if(
					in_array(  $method->name, $remove_setters) ||
					in_array(  $method->name, $remove_getters)
				) {
					return true;
				}

				return false;
			};

			$class->setActualizeDecisionMaker( $dm );

			$this->__class = $class;
		}


		return $this->__class;
	}

	/**
	 * @param ClassCreator_Class $class
	 * @param string $default
	 *
	 * @return string
	 */
	public function createClass_getExtends( ClassCreator_Class $class, $default )
	{
		if(!$this->_extends) {
			return $default;
		}

		$extends = $this->_extends;

		$extends_class = DataModels::getClass( $this->_extends );
		if($extends_class) {
			return $extends_class->getFullClassName();
		}

		$use = ClassCreator_UseClass::createByClassName( $extends );

		if($use->getNamespace()!=$class->getNamespace()) {
			$class->addUse( $use );
		}

		return $use->getClass();
	}


	/**
	 * @return ClassCreator_Class
	 */
	public function createClass_initClass()
	{

		$class = new ClassCreator_Class();

		$class->setName( $this->getClassName() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel') );
		$this->_extends = $this->createClass_getExtends($class, 'DataModel');

		if($this->_implements) {
			foreach( $this->_implements as $i ) {
				$use = ClassCreator_UseClass::createByClassName($i);
				$class->addUse( $use );

				$class->addImplements( $use->getClass() );
			}
		}

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
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_ID( ClassCreator_Class $class )
	{
		if($this->getIDControllerDefinition()) {
			$this->getIDControllerDefinition()->createClassIdDefinition( $class );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_customKeys( ClassCreator_Class $class )
	{
		foreach( $this->getKeys() as $key ) {
			$class->addAnnotation( $key->getAsAnnotation( $class ) );
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_externalRelations( ClassCreator_Class $class )
	{
		foreach( $this->getExternalRelations() as $relation ) {
			$class->addAnnotation( $relation->getAsAnnotation( $class ) );
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_properties( ClassCreator_Class $class )
	{
		$model = $this;

		foreach( $model->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			if($class->hasProperty($property->getName())) {
				$class->addError('Duplicate property '.$property->getName());
				continue;
			}
			$class->addProperty( $property->createClassProperty( $class ) );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_methods( ClassCreator_Class $class )
	{
		$model = $this;

		foreach( $model->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			$property->createClassMethods( $class );
		}

		if( ($id_controller_definition=$this->getIDControllerDefinition()) ) {
			$id_controller_definition->createClassMethods( $class );
		}
	}

	/**
	 *
	 */
	public function prepare()
	{
		if(!$this->database_table_name) {
			$this->database_table_name = $this->getModelName();
		}

		$this->id_properties = [];

		$properties = [];

		foreach( $this->properties as $property ) {
			/**
			 * @var DataModel_Definition_Property_Interface $property
			 */
			$property->prepare();


			if($property->getIsId()) {
				$this->id_properties[] = $property->getName();
			}

			$properties[$property->getName()] = $property;
		}

		$this->properties = $properties;

		foreach( $this->properties as $property_name => $property_definition ) {
			/**
			 * @var DataModel_Definition_Property_Interface $property_definition
			 */

			if( $property_definition->getIsKey() ) {
				$this->keys[$property_name] = new DataModel_Definition_Key(
					$property_name,
					$property_definition->getIsUnique() ? DataModel::KEY_TYPE_UNIQUE : DataModel::KEY_TYPE_INDEX,
					[ $property_name ]
				);
			}
		}

		if( $this->id_properties ) {
			$key_name = $this->model_name.'_pk';

			$this->keys[$key_name] = new DataModel_Definition_Key(
				$key_name,
				DataModel::KEY_TYPE_PRIMARY,
				$this->id_properties
			);

		}

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
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter DataModel name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel name format'
			]);
			$model_name_field->setCatcher( function( $value ) {
				$this->setModelName( $value );
			} );
			$model_name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModels::checkModelName( $field, $this );
			} );
















			$database_table_name_field = new Form_Field_Input('database_table_name', 'Table name:', $this->database_table_name);
			$database_table_name_field->setCatcher( function( $value ) {
				$this->setDatabaseTableName( $value );
			} );
			$database_table_name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel table name name format'
			]);
			$database_table_name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModels::checkTableName( $field, $this );
			} );




			$id_controller_class_field = new Form_Field_Select('id_controller_class', 'ID controller class: ', $this->getIDControllerClassName() );
			$id_controller_class_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select ID controller class'
			]);
			$id_controller_class_field->setCatcher( function( $value ) {
				$this->setIDControllerClassName( $value );
			} );
			$id_controller_class_field->setSelectOptions(
				DataModels::getIDControllers()
			);

			$fields = [
				$model_name_field,
				$database_table_name_field,
				$id_controller_class_field
			];


			if($this->getIDControllerDefinition()) {
				$id_option_fields = $this->getIDControllerDefinition()->getOptionsFormFields();
				foreach( $id_option_fields as $field ) {
					$field->setName('/id_controller_options/'.$field->getName());
					$fields[] = $field;
				}
			}


			if(
				$this instanceof DataModel_Definition_Model_Related_1toN
				||
				$this instanceof DataModel_Definition_Model_Related_MtoN
			)  {

				$iterator_class_name_field = new Form_Field_Input('iterator_class_name', 'Iterator class:', $this->getIteratorClassName());
				$iterator_class_name_field->setCatcher( function( $value ) {
					$this->setIteratorClassName( $value );
				} );
				$iterator_class_name_field->setIsRequired( true );
				$iterator_class_name_field->setValidationRegexp( '/^[a-z0-9\\\\\_]{2,}$/i' );
				$iterator_class_name_field->setErrorMessages([
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter iterator class name',
					Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid iterator class name name format'
				]);

				$fields[$iterator_class_name_field->getName()] = $iterator_class_name_field;
			}


			if(
				$this instanceof DataModel_Definition_Model_Related_1toN
				||
				$this instanceof DataModel_Definition_Model_Related_MtoN
			) {
				$default_order_by_field = new Form_Field_Hidden( 'default_order_by', '', implode('|', $this->getDefaultOrderBy()) );
				$default_order_by_field->setCatcher( function( $value ) {
					if(!$value) {
						$value = [];
					} else {
						$value = explode('|', $value);
					}
					$this->setDefaultOrderBy( $value );
				} );

				$fields[$default_order_by_field->getName()] = $default_order_by_field;
			}



			$this->__edit_form = new Form('edit_model_form', $fields );
			$this->__edit_form->setAction( DataModels::getActionUrl('edit') );

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
	 * @param DataModel_Definition_Property_Interface $property
	 */
	public function addProperty(DataModel_Definition_Property_Interface $property )
	{

		$this->properties[$property->getName()] = $property;

		foreach( DataModels::getClasses() as $class ) {

			if( $class->isDescendantOf( $this->_class ) ) {

				$new_property = clone $property;

				/*
				//TODO:
				$new_property->setIsInherited( true );
				$new_property->setInheritedPropertyId( $property->getName() );
				$new_property->setInheritedModelId( $this->getInternalId() );
				*/

				$this->addProperty( $new_property );
			}
		}

		$this->__sort_properties_form = null;
	}




	/**
	 *
	 * @param string $option
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getIDControllerOption( $option, $default_value )
	{
		if(empty( $this->id_controller_options[$option])) {
			$this->id_controller_options[$option] = $default_value;
			return $default_value;
		}

		return $this->id_controller_options[$option];
	}

	/**
	 *
	 * @param string $option
	 * @param mixed $value
	 */
	public function setIDControllerOption( $option, $value )
	{
		$this->id_controller_options[$option] = $value;
	}



	/**
	 * @return DataModel_Definition_Relation_External[]
	 */
	public function getExternalRelations()
	{
		$res = [];
		foreach($this->relations as $relation) {
			if($relation instanceof DataModel_Definition_Relation_External) {
				$res[$relation->getInternalId()] = $relation;
			}
		}

		return $res;
	}

















	/**
	 * @return string
	 */
	public function getInternalType()
	{
		return $this->internal_type;
	}

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getExtends()
	{
		return $this->_extends;
	}


	/**
	 * @return array
	 */
	public function getImplements()
	{
		return $this->_implements;
	}




	/**
	 * @return string
	 */
	public function getModelName()
	{
		return $this->model_name;
	}

	/**
	 * @param string $model_name
	 */
	public function setModelName($model_name)
	{
		$this->model_name = $model_name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseTableName()
	{
		return $this->database_table_name;
	}

	/**
	 * @param string $database_table_name
	 */
	public function setDatabaseTableName($database_table_name)
	{
		$this->database_table_name = $database_table_name;
	}

	/**
	 * @param string $id_controller_class_name
	 */
	public function setIDControllerClassName( $id_controller_class_name )
	{
		$this->id_controller_class_name = $id_controller_class_name;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class_name;
	}


	/**
	 * @param DataModel_Definition_Key $key
	 */
	public function addNewKey( DataModel_Definition_Key $key )
	{
		$this->keys[ $key->getName() ] = $key;
	}

	/**
	 * @param string $key_name
	 *
	 * @return DataModel_Definition_Key|null
	 */
	public function getKey( $key_name )
	{
		foreach($this->keys as $key) {
			/**
			 * @var DataModel_Definition_Key $key
			 */
			if($key->getName()==$key_name) {
				return $key;
			}
		}

		return null;
	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getKeys()
	{
		return $this->keys;
	}

	/**
	 * @param string $key_name
	 */
	public function deleteKey( $key_name )
	{
		$_keys = [];

		foreach($this->keys as $key) {
			/**
			 * @var DataModel_Definition_Key $key
			 */
			if($key->getName()==$key_name) {
				continue;
			}

			$_keys[] = $key;
		}

		$this->keys = $_keys;
	}


	/**
	 * @param DataModel_Definition_Relation_External $relation
	 */
	public function addExternalRelation( DataModel_Definition_Relation_External $relation )
	{
		$this->relations[] = $relation;
	}

	/**
	 * @param string $relation_id
	 *
	 * @return DataModel_Definition_Relation_External|null
	 */
	public function getExternalRelation( $relation_id )
	{
		foreach($this->relations as $relation) {
			if(
				$relation instanceof DataModel_Definition_Relation_External &&
				$relation->getInternalId()==$relation_id
			) {
				return $relation;
			}
		}

		return null;
	}


	/**
	 * @param string $relation_id
	 */
	public function deleteExternalRelation( $relation_id )
	{
		$_relations = [];
		foreach($this->relations as $relation) {
			if(
				$relation instanceof DataModel_Definition_Relation_External &&
				$relation->getInternalId()==$relation_id
			) {
				continue;
			}

			$_relations[] = $relation;
		}

		$this->relations = $_relations;
	}








	/**
	 * @return string
	 */
	public function getClassPath()
	{
		return $this->_class->getScriptPath();
	}



}