<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;


/**
 *
 */
abstract class DataModel_Definition_Model extends BaseObject
{

	/**
	 *
	 * @var string
	 */
	protected string $model_name = '';

	/**
	 *
	 * @var string
	 */
	protected string $database_table_name = '';

	/**
	 *
	 * @var string
	 */
	protected string $class_name = '';

	/**
	 * @var ReflectionClass
	 */
	protected ReflectionClass $class_reflection;

	/**
	 * @var array
	 */
	protected array $class_arguments = [];

	/**
	 *
	 * @var string
	 */
	protected string $id_controller_class = '';

	/**
	 *
	 * @var array
	 */
	protected array $id_controller_options = [];

	/**
	 *
	 * @var array
	 */
	protected array $id_properties = [];

	/**
	 *
	 * @var DataModel_Definition_Property[]
	 */
	protected array $properties = [];

	/**
	 *
	 * @var DataModel_Definition_Key[]
	 */
	protected array $keys = [];

	/**
	 *
	 * @var DataModel_Definition_Relation[]
	 */
	protected array $relations = [];

	/**
	 *
	 * @param string $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct( string $data_model_class_name = '' )
	{

		if( $data_model_class_name ) {
			$this->_mainInit( $data_model_class_name );
		}
	}

	/**
	 * @throws DataModel_Exception
	 */
	public function init(): void
	{
		$this->_initProperties();
		$this->_initKeys();

		if( !$this->id_properties ) {
			throw new DataModel_Exception(
				'There are not any ID properties in DataModel \'' . $this->getClassName() . '\' definition',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @throws DataModel_Exception
	 */
	protected function _mainInit( string $data_model_class_name ): void
	{

		$this->class_name = $data_model_class_name;
		$this->class_reflection = new ReflectionClass( $data_model_class_name );

		$this->class_arguments = Attributes::getClassDefinition(
			$this->class_reflection,
			DataModel_Definition::class,
			[
				'key' => 'keys',
				'relation' => 'relations'
			]
		);


		$this->model_name = $this->_getModelNameDefinition();

		$this->_initIDController();
		$this->_initDatabaseTableName();
	}

	/**
	 * @param string $argument
	 * @param mixed|string $default_value
	 *
	 * @return mixed
	 */
	protected function getClassArgument( string $argument, mixed $default_value = '' ): mixed
	{
		return $this->class_arguments[$argument] ?? $default_value;
	}

	/**
	 *
	 * @return string
	 * @throws DataModel_Exception
	 */
	protected function _getModelNameDefinition(): string
	{
		$model_name = $this->getClassArgument( 'name' );

		if(
			(
				!is_string( $model_name ) ||
				!$model_name
			) &&
			!$this->class_reflection->isAbstract()
		) {
			throw new DataModel_Exception(
				'DataModel \'' . $this->class_name . '\' does not have model name! Please define attribute #[DataModel_Definition(name: \'SOME_NAME\')] ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $model_name;
	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initIDController(): void
	{
		$this->id_controller_class = $this->getClassArgument( 'id_controller_class' );

		if( !$this->id_controller_class ) {
			throw new DataModel_Exception(
				'DataModel \'' . $this->class_name . '\' does not have ID controller class name! Please define attribute #[DataModel_Definition(id_controller_class: SomeClass:class)] ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$options = $this->getClassArgument( 'id_controller_options' );

		if($options) {
			$this->id_controller_options = $options;
		}

	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initDatabaseTableName(): void
	{
		$this->database_table_name = $this->getClassArgument( 'database_table_name' );

		if( !$this->database_table_name ) {
			throw new DataModel_Exception(
				'DataModel \'' . $this->class_name . '\' does not have database table name! Please define attribute #[DataModel_Definition(database_table_name:\'some_table_name\')] ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

	}

	/**
	 *
	 */
	protected function _initProperties(): void
	{

		$properties_definition_data = $this->_getPropertiesDefinitionData();

		$this->properties = [];

		foreach( $properties_definition_data as $property_name => $property_dd ) {

			if( isset( $property_dd['related_to'] ) ) {
				$property_definition = $this->_initRelationProperty(
					$property_name,
					$property_dd['related_to'],
					$property_dd
				);
			} else {
				$property_definition = Factory_DataModel::getPropertyDefinitionInstance(
					$this->class_name,
					$property_name,
					$property_dd
				);
			}

			if( $property_definition->getIsId() ) {
				$this->id_properties[] = $property_definition->getName();
			}

			$property_name = $property_definition->getName();

			$this->properties[$property_name] = $property_definition;

		}

	}

	/**
	 * @param ?string $class_name
	 *
	 * @return array
	 */
	protected function _getPropertiesDefinitionData( ?string $class_name = null ): array
	{

		$reflection = $class_name ? new ReflectionClass( $class_name ) : $this->class_reflection;

		$properties_definition_data = Attributes::getClassPropertyDefinition( $reflection, DataModel_Definition::class );

		if(
		!$properties_definition_data
		) {
			throw new DataModel_Exception(
				'DataModel \'' . $this->class_name . '\' does not have any properties defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $properties_definition_data;
	}

	/**
	 * @param string $property_name
	 * @param string $related_to
	 * @param array $property_definition_data
	 *
	 * @return DataModel_Definition_Property|null
	 *
	 * @throws DataModel_Exception
	 *
	 */
	protected function _initRelationProperty( string $property_name,
	                                          string $related_to,
	                                          array $property_definition_data ): DataModel_Definition_Property|null
	{
		throw new DataModel_Exception(
			'It is not possible to define related property in Main DataModel  (\'' . $this->class_name . '\'::' . $property_name . ') ',
			DataModel_Exception::CODE_DEFINITION_NONSENSE
		);

		/** @noinspection PhpUnreachableStatementInspection */
		return null;
	}

	/**
	 *
	 */
	protected function _initKeys(): void
	{
		foreach( $this->properties as $property_name => $property_definition ) {
			if( $property_definition->getIsKey() ) {
				$this->addKey(
					$property_name,
					$property_definition->getIsUnique() ? DataModel::KEY_TYPE_UNIQUE : DataModel::KEY_TYPE_INDEX,
					[$property_name]
				);
			}
		}

		if( $this->id_properties ) {
			$this->addKey( $this->model_name . '_pk', DataModel::KEY_TYPE_PRIMARY, $this->id_properties );
		}

		$keys_definition_data = $this->getClassArgument( 'keys', [] );

		foreach( $keys_definition_data as $kd ) {
			$this->addKey( $kd['name'], $kd['type'], $kd['property_names'] );

		}

	}

	/**
	 *
	 */
	public function initRelations(): void
	{
		$this->_initExternalRelations();

		foreach( $this->properties as $property ) {
			if( $property instanceof DataModel_Definition_Property_DataModel ) {
				$property->getValueDataModelDefinition()->initRelations();
			}
		}
	}

	/**
	 *
	 */
	public function _initExternalRelations(): void
	{

		$class = $this->class_name;

		$relations_definitions_data = $this->getClassArgument( 'relations', [] );

		foreach( $relations_definitions_data as $definition_data ) {
			$relation = new DataModel_Definition_Relation_External( $this->getClassName(), $definition_data );

			DataModel_Relations::add(
				$class,
				$relation
			);
		}

	}


	/**
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getModelName(): string
	{
		return $this->model_name;
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
	 * @return DataModel_IDController
	 */
	public function getIDController(): DataModel_IDController
	{
		$id_controller_class = $this->getIDControllerClassName();

		return new $id_controller_class( $this, $this->getIDControllerOptions() );
	}

	/**
	 * @return string
	 */
	public function getIDControllerClassName(): string
	{
		return $this->id_controller_class;
	}

	/**
	 * @return array
	 */
	public function getIDControllerOptions(): array
	{
		return $this->id_controller_options;
	}


	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getIdProperties(): array
	{
		$res = [];

		foreach( $this->id_properties as $id_p ) {
			$res[$id_p] = $this->properties[$id_p];
		}

		return $res;
	}

	/**
	 * @param string $property_name
	 *
	 * @return bool
	 */
	public function hasProperty( string $property_name ): bool
	{
		return isset( $this->properties[$property_name] );
	}

	/**
	 * @param string $property_name
	 *
	 * @return DataModel_Definition_Property
	 */
	public function getProperty( string $property_name ): DataModel_Definition_Property
	{
		return $this->properties[$property_name];
	}

	/**
	 *
	 * @return DataModel_Definition_Property_DataModel[]
	 */
	public function getAllRelatedPropertyDefinitions(): array
	{

		/**
		 * @var DataModel_Definition_Property_DataModel[] $related_definitions
		 */
		$related_definitions = [];
		foreach( $this->getProperties() as $property ) {

			$property->getAllRelatedPropertyDefinitions( $related_definitions );
		}

		return $related_definitions;
	}


	/**
	 * @param string $name
	 * @param string $type
	 * @param array $key_properties
	 *
	 * @throws DataModel_Exception
	 */
	public function addKey( string $name, string $type, array $key_properties ): void
	{

		if( isset( $this->keys[$name] ) ) {
			throw new DataModel_Exception(
				'Class \'' . $this->getClassName() . '\': duplicate key \'' . $name . '\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$my_properties = $this->getProperties();

		foreach( $key_properties as $property_name ) {
			if( !isset( $my_properties[$property_name] ) ) {
				throw new DataModel_Exception(
					'Unknown key property \'' . $property_name . '\'. Class: \'' . $this->class_name . '\' Key: \'' . $name . '\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}
		}

		$this->keys[$name] = new DataModel_Definition_Key( $name, $type, $key_properties );
	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getKeys(): array
	{
		return $this->keys;
	}


	/**
	 * @param string $related_model_name
	 *
	 * @return DataModel_Definition_Relation
	 *
	 * @throws DataModel_Exception
	 */
	public function getRelation( string $related_model_name ): DataModel_Definition_Relation
	{

		$relations = $this->getRelations();

		if( !isset( $relations[$related_model_name] ) ) {

			throw new DataModel_Exception(
				'Unknown relation \'' . $this->getModelName() . '\' <-> \'' . $related_model_name . '\' (Class: \'' . $this->getClassName() . '\') '
			);
		}

		return $relations[$related_model_name];
	}

	/**
	 *
	 *
	 * @return DataModel_Definition_Relation[]
	 */
	public function getRelations(): array
	{
		return DataModel_Relations::get( $this->class_name );
	}

}