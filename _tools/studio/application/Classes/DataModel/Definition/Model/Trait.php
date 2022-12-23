<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Cache;
use Jet\DataModel;
use Jet\DataModel_Exception;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Tr;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_IDController_Passive;

/**
 */
trait DataModel_Definition_Model_Trait
{

	/**
	 * @var ?DataModel_Class
	 */
	protected ?DataModel_Class $_class = null;

	/**
	 * @var bool
	 */
	protected bool $_is_abstract = false;

	/**
	 * @var string
	 */
	protected string $_extends = '';


	/**
	 * @var array
	 */
	protected array $_implements = [];

	/**
	 *
	 * @var DataModel_Definition_Relation_External[]
	 */
	protected ?array $external_relations = null;

	/**
	 * @param null|array|DataModel_Definition_Key[] $key
	 */
	protected ?array $custom_keys = null;

	/**
	 * @var ?ClassCreator_Class
	 */
	protected ?ClassCreator_Class $__class = null;


	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;


	/**
	 * @return DataModel_Class
	 */
	public function getClass(): DataModel_Class
	{
		return $this->_class;
	}

	/**
	 * @param DataModel_Class $_class
	 */
	public function setClass( DataModel_Class $_class ): void
	{
		$this->_class = $_class;

		$this->_is_abstract = $_class->isAbstract();
		$this->_extends = $_class->getExtends();
		$this->_implements = $_class->getImplements();

		$this->class_name = $_class->getFullClassName();

		foreach( $this->properties as $property ) {
			/**
			 * @var DataModel_Definition_Property_Interface $property
			 */
			$property->setClass( $_class );
		}
	}

	/**
	 */
	protected function _initDatabaseTableName(): void
	{
		$this->database_table_name = $this->class_arguments['database_table_name'] ?? '';
	}


	/**
	 * @return bool
	 */
	public function isAbstract(): bool
	{
		return $this->_is_abstract;
	}

	/**
	 * @return bool
	 */
	public function canHaveRelated(): bool
	{
		return true;
	}

	/**
	 * @return array
	 */
	public function getExtendsScope(): array
	{
		$extends_scope = [
			'' => '- default -',
		];

		foreach( DataModels::getClasses() as $e_class ) {
			if(
				/*
				get_class($this)!=get_class($e_model) ||
				*/
				$e_class->getFullClassName() == $this->_class->getFullClassName() ||
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
	public function getProperties(): array
	{
		return $this->properties;
	}

	/**
	 *
	 */
	protected function _initKeys(): void
	{
		/** @noinspection PhpMultipleClassDeclarationsInspection */
		parent::_initKeys();

		$keys_definition_data = $this->class_arguments['keys'] ?? [];

		foreach( $keys_definition_data as $kd ) {
			$key = new DataModel_Definition_Key(
				$kd['name'], $kd['type'], $kd['property_names']
			);
			$this->custom_keys[$key->getName()] = $key;

		}

	}

	/**
	 * @return DataModel_Definition_Id_Abstract|null
	 */
	public function getIDControllerDefinition(): DataModel_Definition_Id_Abstract|null
	{

		if( !$this->getIDControllerClassName() ) {
			return null;
		}

		$class_name = __NAMESPACE__ . '\DataModel_Definition_Id_' . str_replace( 'Jet\DataModel_IDController_', '', $this->getIDControllerClassName() );

		return new $class_name( $this );
	}


	/**
	 * @return ClassCreator_Class|null
	 */
	public function createClass(): ClassCreator_Class|null
	{
		if( !$this->__class ) {
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
	public function createClass_getExtends( ClassCreator_Class $class, string $default ): string
	{
		if( !$this->_extends ) {
			return $default;
		}

		$extends = $this->_extends;

		$extends_class = DataModels::getClass( $this->_extends );
		if( $extends_class ) {
			return $extends_class->getFullClassName();
		}

		$use = ClassCreator_UseClass::createByClassName( $extends );

		if( $use->getNamespace() != $class->getNamespace() ) {
			$class->addUse( $use );
		}

		return $use->getClass();
	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_main( ClassCreator_Class $class ): void
	{
		$class->setAttribute( 'DataModel_Definition', 'name', $this->getModelName() );

		if( $this->getDatabaseTableName() ) {
			$class->setAttribute( 'DataModel_Definition', 'database_table_name', $this->getDatabaseTableName() );
		} else {
			$class->setAttribute( 'DataModel_Definition', 'database_table_name', $this->getModelName() );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_ID( ClassCreator_Class $class ): void
	{
		if( $this->getIDControllerDefinition() ) {
			$this->getIDControllerDefinition()->createClass_IdDefinition( $class );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_customKeys( ClassCreator_Class $class ): void
	{
		$keys = [];
		foreach( $this->getCustomKeys() as $key ) {
			$keys[] = $key->createClass_getAsAttribute( $class );
		}

		if( $keys ) {
			if( count( $keys ) == 1 ) {
				$class->setAttribute( 'DataModel_Definition', 'key', $keys[0] );
			} else {
				$class->setAttribute( 'DataModel_Definition', 'keys', $keys );
			}
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_externalRelations( ClassCreator_Class $class ): void
	{
		$relations = [];
		foreach( $this->getExternalRelations() as $relation ) {
			$relations[] = $relation->createClass_getAsAttribute( $class );
		}

		if( $relations ) {
			if( count( $relations ) == 1 ) {
				$class->setAttribute( 'DataModel_Definition', 'relation', $relations[0] );
			} else {
				$class->setAttribute( 'DataModel_Definition', 'relations', $relations );
			}
		}

	}


	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_properties( ClassCreator_Class $class ): void
	{
		$model = $this;

		foreach( $model->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			if( $class->hasProperty( $property->getName() ) ) {
				$class->addError( 'Duplicate property ' . $property->getName() );
				continue;
			}
			$class->addProperty( $property->createClassProperty( $class ) );
		}
	}

	/**
	 * @param ClassCreator_Class $class
	 */
	public function createClass_methods( ClassCreator_Class $class ): void
	{
		foreach( $this->getProperties() as $property ) {
			if(
				$property->isInherited() &&
				!$property->isOverload()
			) {
				continue;
			}

			$property->createClassMethods( $class );
		}

		if( ($id_controller_definition = $this->getIDControllerDefinition()) ) {
			$id_controller_definition->createClassMethods( $class );
		}
	}

	/**
	 *
	 */
	public function prepare(): void
	{
		if( !$this->database_table_name ) {
			$this->database_table_name = $this->getModelName();
		}

		$this->id_properties = [];

		$properties = [];

		foreach( $this->properties as $property ) {
			/**
			 * @var DataModel_Definition_Property_Interface $property
			 */
			$property->prepare();


			if( $property->getIsId() ) {
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
					[$property_name]
				);
			}
		}

		if( $this->id_properties ) {
			$key_name = $this->model_name . '_pk';

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
	public function getEditForm(): Form
	{

		if( !$this->__edit_form ) {


			$model_name_field = new Form_Field_Input( 'model_name', 'Model name:' );
			$model_name_field->setDefaultValue( $this->model_name );
			$model_name_field->setIsRequired( true );
			$model_name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter DataModel name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel name format'
			] );
			$model_name_field->setFieldValueCatcher( function( $value ) {
				$this->setModelName( $value );
			} );
			$model_name_field->setValidationRegexp('/^[a-z0-9_]{2,}$/i');


			$database_table_name_field = new Form_Field_Input( 'database_table_name', 'Table name:' );
			$database_table_name_field->setDefaultValue( $this->database_table_name );
			$database_table_name_field->setFieldValueCatcher( function( $value ) {
				$this->setDatabaseTableName( $value );
			} );
			$database_table_name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid DataModel table name name format',
				'data_model_table_is_not_unique' => 'DataModel with the same table name already exists',
			] );
			$database_table_name_field->setValidator( function( Form_Field_Input $field ) {


				$name = $field->getValue();

				if( !$name ) {
					return true;
				}


				if(
					!preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ||
					str_contains( $name, '__' )
				) {
					$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

					return false;
				}

				$exists = false;

				$m_class = DataModels::getClass($this->getClassName());

				foreach( DataModels::getClasses() as $class ) {
					if(
						$class->isDescendantOf( $m_class ) ||
						$m_class->isDescendantOf($class)
					) {
						continue;
					}

					$m = $class->getDefinition();

					if(
						$class->getFullClassName() != $this->getClassName() &&
						(
							$m->getDatabaseTableName() == $name ||
							$m->getModelName() == $name
						)
					) {
						$exists = true;
						break;
					}
				}

				if( $exists ) {
					$field->setError('data_model_table_is_not_unique');

					return false;
				}

				return true;
			} );


			$id_controller_class_field = new Form_Field_Select( 'id_controller_class', 'ID controller class: ' );
			$id_controller_class_field->setDefaultValue( $this->getIDControllerClassName() );
			$id_controller_class_field->setIsReadonly( true );
			$id_controller_class_field->setErrorMessages( [
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select ID controller class'
			] );
			$id_controller_class_field->setFieldValueCatcher( function( $value ) {
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


			if( $this->getIDControllerDefinition() ) {
				$id_option_fields = $this->getIDControllerDefinition()->getOptionsFormFields();
				foreach( $id_option_fields as $field ) {
					$field->setName( '/id_controller_options/' . $field->getName() );
					$fields[] = $field;
				}
			}


			if(
				$this instanceof DataModel_Definition_Model_Related_1toN
			) {
				$default_order_by_field = new Form_Field_Hidden( 'default_order_by', '' );
				$default_order_by_field->setDefaultValue( implode( '|', $this->getDefaultOrderBy() ) );
				$default_order_by_field->setFieldValueCatcher( function( $value ) {
					if( !$value ) {
						$value = [];
					} else {
						$value = explode( '|', $value );
					}
					$this->setDefaultOrderBy( $value );
				} );

				$fields[$default_order_by_field->getName()] = $default_order_by_field;
			}


			$this->__edit_form = new Form( 'edit_model_form', $fields );
			$this->__edit_form->setAction( DataModels::getActionUrl( 'model/edit' ) );

		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm(): bool
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchFieldValues();

		return true;
	}


	/**
	 * @param DataModel_Definition_Property_Interface $property
	 */
	public function addProperty( DataModel_Definition_Property_Interface $property ): void
	{
		$this->properties[$property->getName()] = $property;
	}


	/**
	 *
	 * @param string $option
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getIDControllerOption( string $option, mixed $default_value ): mixed
	{
		if( empty( $this->id_controller_options[$option] ) ) {
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
	public function setIDControllerOption( string $option, mixed $value ): void
	{
		$this->id_controller_options[$option] = $value;
	}


	/**
	 * @return DataModel_Definition_Relation_External[]
	 */
	public function getExternalRelations(): array
	{
		if( $this->external_relations === null ) {
			$this->external_relations = [];

			if( !$this->_class->isIsNew() ) {
				$class = $this->class_name;

				$relations_definitions_data = $this->class_arguments['relations'] ?? [];

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
	public function getInternalType(): string
	{
		return $this->internal_type;
	}

	/**
	 * @return string
	 */
	public function getExtends(): string
	{
		return $this->_extends;
	}


	/**
	 * @return array
	 */
	public function getImplements(): array
	{
		return $this->_implements;
	}


	/**
	 * @return string
	 */
	public function getModelName(): string
	{
		return $this->model_name;
	}

	/**
	 * @param string $model_name
	 */
	public function setModelName( string $model_name ): void
	{
		$this->model_name = $model_name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseTableName(): string
	{
		return $this->database_table_name;
	}

	/**
	 * @param string $database_table_name
	 */
	public function setDatabaseTableName( string $database_table_name ): void
	{
		$this->database_table_name = $database_table_name;
	}

	/**
	 * @param string $id_controller_class
	 */
	public function setIDControllerClassName( string $id_controller_class ): void
	{
		$this->id_controller_class = $id_controller_class;
	}

	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->class_name;
	}


	/**
	 * @param DataModel_Definition_Key $key
	 */
	public function addCustomNewKey( DataModel_Definition_Key $key ): void
	{
		$this->custom_keys[$key->getName()] = $key;
	}

	/**
	 * @param string $key_name
	 *
	 * @return DataModel_Definition_Key|null
	 */
	public function getCustomKey( string $key_name ): DataModel_Definition_Key|null
	{
		if( !isset( $this->custom_keys[$key_name] ) ) {
			return null;
		}

		return $this->custom_keys[$key_name];
	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getCustomKeys(): array
	{
		if( $this->custom_keys === null ) {
			$this->custom_keys = [];

			if( !$this->_class->isIsNew() ) {
				$keys_definition_data = $this->class_arguments['keys'] ?? [];

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
	public function getCustomCustomKeys(): array
	{
		$keys = [];

		foreach( $this->custom_keys as $key ) {
			if( $key->isCustom() ) {
				$keys[] = $key;
			}
		}

		return $keys;
	}

	/**
	 * @param string $key_name
	 */
	public function deleteCustomKey( string $key_name ): void
	{
		$_keys = [];

		foreach( $this->custom_keys as $key ) {
			/**
			 * @var DataModel_Definition_Key $key
			 */
			if( $key->getName() == $key_name ) {
				continue;
			}

			$_keys[] = $key;
		}

		$this->custom_keys = $_keys;
	}


	/**
	 * @param DataModel_Definition_Relation_External $relation
	 */
	public function addExternalRelation( DataModel_Definition_Relation_External $relation ): void
	{
		$this->getExternalRelations();

		$this->external_relations[$relation->getName()] = $relation;
	}

	/**
	 * @param string $relation_name
	 *
	 * @return DataModel_Definition_Relation_External|null
	 */
	public function getExternalRelation( string $relation_name ): DataModel_Definition_Relation_External|null
	{
		$this->getExternalRelations();
		if( !isset( $this->external_relations[$relation_name] ) ) {
			return null;
		}

		return $this->external_relations[$relation_name];
	}


	/**
	 * @param string $relation_name
	 */
	public function deleteExternalRelation( string $relation_name ): void
	{
		$this->getExternalRelations();

		unset( $this->external_relations[$relation_name] );
	}


	/**
	 * @return string
	 */
	public function getClassPath(): string
	{
		return $this->_class->getScriptPath();
	}

	/**
	 * @return bool
	 */
	public function save(): bool
	{
		$ok = true;

		try {
			$class = $this->createClass();

			if( $class->getErrors() ) {
				return false;
			}

			$script = IO_File::read( $this->_class->getScriptPath() );

			$parser = new ClassParser( $script );

			foreach( $class->getAttributes() as $attribute ) {
				$parser->actualize_setAttribute( $class->getName(), $attribute );
			}

			$parser->actualize_setUse( $class->getUse() );

			IO_File::write(
				$this->_class->getScriptPath(),
				$parser->toString()
			);

			Cache::resetOPCache();


		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;

	}

	/**
	 * @return bool
	 */
	public function create(): bool
	{
		$ok = true;
		try {
			$class = $this->createClass();

			if( $class->getErrors() ) {
				throw new DataModel_Exception( implode( '', $class->getErrors() ) );
			}

			IO_File::write(
				$this->_class->getScriptPath(),
				'<?php' . PHP_EOL . $class->toString()
			);

			Cache::resetOPCache();

			if( !$this instanceof DataModel_Definition_Model_Main ) {
				DataModels::load( true );

				$parent_class_name = $this->getRelevantParentModel()->getClassName();
				$parent_class = DataModels::getClass( $parent_class_name );

				$property = new DataModel_Definition_Property_DataModel( $parent_class_name, $this->getModelName() );

				$property->setDataModelClass( $class->getFullName() );

				if( !$property->add( $parent_class ) ) {
					return false;
				}
			}


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
	public static function getCreateForm_mainFields(): array
	{
		$current_class = DataModels::getCurrentClass();

		$type = new Form_Field_Hidden( 'type', '' );

		$namespace = new Form_Field_Select( 'namespace', Tr::_( 'Namespace:' ) );
		$namespace->setIsRequired( true );
		$namespace->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => Tr::_( 'Please select namespace' ),
			Form_Field::ERROR_CODE_INVALID_VALUE => Tr::_( 'Please select namespace' )
		] );
		$namespace->setSelectOptions( DataModels::getNamespaces() );


		$class_name = new Form_Field_Input( 'class_name', Tr::_( 'Class name:' ) );
		$class_name->setIsRequired( true );
		$class_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => Tr::_( 'Please enter DataModel class name' ),
			Form_Field::ERROR_CODE_INVALID_FORMAT => Tr::_( 'Invalid DataModel class name format' ),
			'data_model_class_is_not_unique'            => Tr::_( 'DataModel with the same class name already exists' ),
		] );
		$class_name->setValidator( function( Form_Field_Input $field ) {
			$name = $field->getValue();

			if(
				!preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ||
				str_contains( $name, '__' )
			) {
				$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

				return false;
			}

			foreach( DataModels::getClasses() as $class ) {

				if( $class->getFullClassName() == $name ) {
					$field->setError( 'data_model_class_is_not_unique' );

					return false;
				}
			}

			return true;
		} );


		$model_name = new Form_Field_Input( 'model_name', Tr::_( 'Model name:' ) );
		$model_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => Tr::_( 'Please enter DataModel name' ),
			Form_Field::ERROR_CODE_INVALID_FORMAT => Tr::_( 'Invalid DataModel name format' )
		] );
		$model_name->setIsRequired( true );
		$model_name->setValidationRegexp('/^[a-z0-9_]{2,}$/i');


		$script_path = new Form_Field_Input( 'script_path', Tr::_( 'Script path:' ) );
		$script_path->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => Tr::_( 'Please enter valid script path' ),
			Form_Field::ERROR_CODE_INVALID_FORMAT => Tr::_( 'Please enter valid script path' )
		] );
		$script_path->setIsRequired( true );


		if( $current_class ) {
			$namespace->setDefaultValue( $current_class->getNamespace() );
		}

		$id_controller_class = new Form_Field_Select( 'id_controller_class', Tr::_( 'ID controller class: ' ) );
		$id_controller_class->setErrorMessages( [
			Form_Field::ERROR_CODE_INVALID_VALUE => Tr::_( 'Please select ID controller class' )
		] );
		$id_controller_class->setFieldValueCatcher( function( $value ) {
			$this->setIDControllerClassName( $value );
		} );
		$id_controller_class->setSelectOptions(
			DataModels::getIDControllers()
		);

		$id_property_name = new Form_Field_Input( 'id_property_name', 'ID property name:' );
		$id_property_name->setDefaultValue( 'id' );
		$id_property_name->setIsRequired( true );
		$id_property_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter property name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format'
		] );
		$id_property_name->setValidator( function( Form_Field_Input $field ) {
			return DataModel_Definition_Property::checkPropertyNameFormat( $field );
		} );


		return [
			'type'                => $type,
			'namespace'           => $namespace,
			'class_name'          => $class_name,
			'model_name'          => $model_name,
			'script_path'         => $script_path,
			'id_controller_class' => $id_controller_class,
			'id_property_name'    => $id_property_name
		];

	}

	/**
	 * @return Form
	 */
	public static function getCreateForm_Main(): Form
	{
		$fields = static::getCreateForm_mainFields();

		$create_form = new Form( 'create_data_model_form_'.DataModel::MODEL_TYPE_MAIN, $fields );
		$create_form->setDoNotTranslateTexts( true );
		$create_form->setAction( DataModels::getActionUrl( 'model/add' ) );

		$create_form->field( 'type' )->setDefaultValue( DataModel::MODEL_TYPE_MAIN );

		return $create_form;
	}

	/**
	 * @param string $type
	 *
	 * @return Form
	 */
	public static function getCreateForm_Related( string $type ): Form
	{
		$fields = static::getCreateForm_mainFields();

		$current_class = DataModels::getCurrentClass();
		$current_model = DataModels::getCurrentModel();

		$fields['model_name']->setDefaultValue( $current_model->getModelName() . '_' );
		$fields['class_name']->setDefaultValue( $current_class->getClassName() . '_' );

		$related_fields = [];

		if( $current_model instanceof DataModel_Definition_Model_Main ) {
			foreach( $current_model->getIdProperties() as $id_property ) {
				$name = 'related_main_' . $id_property->getName();
				$label = Tr::_( 'Relation %name% property name:', ['name' => $current_model->getModelName() . '.' . $id_property->getName()] );
				$default_value = $current_model->getModelName() . '_' . $id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label );
				$fields[$name]->setDefaultValue( $default_value );
				$related_fields[] = $name;
			}

		} else {
			$main_definition = $current_model->getMainModelDefinition();
			foreach( $main_definition->getIdProperties() as $id_property ) {
				$name = 'related_main_' . $id_property->getName();
				$label = Tr::_( 'Relation %name% property name:', ['name' => $main_definition->getModelName() . '.' . $id_property->getName()] );
				$default_value = $main_definition->getModelName() . '_' . $id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label );
				$fields[$name]->setDefaultValue( $default_value );
				$related_fields[] = $name;
			}

			foreach( $current_model->getIdProperties() as $id_property ) {
				if( $id_property->getRelatedToClassName() == $main_definition->getClassName() ) {
					continue;
				}

				$name = 'related_parent_' . $id_property->getName();
				$label = Tr::_( 'Relation %name% property name:', ['name' => $current_model->getModelName() . '.' . $id_property->getName()] );
				$default_value = $current_model->getModelName() . '_' . $id_property->getName();

				$fields[$name] = new Form_Field_Input( $name, $label );
				$fields[$name]->setDefaultValue( $default_value );
				$related_fields[] = $name;
			}
		}

		foreach( $related_fields as $name ) {
			$field = $fields[$name];

			$field->setIsRequired( true );
			$field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => Tr::_( 'Please enter property name' ),
				Form_Field::ERROR_CODE_INVALID_FORMAT => Tr::_( 'Invalid property name format' )
			] );
			$field->setValidator( function( Form_Field_Input $field ) {
				return DataModel_Definition_Property::checkPropertyNameFormat( $field );
			} );

		}


		$create_form = new Form( 'create_data_model_form_' . $type, $fields );
		$create_form->setDoNotTranslateTexts( true );
		$create_form->setAction( DataModels::getActionUrl( 'model/add' ) );

		$create_form->field( 'type' )->setDefaultValue( $type );

		return $create_form;
	}

	/**
	 * @param Form $form
	 *
	 * @return DataModel_Class
	 */
	public static function catchCreateForm_createClass( Form $form ): DataModel_Class
	{
		$namespace = $form->field( 'namespace' )->getValue();
		$class_name = $form->field( 'class_name' )->getValue();
		$script_path = $form->field( 'script_path' )->getValue();

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
	public static function catchCreateForm_modelMainSetup( Form $form,
	                                                       DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $model ): void
	{


		$model_name = $form->field( 'model_name' )->getValue();
		$id_controller_class = $form->field( 'id_controller_class' )->getValue();
		$id_property_name = $form->field( 'id_property_name' )->getValue();


		$model->setModelName( $model_name );
		$model->setIDControllerClassName( $id_controller_class );


		switch( $id_controller_class ) {
			case DataModel_IDController_AutoIncrement::class:
				$id_property = new DataModel_Definition_Property_IdAutoIncrement( $model->getClassName(), $id_property_name );
				$id_controller_option = 'id_property_name';
				break;
			case DataModel_IDController_UniqueString::class:
				$id_property = new DataModel_Definition_Property_Id( $model->getClassName(), $id_property_name );
				$id_controller_option = 'id_property_name';
				break;
			case DataModel_IDController_Passive::class:
				$id_property = new DataModel_Definition_Property_Id( $model->getClassName(), $id_property_name );
				$id_controller_option = '';
				break;
			default:
				throw new DataModel_Exception( 'Unknown ID controller class ' . $id_controller_class );
		}

		$id_property->setIsId( true );
		$model->addProperty( $id_property );

		if( $id_controller_option ) {
			$model->getIDController()->setOptions( [
				$id_controller_option => $id_property_name
			] );
		}

	}

	/**
	 * @param Form $form
	 * @param DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $model
	 */
	public static function catchCreateForm_relatedModelSetup( Form $form,
	                                                          DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $model ): void
	{
		$current_model = DataModels::getCurrentModel();
		$model->setParentModel( $current_model );

		if( $current_model instanceof DataModel_Definition_Model_Main ) {
			foreach( $current_model->getIdProperties() as $id_property ) {
				$relation_property_name = $form->field( 'related_main_' . $id_property->getName() )->getValue();

				$class_name = get_class( $id_property );

				/**
				 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
				 */
				$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

				$relation_property->setIsKey( true );
				$relation_property->setRelatedToClassName( 'main:' . $current_model->getClassName() );
				$relation_property->setRelatedToPropertyName( $id_property->getName() );
				$model->addProperty( $relation_property );
			}

		} else {
			$main_definition = $current_model->getMainModelDefinition();
			foreach( $main_definition->getIdProperties() as $id_property ) {
				$relation_property_name = $form->field( 'related_main_' . $id_property->getName() )->getValue();

				$class_name = get_class( $id_property );

				/**
				 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
				 */
				$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

				$relation_property->setIsKey( true );
				$relation_property->setRelatedToClassName( 'main:' . $main_definition->getClassName() );
				$relation_property->setRelatedToPropertyName( $id_property->getName() );
				$model->addProperty( $relation_property );
			}

			foreach( $current_model->getIdProperties() as $id_property ) {
				if( $id_property->getRelatedToClassName() == $main_definition->getClassName() ) {
					continue;
				}

				$relation_property_name = $form->field( 'related_parent_' . $id_property->getName() )->getValue();

				$class_name = get_class( $id_property );

				/**
				 * @var DataModel_Definition_Property|DataModel_Definition_Property_Interface $relation_property
				 */
				$relation_property = new $class_name( $model->getClassName(), $relation_property_name );

				$relation_property->setIsKey( true );
				$relation_property->setRelatedToClassName( 'parent:' . $current_model->getClassName() );
				$relation_property->setRelatedToPropertyName( $id_property->getName() );
				$model->addProperty( $relation_property );
			}
		}

	}
}