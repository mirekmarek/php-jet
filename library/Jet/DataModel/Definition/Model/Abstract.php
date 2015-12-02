<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

abstract class DataModel_Definition_Model_Abstract extends Object {

	/**
	 * DataModel name
	 *
	 * @var string
	 */
	protected $model_name = '';

	/**
	 * Database table name
	 *
	 * @var string
	 */
	protected $database_table_name = '';

	/**
	 * DataModel class name
	 *
	 * @var string
	 */
	protected $class_name = '';

	/**
	 * @var string
	 */
	protected $ID_class_name = '';

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $ID_properties = array();

	
	/**
	 *
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $properties = array();

	/**
	 * @var DataModel_Definition_Key[]
	 */
	protected $keys = array();

	/**
	 * @var DataModel_Definition_Relation_Abstract[]
	 */
	protected $relations;



	/**
	 * @var null|string
	 */
	protected $forced_backend_type;

	/**
	 * @var null|array
	 */
	protected $forced_backend_config;

	/**
	 * @var null|bool
	 */
	protected $forced_cache_enabled;

	/**
	 * @var null|string
	 */
	protected $forced_cache_backend_type;

	/**
	 * @var null|array
	 */
	protected $forced_cache_backend_config;

	/**
	 * @var null|bool
	 */
	protected $forced_history_enabled;

	/**
	 * @var null|string
	 */
	protected $forced_history_backend_type;

	/**
	 * @var null|array
	 */
	protected $forced_history_backend_config;


	/**
	 * @var DataModel_Config
	 */
	protected static $__main_config;

	/**
	 * Backend instance
	 * @see getBackendInstance()
	 *
	 * @var DataModel_Backend_Abstract[]
	 */
	protected static $__backend_instances = array();

	/**
	 * Cache Backend instance
	 * @see getCacheBackendInstance()
	 *
	 * @var DataModel_Cache_Backend_Abstract[]
	 */
	protected static $__cache_backend_instance = array();


	/**
	 *
	 * @var DataModel_Definition_Model_Abstract[]
	 */
	protected static $__definitions = array();

	/**
	 * Returns model definition
	 *
	 * @param string $class_name
	 *
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public static function getDataModelDefinition( $class_name )  {

		$s_class_name = $class_name;
		$file_path = JET_DATAMODEL_DEFINITION_CACHE_PATH.str_replace('\\', '__', $s_class_name.'.php');

		if( JET_DATAMODEL_DEFINITION_CACHE_LOAD ) {

			if(IO_File::isReadable($file_path)) {
				/** @noinspection PhpIncludeInspection */
				$definition = require $file_path;

				static::$__definitions[$s_class_name] = $definition;

				return $definition;
			}
		}


		if( !isset(self::$__definitions[$s_class_name])) {

			/**
			 * @var DataModel $class_name
			 */
			self::$__definitions[$s_class_name] = $class_name::_getDataModelDefinitionInstance($s_class_name);

			if(JET_DATAMODEL_DEFINITION_CACHE_SAVE) {
				try {
					IO_File::write( $file_path, '<?php return '.var_export(self::$__definitions[$s_class_name], true).';' );
				} catch(Exception $e) {}
			}
		}


		return self::$__definitions[$s_class_name];
	}


	/**
	 *
	 * @param $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function  __construct( $data_model_class_name='' ) {

		if($data_model_class_name) {
			$this->_mainInit( $data_model_class_name );
			$this->_initProperties();
			$this->_initKeys();

			if(!$this->ID_properties) {
				throw new DataModel_Exception(
					'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}
	}


	/**
	 * @param $class_name
	 *
	 * @return array
	 * @throws DataModel_Exception
	 */
	protected function _getPropertiesDefinitionData( $class_name ) {
		$properties_definition_data = Object_Reflection::get( $class_name , 'data_model_properties_definition', false);

		if(
			!is_array($properties_definition_data) ||
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
	 * @param $class_name
	 *
	 * @return string
	 * @throws DataModel_Exception
	 */
	protected function _getModelNameDefinition( $class_name ) {
		$model_name = Object_Reflection::get( $class_name, 'data_model_name', '' );

		if(
			!is_string($model_name) ||
			!$model_name
		) {
			throw new DataModel_Exception(
				'DataModel \''.$class_name.'\' does not have model name! Please specify it by @JetDataModel:name ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return $model_name;
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return array
	 * @throws DataModel_Exception
	 */
	protected function _mainInit( $data_model_class_name ) {
		$this->ID_class_name = Object_Reflection::get( $data_model_class_name, 'data_model_ID_class_name', 'Jet\DataModel_ID_Default' );

		$this->class_name = (string)$data_model_class_name;

		/**
		 * @var DataModel $data_model_class_name
		 */
		$this->model_name = $this->_getModelNameDefinition( $data_model_class_name );

		$this->database_table_name = Object_Reflection::get( $data_model_class_name, 'database_table_name', '' );

		if(
			!is_string($this->database_table_name) ||
			!$this->database_table_name
		) {
			throw new DataModel_Exception(
				'DataModel \''.$data_model_class_name.'\' does not have database table name! Please specify it by @JetDataModel:database_table_name ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


	}

	/**
	 *
	 */
	protected function _initBackendsConfig() {
		$this->forced_backend_type = Object_Reflection::get( $this->class_name, 'data_model_forced_backend_type', null );
		$this->forced_backend_config = Object_Reflection::get( $this->class_name, 'data_model_forced_backend_config', null );

		$this->forced_cache_enabled = Object_Reflection::get( $this->class_name, 'data_model_forced_cache_enabled', null );
		$this->forced_cache_backend_type = Object_Reflection::get( $this->class_name, 'data_model_forced_cache_backend_type', null );
		$this->forced_cache_backend_config = Object_Reflection::get( $this->class_name, 'data_model_forced_cache_backend_config', null );

		$this->forced_history_enabled = Object_Reflection::get( $this->class_name, 'data_model_forced_history_enabled', null );
		$this->forced_history_backend_type = Object_Reflection::get( $this->class_name, 'data_model_forced_history_backend_type', null );
		$this->forced_history_backend_config = Object_Reflection::get( $this->class_name, 'data_model_forced_history_backend_config', null );
	}

	/**
	 *
	 */
	protected function _initProperties() {

		$class_name = $this->class_name;

		$properties_definition_data = $this->_getPropertiesDefinitionData($class_name);

		$this->properties = array();

		foreach( $properties_definition_data as $property_name=>$property_dd ) {
			if(isset($property_dd['related_to'])) {
				$property_definition = $this->_initGlueProperty($property_name, $property_dd['related_to'], $property_dd);
			} else {
				$property_definition = DataModel_Factory::getPropertyDefinitionInstance($this->class_name, $property_name, $property_dd);
			}

			if($property_definition->getIsID()) {
				$this->ID_properties[$property_definition->getName()] = $property_definition;
			}

			$property_name = $property_definition->getName();

			$this->properties[$property_name] = $property_definition;

		}

	}

	/**
	 *
	 */
	protected function _initKeys() {
		foreach( $this->properties as $property_name=>$property_definition ) {
			if( $property_definition->getIsKey() ) {
				$this->addKey(
					$property_name,
					$property_definition->getIsUnique() ? DataModel::KEY_TYPE_UNIQUE : DataModel::KEY_TYPE_INDEX,
					array( $property_name )
				);
			}
		}

		if($this->ID_properties) {
			$this->addKey( $this->model_name.'_pk', DataModel::KEY_TYPE_PRIMARY, array_keys($this->ID_properties) );
		}

		$keys_definition_data = Object_Reflection::get( $this->class_name, 'data_model_keys_definition', array());

		foreach( $keys_definition_data as $kd ) {
			$this->addKey( $kd['name'], $kd['type'], $kd['property_names'] );

		}

	}

	/**
	 * @param string $property_name
	 * @param string $related_to
	 * @param array $property_definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property_Abstract
	 *
	 */
	protected function _initGlueProperty( $property_name,
										/** @noinspection PhpUnusedParameterInspection */
	                                      $related_to,
										/** @noinspection PhpUnusedParameterInspection */
										  $property_definition_data ) {
		throw new DataModel_Exception(
			'It is not possible to define related property in Main DataModel  (\''.$this->class_name.'\'::'.$property_name.') ',
			DataModel_Exception::CODE_DEFINITION_NONSENSE
		);

	}


	/**
	 * @return string
	 */
	public function getModelName() {
		return $this->model_name;
	}

	/**
	 * @return string
	 */
	public function getDatabaseTableName() {
		return $this->database_table_name;
	}


	
	/**
	 * Returns DataModel class name
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getIDClassName() {
		return $this->ID_class_name;
	}

	/**
	 * @return DataModel_ID_Abstract
	 */
	public function getEmptyIDInstance() {
		$ID_class_name = $this->getIDClassName();

		return new $ID_class_name( $this );
	}


	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getIDProperties() {
		return $this->ID_properties;
	}


	/**
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * @param string $property_name
	 *
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getProperty( $property_name ) {
		return $this->properties[$property_name];
	}

	/**
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel_Definition_Relation_Abstract[]
	 */
	public function getRelations() {

		if($this->relations!==null) {
			return $this->relations;
		}

		$this->relations = array();

		$class = $this->class_name;

		$relations_definitions_data = Object_Reflection::get( $class, 'data_model_outer_relations_definition', array());

		foreach( $relations_definitions_data as $definition_data ) {
			$relation = new DataModel_Definition_Relation_External( $this, $definition_data );

			$related_model_name = $relation->getRelatedDataModelName();

			$this->addRelation($related_model_name, $relation);
		}


		foreach( $this->properties as $property ) {
			if(!$property->getIsDataModel()) {
				continue;
			}

			/**
			 * @var DataModel_Definition_Model_Related_Abstract $related_data_model_definition
			 * @var DataModel_Definition_Property_DataModel $property
			 */
			$related_data_model_definition = DataModel::getDataModelDefinition( $property->getValueDataModelClass() );

			$internal_relations = $related_data_model_definition->getInternalRelations( $this->class_name );

			foreach( $internal_relations as $related_model_name=>$relation ) {
				$this->addRelation($related_model_name, $relation);
			}


		}

		return $this->relations;
	}

	/**
	 * @param string $related_model_name
	 * @param DataModel_Definition_Relation_Abstract $relation
	 *
	 * @throws DataModel_Exception
	 */
	protected function addRelation( $related_model_name, DataModel_Definition_Relation_Abstract $relation) {
		if(isset($this->relations[ $related_model_name ])) {
			throw new DataModel_Exception(
				'Class \''.$this->getClassName().'\': duplicate relation \''.$related_model_name.'\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->relations[$related_model_name] = $relation;

	}

	/**
	 * @return DataModel_Definition_Key[]
	 */
	public function getKeys() {
		return $this->keys;
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @param array $key_properties
	 *
	 * @throws DataModel_Exception
	 */
	public function addKey( $name, $type, array $key_properties ) {

		if(isset($this->keys[$name])) {
			throw new DataModel_Exception(
				'Class \''.$this->getClassName().'\': duplicate key \''.$name.'\' ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$my_properties = $this->getProperties();

		foreach( $key_properties as $property_name ) {
			if(!isset($my_properties[$property_name])) {
				throw new DataModel_Exception(
					'Unknown key property \''.$property_name.'\'. Class: \''.$this->class_name.'\' Key: \''.$name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}
		}

		$this->keys[$name] = new DataModel_Definition_Key( $name, $type, $key_properties );
	}

	/**
	 * @return array|null
	 */
	public function getForcedBackendConfig() {
		return $this->forced_backend_config;
	}

	/**
	 * @return null|string
	 */
	public function getForcedBackendType() {
		return $this->forced_backend_type;
	}

	/**
	 * @return array|null
	 */
	public function getForcedCacheBackendConfig() {
		return $this->forced_cache_backend_config;
	}

	/**
	 * @return null|string
	 */
	public function getForcedCacheBackendType() {
		return $this->forced_cache_backend_type;
	}

	/**
	 * @return bool|null
	 */
	public function getForcedCacheEnabled() {
		return $this->forced_cache_enabled;
	}

	/**
	 * @return array|null
	 */
	public function getForcedHistoryBackendConfig() {
		return $this->forced_history_backend_config;
	}

	/**
	 * @return null|string
	 */
	public function getForcedHistoryBackendType() {
		return $this->forced_history_backend_type;
	}

	/**
	 * @return bool|null
	 */
	public function getForcedHistoryEnabled() {
		return $this->forced_history_enabled;
	}


	/**
	 * Returns backend type (example: MySQL)
	 *
	 * @return string
	 */
	public function getBackendType() {

		if($this->forced_backend_type!==null) {
			return $this->forced_backend_type;
		}
		return self::_getMainConfig()->getBackendType();
	}

	/**
	 * Returns Backend options
	 *
	 * @return DataModel_Backend_Config_Abstract
	 */
	public function getBackendConfig() {

		if($this->forced_backend_config!==null) {
			$config = DataModel_Factory::getBackendConfigInstance( $this->getBackendType(), true );

			$config->setData(
				$this->forced_backend_config,
				false
			);
		} else {
			$config = DataModel_Factory::getBackendConfigInstance( $this->getBackendType() );

		}
		return $config;
	}

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend_Abstract
	 */
	public function getBackendInstance() {
		$backend_type = $this->getBackendType();
		$backend_config = $this->getBackendConfig();

		$key = $backend_type.':'.md5(serialize($backend_config));

		if(!isset(self::$__backend_instances[$key])) {
			self::$__backend_instances[$key] = DataModel_Factory::getBackendInstance(
				$backend_type,
				$backend_config
			);
			self::$__backend_instances[$key]->initialize();

		}

		return self::$__backend_instances[$key];
	}

	/**
	 *
	 * @return bool
	 */
	public function getCacheEnabled() {

		if($this->forced_cache_enabled!==null) {
			return $this->forced_cache_enabled;
		}
		return static::_getMainConfig()->getCacheEnabled();
	}


	/**
	 * Returns cache backend type (example: MySQL)
	 *
	 * @return string
	 */
	public function getCacheBackendType() {

		if($this->forced_cache_backend_type!==null) {
			return $this->forced_cache_backend_type;
		}

		return static::_getMainConfig()->getCacheBackendType();
	}

	/**
	 * Returns Cache Backend options
	 *
	 * @return DataModel_Cache_Backend_Config_Abstract
	 */
	public function getCacheBackendConfig() {

		if($this->forced_cache_backend_config!==null) {
			$config = DataModel_Factory::getCacheBackendConfigInstance( $this->getCacheBackendType(), true );

			$config->setData( $this->forced_cache_backend_config, false );

		} else {
			$config = DataModel_Factory::getCacheBackendConfigInstance( $this->getCacheBackendType() );
		}

		return $config;
	}

	/**
	 *
	 * @return DataModel_Cache_Backend_Abstract
	 */
	public function getCacheBackendInstance() {
		if(!$this->getCacheEnabled()) {
			return false;
		}

		$backend_type = $this->getCacheBackendType();
		$backend_config = $this->getCacheBackendConfig();

		$key = $backend_type.md5(serialize($backend_config));

		if(!isset(self::$__cache_backend_instance[$key])) {
			self::$__cache_backend_instance[$key] = DataModel_Factory::getCacheBackendInstance(
				$backend_type,
				$backend_config
			);
		}

		return self::$__cache_backend_instance[$key];
	}


	/**
	 *
	 * @return bool
	 */
	public function getHistoryEnabled() {
		if($this->forced_history_enabled!==null) {
			return $this->forced_history_enabled;
		}
		return static::_getMainConfig()->getHistoryEnabled();
	}


	/**
	 * Returns history backend type (example: MySQL)
	 *
	 * @return string
	 */
	public function getHistoryBackendType() {
		if($this->forced_history_backend_type!==null) {
			return $this->forced_history_backend_type;
		}
		return static::_getMainConfig()->getHistoryBackendType();
	}

	/**
	 * Returns history Backend options
	 *
	 * @return DataModel_History_Backend_Config_Abstract
	 */
	public function getHistoryBackendConfig() {

		if($this->forced_history_backend_config!==null) {
			$config = DataModel_Factory::getHistoryBackendConfigInstance( $this->getHistoryBackendType(), true );

			$config->setData( $this->forced_history_backend_config, false );
		} else {
			$config = DataModel_Factory::getHistoryBackendConfigInstance( $this->getHistoryBackendType() );
		}
		return $config;
	}
	

	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep(){
		$this->getRelations();

		return parent::__sleep();
	}

	/**
	 * @param string $class_name
	 * @param array $reflection_data
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( $class_name, &$reflection_data, $key, $definition, $value ) {


		switch($key) {
			case 'key':
				if(
					!is_array($value) ||
					empty($value[0]) ||
					empty($value[1]) ||
					!is_array($value[1]) ||
					!is_string($value[0])
				) {
					throw new Object_Reflection_Exception(
						'Key definition parse error. Class: \''.$class_name.'\', definition: \''.$definition.'\', Example: @JetDataModel:key = [ \'some_key_name\', [ \'some_property_name_1\', \'some_property_name_2\', \'some_property_name_n\' ], Jet\DataModel::KEY_TYPE_INDEX ]',
						Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if(!isset($value[2])) {
					$value[2] = DataModel::KEY_TYPE_INDEX;
				}

				if(
					$value[2]!= DataModel::KEY_TYPE_INDEX &&
					$value[2]!= DataModel::KEY_TYPE_UNIQUE
				) {
					throw new Object_Reflection_Exception(
						'Unknown key type. Class: \''.$class_name.'\', definition: \''.$definition.'\', Use Jet\DataModel::KEY_TYPE_INDEX or Jet\DataModel::KEY_TYPE_UNIQUE',
						Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);
				}

				if( !isset($reflection_data['data_model_keys_definition']) ) {
					$reflection_data['data_model_keys_definition'] = array();
				}

				if(isset( $reflection_data['data_model_keys_definition'][ $value[0] ] )) {
					throw new Object_Reflection_Exception(
						'Duplicate key! Class: \''.$class_name.'\', definition: \''.$definition.'\''
					);

				}

				$reflection_data['data_model_keys_definition'][ $value[0] ] = array(
					'name' => $value[0],
					'type' => $value[1],
					'property_names' => $value[2]
				);


				break;
			case 'relation':
				if(
					!is_array($value) ||
					empty($value[0]) ||
					empty($value[1]) ||
					!is_array($value[1]) ||
					!is_string($value[0])
				) {
					throw new Object_Reflection_Exception(
						'Relation definition parse error. Class: \''.$class_name.'\', definition: \''.$definition.'\', Example: @JetDataModel:relation = [ \'Some\RelatedClass\', [ \'property_name\'=>\'related_property_name\', \'another_property_name\' => \'another_related_property_name\' ], Jet\DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]',
						Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if(!isset($value[2])) {
					$value[2] = DataModel_Query::JOIN_TYPE_LEFT_JOIN;
				}

				if(
					$value[2]!= DataModel_Query::JOIN_TYPE_LEFT_JOIN &&
					$value[2]!= DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN
				) {
					throw new Object_Reflection_Exception(
						'Unknown relation type. Class: \''.$class_name.'\', definition: \''.$definition.'\', Use Jet\DataModel_Query::JOIN_TYPE_LEFT_JOIN or Jet\DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN',
						Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset($reflection_data['data_model_outer_relations_definition']) ) {
					$reflection_data['data_model_outer_relations_definition'] = array();
				}

				if(isset( $reflection_data['data_model_outer_relations_definition'][ $value[0] ] )) {
					throw new Object_Reflection_Exception(
						'Duplicate relation! Class: \''.$class_name.'\', definition: \''.$definition.'\''
					);

				}

				$reflection_data['data_model_outer_relations_definition'][ $value[0] ] = array(
					'related_to_class_name' => $value[0],
					'join_by_properties' => $value[1],
					'join_type' => $value[2]
				);

				return;
				break;
			case 'name':
				if(!empty($reflection_data['data_model_name'])) {
					throw new Object_Reflection_Exception(
						'@Jet_DataModel:model_name is defined by parent and can\'t be overloaded! '
					);

				}
				$reflection_data['data_model_name'] = (string)$value;
				break;
			case 'database_table_name':
				$reflection_data['database_table_name'] = (string)$value;
				break;
			case 'ID_class_name':
				$reflection_data['data_model_ID_class_name'] = (string)$value;
				break;
			case 'parent_model_class_name':
				$reflection_data['data_model_parent_model_class_name'] = (string)$value;
				break;
			case 'forced_backend_type':
				$reflection_data['data_model_forced_backend_type'] = (string)$value;
				break;
			case 'forced_backend_config':
				$reflection_data['data_model_forced_backend_config'] = (array)$value;
				break;
			case 'forced_history_enabled':
				$reflection_data['data_model_forced_history_enabled'] = (bool)$value;
				break;
			case 'forced_history_backend_type':
				$reflection_data['data_model_forced_history_backend_type'] = (string)$value;
				break;
			case 'forced_history_backend_config':
				$reflection_data['data_model_forced_history_backend_config'] = (array)$value;
				break;
			case 'forced_cache_enabled':
				$reflection_data['data_model_forced_cache_enabled'] = (bool)$value;
				break;
			case 'forced_cache_backend_type':
				$reflection_data['data_model_forced_cache_backend_type'] = (string)$value;
				break;
			case 'forced_cache_backend_config':
				$reflection_data['data_model_forced_cache_backend_config'] = (array)$value;
				break;
			case 'M_model_class_name':
				$reflection_data['M_model_class_name'] = (string)$value;
				break;
			case 'N_model_class_name':
				$reflection_data['N_model_class_name'] = (string)$value;
				break;
            case 'default_order_by':

                if(
                    !is_array($value)
                ) {
                    throw new Object_Reflection_Exception(
                        'Relation definition parse error. Class: \''.$class_name.'\', definition: \''.$definition.'\', Example: @JetDataModel:relation = [ \'property_name\' ]',
                        Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
                    );

                }

                $reflection_data['default_order_by'] = $value;
                break;
			default:
				throw new Object_Reflection_Exception(
					'Unknown definition! Class: \''.$class_name.'\', definition: \''.$definition.'\' ',
					Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param $class_name
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 */
	public static function parsePropertyDocComment(
		/** @noinspection PhpUnusedParameterInspection */
		$class_name,
		&$reflection_data,
		$property_name,
		$key,
		$definition,
		$value
	) {

		if(!isset($reflection_data['data_model_properties_definition'])) {
			$reflection_data['data_model_properties_definition'] = array();
		}
		if(!isset($reflection_data['data_model_properties_definition'][$property_name])) {
			$reflection_data['data_model_properties_definition'][$property_name] = array();
		}

		$reflection_data['data_model_properties_definition'][$property_name][$key] = $value;
	}


	/**
	 * Returns DataModel system config instance
	 *
	 * @return DataModel_Config
	 */
	protected static function _getMainConfig() {
		if(!self::$__main_config) {
			self::$__main_config = new DataModel_Config();
		}

		return self::$__main_config;
	}

	/**
	 * @param $data
	 *
	 * @return static
	 */
	public static function __set_state( $data ) {
		$i = new static();

		foreach( $data as $key=>$val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

}