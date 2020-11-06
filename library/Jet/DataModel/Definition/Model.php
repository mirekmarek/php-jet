<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
abstract class DataModel_Definition_Model extends BaseObject
{

	/**
	 *
	 * @var string
	 */
	protected $model_name = '';

	/**
	 *
	 * @var string
	 */
	protected $database_table_name = '';

	/**
	 *
	 * @var string
	 */
	protected $class_name = '';

	/**
	 *
	 * @var string
	 */
	protected $id_controller_class_name = '';

	/**
	 *
	 * @var array
	 */
	protected $id_controller_options = [];

	/**
	 *
	 * @var array
	 */
	protected $id_properties = [];

	/**
	 *
	 * @var DataModel_Definition_Property[]
	 */
	protected $properties = [];

	/**
	 *
	 * @var DataModel_Definition_Key[]
	 */
	protected $keys = [];

	/**
	 *
	 * @var DataModel_Definition_Relation[]
	 */
	protected $relations = [];



	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( $data )
	{
		$i = new static();

		foreach( $data as $key => $val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

	/**
	 *
	 * @param string $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct( $data_model_class_name = '' )
	{

		if( $data_model_class_name ) {
			$this->_mainInit( $data_model_class_name );
		}
	}

	/**
	 * @throws DataModel_Exception
	 */
	public function init()
	{
		$this->_initProperties();
		$this->_initKeys();

		if( !$this->id_properties ) {
			throw new DataModel_Exception(
				'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @throws DataModel_Exception
	 */
	protected function _mainInit( $data_model_class_name )
	{

		$this->class_name = (string)$data_model_class_name;

		$this->model_name = $this->_getModelNameDefinition( $data_model_class_name );

		$this->_initIDController();
		$this->_initDatabaseTableName();
	}

	/**
	 * @param string $class_name
	 *
	 * @return string
	 * @throws DataModel_Exception
	 */
	protected function _getModelNameDefinition( $class_name )
	{
		$model_name = Reflection::get( $class_name, 'data_model_name', '' );

		if(
			!is_string( $model_name ) ||
			!$model_name
		) {
			throw new DataModel_Exception(
				'DataModel \''.$class_name.'\' does not have model name! Please enter it by @JetDataModel:name ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $model_name;
	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initIDController()
	{
		$this->id_controller_class_name = Reflection::get( $this->class_name, 'data_model_id_controller_class_name' );

		if( !$this->id_controller_class_name ) {
			throw new DataModel_Exception(
				'DataModel \''.$this->class_name.'\' does not have ID controller class name! Please enter it by @JetDataModel:id_controller_class_name ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->id_controller_options = Reflection::get( $this->class_name, 'id_controller_options', [] );


	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initDatabaseTableName()
	{
		$this->database_table_name = Reflection::get( $this->class_name, 'database_table_name', '' );

		if(
			!is_string( $this->database_table_name ) ||
			!$this->database_table_name
		) {
			throw new DataModel_Exception(
				'DataModel \''.$this->class_name.'\' does not have database table name! Please enter it by @JetDataModel:database_table_name ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

	}

	/**
	 *
	 */
	protected function _initProperties()
	{

		$class_name = $this->class_name;

		$properties_definition_data = $this->_getPropertiesDefinitionData( $class_name );

		$this->properties = [];

		foreach( $properties_definition_data as $property_name => $property_dd ) {

			if( isset( $property_dd['related_to'] ) ) {
				$property_definition = $this->_initRelationProperty(
					$property_name,
					$property_dd['related_to'],
					$property_dd
				);
			} else {
				$property_definition = DataModel_Factory::getPropertyDefinitionInstance(
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
	 * @param string $class_name
	 *
	 * @return array
	 * @throws DataModel_Exception
	 */
	protected function _getPropertiesDefinitionData( $class_name )
	{
		$properties_definition_data = Reflection::get( $class_name, 'data_model_properties_definition', false );

		if(
			!is_array( $properties_definition_data ) ||
			!$properties_definition_data
		) {
			throw new DataModel_Exception(
				'DataModel \''.$class_name.'\' does not have any properties defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $properties_definition_data;
	}

	/**
	 * @param string $property_name
	 * @param string $related_to
	 * @param array  $property_definition_data
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Property
	 *
	 */
	protected function _initRelationProperty( $property_name, /** @noinspection PhpUnusedParameterInspection */
	                                          $related_to, /** @noinspection PhpUnusedParameterInspection */
	                                          $property_definition_data )
	{
		throw new DataModel_Exception(
			'It is not possible to define related property in Main DataModel  (\''.$this->class_name.'\'::'.$property_name.') ',
			DataModel_Exception::CODE_DEFINITION_NONSENSE
		);

		/** @noinspection PhpUnreachableStatementInspection */
		return null;
	}

	/**
	 *
	 */
	protected function _initKeys()
	{
		foreach( $this->properties as $property_name => $property_definition ) {
			if( $property_definition->getIsKey() ) {
				$this->addKey(
					$property_name,
					$property_definition->getIsUnique() ? DataModel::KEY_TYPE_UNIQUE : DataModel::KEY_TYPE_INDEX,
					[ $property_name ]
				);
			}
		}

		if( $this->id_properties ) {
			$this->addKey( $this->model_name.'_pk', DataModel::KEY_TYPE_PRIMARY, $this->id_properties );
		}

		$keys_definition_data = Reflection::get( $this->class_name, 'data_model_keys_definition', [] );

		foreach( $keys_definition_data as $kd ) {
			$this->addKey( $kd['name'], $kd['type'], $kd['property_names'] );

		}

	}

	/**
	 *
	 */
	public function initRelations()
	{
		$this->_initExternalRelations();

		foreach( $this->properties as $property ) {
			if($property instanceof DataModel_Definition_Property_DataModel) {
				$property->getValueDataModelDefinition()->initRelations();
			}
		}
	}

	/**
	 *
	 */
	public function _initExternalRelations()
	{

		$class = $this->class_name;

		$relations_definitions_data = Reflection::get( $class, 'data_model_outer_relations_definition', [] );

		foreach( $relations_definitions_data as $definition_data ) {
			$relation = new DataModel_Definition_Relation_External( $this->getClassName(), $definition_data );

			DataModel_Relations::add(
				$class,
				$relation
			);
		}

	}


	/**
	 * Returns DataModel class name
	 *
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getModelName()
	{
		return $this->model_name;
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
	public function setDatabaseTableName( $database_table_name )
	{
		$this->database_table_name = $database_table_name;
	}

	/**
	 * @return DataModel_IDController
	 */
	public function getIDController()
	{
		$id_controller_class_name = $this->getIDControllerClassName();

		/**
		 * @var DataModel_IDController $empty_id
		 */
		$empty_id = new $id_controller_class_name( $this, $this->getIDControllerOptions() );


		return $empty_id;
	}

	/**
	 * @return string
	 */
	public function getIDControllerClassName()
	{
		return $this->id_controller_class_name;
	}

	/**
	 * @return array
	 */
	public function getIDControllerOptions()
	{
		return $this->id_controller_options;
	}


	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getIdProperties()
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
	public function hasProperty( $property_name )
	{
		return isset( $this->properties[$property_name] );
	}

	/**
	 * @param string $property_name
	 *
	 * @return DataModel_Definition_Property
	 */
	public function getProperty( $property_name )
	{
		return $this->properties[$property_name];
	}

	/**
	 *
	 * @return array|DataModel_Definition_Property_DataModel[]
	 */
	public function getAllRelatedPropertyDefinitions()
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
	 * @return DataModel_Definition_Key[]
	 */
	public function getKeys()
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
	public function getRelation( $related_model_name )
	{

		$relations = $this->getRelations();

		if( !isset( $relations[$related_model_name] ) ) {

			throw new DataModel_Exception(
				'Unknown relation \''.$this->getModelName().'\' <-> \''.$related_model_name.'\' (Class: \''.$this->getClassName().'\') '
			);
		}

		return $relations[$related_model_name];
	}

	/**
	 *
	 *
	 * @return DataModel_Definition_Relation[]
	 */
	public function getRelations()
	{
		return DataModel_Relations::get( $this->class_name );
	}

}