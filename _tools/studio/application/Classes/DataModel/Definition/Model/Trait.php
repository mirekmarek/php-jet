<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\DataModel_Definition_Model;
use Jet\DataModel_Exception;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Reflection;
use Jet\Tr;

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
	 *
	 * @var DataModel_Definition_Relation_External[]
	 */
	protected $external_relations;

	/**
	 * @param DataModel_Definition_Key $key
	 */
	protected $custom_keys;

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

		$this->class_name = $_class->getFullClassName();

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
	 * @return DataModel_Definition_Property_Interface[]|\Jet\DataModel_Definition_Property[]
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
	 *
	 */
	protected function _initKeys()
	{
		parent::_initKeys();

		$keys_definition_data = Reflection::get( $this->class_name, 'data_model_keys_definition', [] );

		foreach( $keys_definition_data as $kd ) {
			$key = new DataModel_Definition_Key(
				$kd['name'], $kd['type'], $kd['property_names'], true
			);
			$this->custom_keys[$key->getName()] = $key;

		}

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
			$this->getIDControllerDefinition()->createClass_IdDefinition( $class );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_customKeys( ClassCreator_Class $class )
	{
		foreach( $this->getCustomKeys() as $key ) {
			$class->addAnnotation( $key->createClass_getAsAnnotation( $class ) );
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_externalRelations( ClassCreator_Class $class )
	{
		foreach( $this->getExternalRelations() as $relation ) {
			$class->addAnnotation( $relation->createClass_getAsAnnotation( $class ) );
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
			$id_controller_class_field->setIsReadonly(true);
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
	 * @param DataModel_Definition_Property_Interface $property
	 */
	public function addProperty(DataModel_Definition_Property_Interface $property )
	{

		$this->properties[$property->getName()] = $property;

		foreach( DataModels::getClasses() as $class ) {

			if( $class->isDescendantOf( $this->_class ) ) {

				$new_property = clone $property;

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
		if($this->external_relations===null) {
			$this->external_relations = [];

			if(!$this->_class->isIsNew()) {
				$class = $this->class_name;

				$relations_definitions_data = Reflection::get( $class, 'data_model_external_relations_definition', [] );

				foreach( $relations_definitions_data as $definition_data ) {
					$relation = new DataModel_Definition_Relation_External( $class, $definition_data );

					$this->external_relations[$relation->getName()] = $relation;
				}
			}

		}

		return $this->external_relations;
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
	public function addCustomNewKey( DataModel_Definition_Key $key )
	{
		$this->custom_keys[ $key->getName() ] = $key;
	}

	/**
	 * @param string $key_name
	 *
	 * @return DataModel_Definition_Key|null
	 */
	public function getCustomKey( $key_name )
	{
		if(!isset($this->custom_keys[$key_name])) {
			return null;
		}

		return $this->custom_keys[$key_name];
	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getCustomKeys()
	{
		if($this->custom_keys===null) {
			$this->custom_keys = [];

			if(!$this->_class->isIsNew()) {
				$keys_definition_data = Reflection::get( $this->class_name, 'data_model_keys_definition', [] );

				foreach( $keys_definition_data as $kd ) {
					$this->custom_keys[$kd['name']] = new DataModel_Definition_Key( $kd['name'], $kd['type'], $kd['property_names'] );
				}
			}

		}

		return $this->custom_keys;
	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getCustomCustomKeys()
	{
		$keys = [];

		foreach($this->custom_keys as $key) {
			if($key->isCustom()) {
				$keys[] = $key;
			}
		}

		return $keys;
	}

	/**
	 * @param string $key_name
	 */
	public function deleteCustomKey( $key_name )
	{
		$_keys = [];

		foreach($this->custom_keys as $key) {
			/**
			 * @var DataModel_Definition_Key $key
			 */
			if($key->getName()==$key_name) {
				continue;
			}

			$_keys[] = $key;
		}

		$this->custom_keys = $_keys;
	}


	/**
	 * @param DataModel_Definition_Relation_External $relation
	 */
	public function addExternalRelation( DataModel_Definition_Relation_External $relation )
	{
		$this->getExternalRelations();

		$this->external_relations[$relation->getName()] = $relation;
	}

	/**
	 * @param string $relation_name
	 *
	 * @return DataModel_Definition_Relation_External|null
	 */
	public function getExternalRelation( $relation_name )
	{
		$this->getExternalRelations();
		if(!isset($this->external_relations[$relation_name])) {
			return null;
		}

		return $this->external_relations[$relation_name];
	}


	/**
	 * @param string $relation_name
	 */
	public function deleteExternalRelation( $relation_name )
	{
		$this->getExternalRelations();

		unset($this->external_relations[$relation_name]);
	}








	/**
	 * @return string
	 */
	public function getClassPath()
	{
		return $this->_class->getScriptPath();
	}

	/**
	 * @return bool
	 */
	public function save()
	{
		$ok = true;
		try {
			$class = $this->createClass();

			if($class->getErrors()) {
				return false;
			}

			$script  = IO_File::read($this->_class->getScriptPath());

			$parser = new ClassParser( $script );

			$parser->actualize_setUse( $class->getUse() );
			$parser->actualize_setClassAnnotation( $this->_class->getClassName(), $class->generateClassAnnotation() );

			IO_File::write(
				$this->_class->getScriptPath(),
				$parser->toString()
			);

			Application::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;

	}

	/**
	 * @return bool
	 */
	public function create()
	{
		$ok = true;
		try {
			$class = $this->createClass();

			if($class->getErrors()) {
				throw new DataModel_Exception(implode('', $class->getErrors()));
			}

			IO_File::write(
				$this->_class->getScriptPath(),
				'<?php'.PHP_EOL.$class->toString()
			);

			Application::resetOPCache();

		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

	/**
	 *
	 * @return Form_Field[]
	 */
	public static function getCreateForm_mainFields()
	{
		$current_class = DataModels::getCurrentClass();

		$type = new Form_Field_Hidden('type', '');

		$namespace = new Form_Field_Select('namespace', Tr::_('Namespace:'), '');
		$namespace->setIsRequired(true);
		$namespace->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => Tr::_('Please select namespace'),
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => Tr::_('Please select namespace')
		]);
		$namespace->setSelectOptions( DataModels::getNamespaces() );


		$class_name = new Form_Field_Input('class_name', Tr::_('Class name:'), '');
		$class_name->setIsRequired(true);
		$class_name->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => Tr::_('Please enter DataModel class name'),
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => Tr::_('Invalid DataModel class name format')
		]);
		$class_name->setValidator( function( Form_Field_Input $field ) {
			return DataModels::checkClassName( $field );
		} );


		$model_name = new Form_Field_Input('model_name', Tr::_('Model name:'), '');
		$model_name->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => Tr::_('Please enter DataModel name'),
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => Tr::_('Invalid DataModel name format')
		]);
		$model_name->setIsRequired(true);
		$model_name->setValidator( function( Form_Field_Input $field ) {
			return DataModels::checkModelName( $field );
		} );


		$script_path = new Form_Field_Input('script_path', Tr::_('Script path:'), '');
		$script_path->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => Tr::_('Please enter valid script path'),
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => Tr::_('Please enter valid script path')
		]);
		$script_path->setIsRequired(true);


		if( $current_class ) {
			$namespace->setDefaultValue($current_class->getNamespace());
		}

		$id_controller_class = new Form_Field_Select('id_controller_class', Tr::_('ID controller class: '), '' );
		$id_controller_class->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => Tr::_('Please select ID controller class')
		]);
		$id_controller_class->setCatcher( function( $value ) {
			$this->setIDControllerClassName( $value );
		} );
		$id_controller_class->setSelectOptions(
			DataModels::getIDControllers()
		);

		$id_property_name = new Form_Field_Input('id_property_name', 'ID property name:', 'id');
		$id_property_name->setIsRequired(true);
		$id_property_name->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter property name',
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format'
		]);
		$id_property_name->setValidator( function( Form_Field_Input $field ) {
			return DataModel_Definition_Property::checkPropertyNameFormat( $field );
		} );



		$fields = [
			'type' => $type,
			'namespace' => $namespace,
			'class_name' => $class_name,
			'model_name' => $model_name,
			'script_path' => $script_path,
			'id_controller_class' => $id_controller_class,
			'id_property_name' => $id_property_name
		];

		return $fields;
	}

	/**
	 * @return Form
	 */
	public static function getCreateForm_Main()
	{
		$fields = static::getCreateForm_mainFields();

		$create_form = new Form('create_data_model_form_Main', $fields );
		$create_form->setDoNotTranslateTexts(true);
		$create_form->setAction( DataModels::getActionUrl('model/add') );

		$create_form->field('type')->setDefaultValue('Main');

		return $create_form;
	}

	/**
	 * @param string $type
	 *
	 * @return Form
	 */
	public static function getCreateForm_Related( $type )
	{
		$fields = static::getCreateForm_mainFields();

		$current_class = DataModels::getCurrentClass();
		$current_model = DataModels::getCurrentModel();

		$fields['model_name']->setDefaultValue( $current_model->getModelName().'_' );
		$fields['class_name']->setDefaultValue( $current_class->getClassName().'_' );

		$related_fields = [];

		if($current_model instanceof DataModel_Definition_Model_Main) {
			foreach($current_model->getIdProperties() as $id_property) {
				$name = 'related_main_'.$id_property->getName();
				$label = Tr::_('Relation %name% property name:', ['name'=>$current_model->getModelName().'.'.$id_property->getName()]);
				$default_value = $current_model->getModelName().'_'.$id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label, $default_value );
				$related_fields[] = $name;
			}

		} else {
			$main_definition = $current_model->getMainModelDefinition();
			foreach($main_definition->getIdProperties() as $id_property ) {
				$name = 'related_main_'.$id_property->getName();
				$label = Tr::_('Relation %name% property name:', ['name'=>$main_definition->getModelName().'.'.$id_property->getName()]);
				$default_value = $main_definition->getModelName().'_'.$id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label, $default_value );
				$related_fields[] = $name;
			}

			foreach($current_model->getIdProperties() as $id_property) {
				if($id_property->getRelatedToClassName()==$main_definition->getClassName()) {
					continue;
				}

				$name = 'related_parent_'.$id_property->getName();
				$label = Tr::_('Relation %name% property name:', ['name'=>$current_model->getModelName().'.'.$id_property->getName()]);
				$default_value = $current_model->getModelName().'_'.$id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label, $default_value );
				$related_fields[] = $name;
			}
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


		$create_form = new Form('create_data_model_form_'.$type, $fields );
		$create_form->setDoNotTranslateTexts(true);
		$create_form->setAction( DataModels::getActionUrl('model/add') );

		$create_form->field('type')->setDefaultValue($type);

		return $create_form;
	}

	/**
	 * @param Form $form
	 *
	 * @return DataModel_Class
	 */
	public static function catchCreateForm_createClass( Form $form )
	{
		$namespace = $form->field('namespace')->getValue();
		$class_name = $form->field('class_name')->getValue();
		$script_path = $form->field('script_path')->getValue();

		$class = new DataModel_Class(
			$script_path,
			$namespace,
			$class_name
		);

		$class->setIsNew( true );

		return $class;
	}

	/**
	 * @param Form $form
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $model
	 */
	public static function catchCreateForm_modelMainSetup( Form $form, $model )
	{


		$model_name = $form->field('model_name')->getValue();
		$id_controller_class = $form->field('id_controller_class')->getValue();
		$id_property_name = $form->field('id_property_name')->getValue();


		$model->setModelName( $model_name );
		$model->setIDControllerClassName(  $id_controller_class);


		switch($id_controller_class) {
			case 'Jet\DataModel_IDController_AutoIncrement':
				$id_property = new DataModel_Definition_Property_IdAutoIncrement( $model->getClassName(), $id_property_name);
				$id_controller_option = 'id_property_name';
				break;
			case 'Jet\DataModel_IDController_UniqueString':
			case 'Jet\DataModel_IDController_Name':
				$id_property = new DataModel_Definition_Property_Id( $model->getClassName(), $id_property_name);
				$id_controller_option = 'id_property_name';
				break;
			case 'Jet\DataModel_IDController_Passive':
				$id_property = new DataModel_Definition_Property_Id( $model->getClassName(), $id_property_name);
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

	}

	/**
	 * @param Form $form
	 * @param DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $model
	 */
	public static function catchCreateForm_relatedModelSetup( Form $form, $model )
	{
		$current_model = DataModels::getCurrentModel();
		$model->setParentModel( $current_model );

		if($current_model instanceof DataModel_Definition_Model_Main) {
			foreach($current_model->getIdProperties() as $id_property) {
				$relation_property_name = $form->field('related_main_'.$id_property->getName())->getValue();

				$class_name = get_class($id_property);

				/**
				 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
				 */
				$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

				$relation_property->setIsKey(true);
				$relation_property->setRelatedToClassName( 'main:'.$current_model->getClassName() );
				$relation_property->setRelatedToPropertyName( $id_property->getName() );
				$model->addProperty($relation_property);
			}

		} else {
			$main_definition = $current_model->getMainModelDefinition();
			foreach($main_definition->getIdProperties() as $id_property ) {
				$relation_property_name = $form->field('related_main_'.$id_property->getName())->getValue();

				$class_name = get_class($id_property);

				/**
				 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
				 */
				$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

				$relation_property->setIsKey(true);
				$relation_property->setRelatedToClassName( 'main:'.$main_definition->getClassName() );
				$relation_property->setRelatedToPropertyName( $id_property->getName() );
				$model->addProperty($relation_property);
			}

			foreach($current_model->getIdProperties() as $id_property) {
				if($id_property->getRelatedToClassName()==$main_definition->getClassName()) {
					continue;
				}

				$relation_property_name = $form->field('related_parent_'.$id_property->getName())->getValue();

				$class_name = get_class($id_property);

				/**
				 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
				 */
				$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

				$relation_property->setIsKey(true);
				$relation_property->setRelatedToClassName( 'parent:'.$current_model->getClassName() );
				$relation_property->setRelatedToPropertyName( $id_property->getName() );
				$model->addProperty($relation_property);
			}
		}

	}
}