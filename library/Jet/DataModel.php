<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 */
namespace Jet;

/**
 * //TODO: update comments
 *
 *	Common options
 *		'type':
 *		'default_value':
 *		'backend_options':
 *              'is_ID',
 *              'do_not_serialize':
 *              'description':
 *
 *      Form options:
 *              'form_field_type':
 *              'form_field_label':
 *              'form_field_options':
 *              'form_field_error_messages':
 *              'form_field_get_default_value_callback':
 *              'form_field_get_select_options_callback':
 *
 *	Data validation options
 *		All types:
 *			'validation_method':
 *			'list_of_valid_options':
 *			'error_messages':
 *
 *		TYPE_STRING:
 *			'is_required':
 *			'max_len':
 *			'validation_regexp':
 *
 *		TYPE_INT,TYPE_FLOAT:
 *			'min_value':
 *			'max_value':
 *
 *
 *	Type specific options
 *		TYPE_DATA_MODEL:
 *			'data_model_class'
 *		TYPE_ARRAY
 *			'item_type':
 *
 *
 * Relations to another (indenpendent) model.
 *
 * Example:
 *
 * JetDataModel:relation = [ 'Some\RelatedClass', [ 'this.class_property_name'=>'related_class_property_name', 'this.another_class_property_name' => 'another_related_class_property_name', 'this_value.getValueMethodName' => 'another_related_class_property' ], Jet\DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]
 *
 *
 * Then you can use relation in query like this:
 *
 * $query = array(
 *          'relation_name.some_related_class_property' => 'value',
 *          'AND',
 *          'relation_name.another_some_related_class_property!' => 1234
 * );
 *
 * Warning!
 *
 * Outer relation has no affect on saving or deleting object (like DataModel_Related_* models has).
 *
 *
 * @var array
 */



/**
 * Class DataModel
 *
 * @JetDataModel:ID_class_name = 'Jet\\DataModel_ID_Default'
 */
abstract class DataModel extends Object implements Object_Serializable_REST, Object_Reflection_ParserInterface {
	const DEFAULT_ID_COLUMN_NAME = 'ID';

	const TYPE_ID = 'ID';
	const TYPE_STRING = 'String';
	const TYPE_BOOL = 'Bool';
	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_LOCALE = 'Locale';
	const TYPE_DATE = 'Date';
	const TYPE_DATE_TIME = 'DateTime';
	const TYPE_ARRAY = 'Array';
	const TYPE_DATA_MODEL = 'DataModel';

	const KEY_TYPE_PRIMARY = 'PRIMARY';
	const KEY_TYPE_INDEX = 'INDEX';
	const KEY_TYPE_UNIQUE = 'UNIQUE';
	//const KEY_TYPE_FULLTEXT = 'FULLTEXT';


	/**
	 * @var string|null
	 */
	protected $___data_model_saved = false;
	/**
	 * @var string|null
	 */
	protected $___data_model_ready_to_save = false;


	/**
	 * Backend instance
	 * @see getBackendInstance()
	 *
	 * @var DataModel_Backend_Abstract[]
	 */
	protected static $___data_model_backend_instance = array();

	/**
	 * Cache Backend instance
	 * @see getCacheBackendInstance()
	 *
	 * @var DataModel_Cache_Backend_Abstract[]
	 */
	protected static $___data_model_cache_backend_instance = array();


	/**
	 *
	 * @var DataModel_History_Backend_Abstract
	 */
	protected $___data_model_history_backend_instance = null;

	/**
	 *
	 * @var DataModel_Validation_Error[]
	 */
	protected $___data_model_data_validation_errors = array();

	/**
	 *
	 * @var DataModel_Definition_Model_Abstract[]
	 */
	protected static $___data_model_definitions = array();

	/**
	 * @var DataModel_Definition_Relation_External[]
	 */
	protected static $___data_model_outer_relations_definitions = array();

	/**
	 * @var DataModel_Config
	 */
	protected static $___data_model_main_config;

	/**
	 * Returns DataModel system config instance
	 *
	 * @return DataModel_Config
	 */
	protected static function __DataModelGetMainConfig() {
		if(!static::$___data_model_main_config) {
			static::$___data_model_main_config = new DataModel_Config();
		}

		return static::$___data_model_main_config;
	}

	/**
	 * Returns model definition
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public static function getDataModelDefinition( $class_name='' )  {
		if($class_name) {
			$class_name = Factory::getClassName( $class_name );

			/**
			 * @var DataModel $class_name
			 */
			return $class_name::getDataModelDefinition();
		}

		$class = get_called_class();

		if( !isset(self::$___data_model_definitions[$class])) {
			self::$___data_model_definitions[$class] = new DataModel_Definition_Model_Main( $class );
		}
		return self::$___data_model_definitions[$class];
	}

	/**
	 *
	 *
	 * @return DataModel_Definition_Relation_External[]
	 */
	public static function getDataModelOuterRelationsDefinition()  {
		$class = get_called_class();

		if( !isset(self::$___data_model_outer_relations_definitions[$class])) {
			self::$___data_model_outer_relations_definitions[$class] = array();

			/**
			 * @var DataModel $class
			 */
			$definitions_data = $class::getDataModelOuterRelationsDefinitionData();

			foreach( $definitions_data as $definition_data ) {

				self::$___data_model_outer_relations_definitions[$class][] = new DataModel_Definition_Relation_External(
															$definition_data
														);
			}

		}
		return self::$___data_model_outer_relations_definitions[$class];
	}

	/**
	 *
	 * @return array
	 */
	public static function getDataModelName() {
		return Object_Reflection::get( get_called_class(), 'data_model_name', '' );
	}

	/**
	 *
	 * @return array
	 */
	public static function getDbTableName() {
		return Object_Reflection::get( get_called_class(), 'database_table_name', '' );
	}


	/**
	 * Returns properties definition data (used for DataModel_Definition_Model_Abstract::_mainInit)
	 *
	 * @return array
	 */
	public static function getDataModelPropertiesDefinitionData() {
		return Object_Reflection::get( get_called_class() , 'data_model_properties_definition', false);
	}

	/**
	 * Returns properties definition data (used for DataModel_Definition_Model_Abstract::_mainInit)
	 *
	 * @return array
	 */
	public static function getDataModelOuterRelationsDefinitionData() {
		return Object_Reflection::get( get_called_class(), 'data_model_outer_relations_definition', array());
	}

	/**
	 * Returns backend type (example: MySQL)
	 *
	 * @return string
	 */
	public static function getBackendType() {
		$forced_backend_type = Object_Reflection::get( get_called_class(), 'data_model_forced_backend_type', null );

		if($forced_backend_type!==null) {
			return $forced_backend_type;
		}
		return static::__DataModelGetMainConfig()->getBackendType();
	}

	/**
	 * Returns Backend options
	 *
	 * @return DataModel_Backend_Config_Abstract
	 */
	public static function getBackendConfig() {

		$forced_backend_config = Object_Reflection::get( get_called_class(), 'data_model_forced_backend_config', null );

		if($forced_backend_config!==null) {
			$config = DataModel_Factory::getBackendConfigInstance( static::getBackendType(), true );

			$config->setData(
				$forced_backend_config,
				false
			);
		} else {
			$config = DataModel_Factory::getBackendConfigInstance( static::getBackendType() );

		}
		return $config;
	}

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend_Abstract
	 */
	public static function getBackendInstance() {
		$backend_type = static::getBackendType();
		$backend_config = static::getBackendConfig();

		$key = $backend_type.':'.md5(serialize($backend_config));

		if(!isset(self::$___data_model_backend_instance[$key])) {
			self::$___data_model_backend_instance[$key] = DataModel_Factory::getBackendInstance(
				$backend_type,
				$backend_config
			);
			self::$___data_model_backend_instance[$key]->initialize();

		}

		return self::$___data_model_backend_instance[$key];
	}

	/**
	 *
	 * @return bool
	 */
	public static function getCacheEnabled() {
		$forced_cache_enabled = Object_Reflection::get( get_called_class(), 'data_model_forced_cache_enabled', null );

		if($forced_cache_enabled!==null) {
			return $forced_cache_enabled;
		}
		return static::__DataModelGetMainConfig()->getCacheEnabled();
	}


	/**
	 * Returns cache backend type (example: MySQL)
	 *
	 * @return string
	 */
	public static function getCacheBackendType() {
		$forced_cache_backend_type = Object_Reflection::get( get_called_class(), 'data_model_forced_cache_backend_type', null );

		if($forced_cache_backend_type!==null) {
			return $forced_cache_backend_type;
		}

		return static::__DataModelGetMainConfig()->getCacheBackendType();
	}

	/**
	 * Returns Cache Backend options
	 *
	 * @return DataModel_Cache_Backend_Config_Abstract
	 */
	public static function getCacheBackendConfig() {
		$forced_cache_backend_config = Object_Reflection::get( get_called_class(), 'data_model_forced_cache_backend_config', null );


		if($forced_cache_backend_config!==null) {
			$config = DataModel_Factory::getCacheBackendConfigInstance( static::getCacheBackendType(), true );

			$config->setData( $forced_cache_backend_config, false );

		} else {
			$config = DataModel_Factory::getCacheBackendConfigInstance( static::getCacheBackendType() );
		}

		return $config;
	}

	/**
	 * Returns cache backend instance
	 *
	 * @return DataModel_Cache_Backend_Abstract
	 */
	public static function getCacheBackendInstance() {
		if(!static::getCacheEnabled()) {
			return false;
		}

		$backend_type = static::getCacheBackendType();
		$backend_config = static::getCacheBackendConfig();

		$key = $backend_type.md5(serialize($backend_config));

		if(!isset(self::$___data_model_cache_backend_instance[$key])) {
			self::$___data_model_cache_backend_instance[$key] = DataModel_Factory::getCacheBackendInstance(
				$backend_type,
				$backend_config
			);
		}

		return self::$___data_model_cache_backend_instance[$key];
	}


	/**
	 *
	 * @return bool
	 */
	public static function getHistoryEnabled() {
		$force_history_enabled = Object_Reflection::get( get_called_class(), 'data_model_forced_history_enabled', null );

		if($force_history_enabled!==null) {
			return $force_history_enabled;
		}
		return static::__DataModelGetMainConfig()->getHistoryEnabled();
	}


	/**
	 * Returns history backend type (example: MySQL)
	 *
	 * @return string
	 */
	public static function getHistoryBackendType() {
		$forced_history_backend_type = Object_Reflection::get( get_called_class(), 'data_model_forced_history_backend_type', null );

		if($forced_history_backend_type!==null) {
			return $forced_history_backend_type;
		}
		return static::__DataModelGetMainConfig()->getHistoryBackendType();
	}

	/**
	 * Returns history Backend options
	 *
	 * @return DataModel_History_Backend_Config_Abstract
	 */
	public static function getHistoryBackendConfig() {

		$forced_history_backend_config = Object_Reflection::get( get_called_class(), 'data_model_forced_history_backend_config', null );

		if($forced_history_backend_config!==null) {
			$config = DataModel_Factory::getHistoryBackendConfigInstance( self::getHistoryBackendType(), true );

			$config->setData( $forced_history_backend_config, false );
		} else {
			$config = DataModel_Factory::getHistoryBackendConfigInstance( self::getHistoryBackendType() );
		}
		return $config;
	}


	/**
	 * Returns history backend instance
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public function getHistoryBackendInstance() {
		if(!static::getHistoryEnabled()) {
			return false;
		}

		if(!$this->___data_model_history_backend_instance) {
			$this->___data_model_history_backend_instance = DataModel_Factory::getHistoryBackendInstance(
				static::getHistoryBackendType(),
				static::getHistoryBackendConfig()
			);

		}

		return $this->___data_model_history_backend_instance;

	}



	/**
	 * Returns ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function getID() {
		$result = $this->getEmptyIDInstance();

		foreach($result as $property_name => $value) {
			$result[$property_name] = $this->{$property_name};
		}

		return $result;
	}

	/**
	 * @return DataModel_ID_Abstract
	 */
	public function getEmptyIDInstance() {
		$class_name = Object_Reflection::get( get_class($this), 'data_model_ID_class_name', 'Jet\\DataModel_ID_Default' );
		return new $class_name( $this );
	}

	/**
	 * Returns ID properties names
	 *
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getIDProperties() {
		return $this->getDataModelDefinition()->getIDProperties();
	}


	/**
	 * @param DataModel_ID_Abstract|string $ID
	 * @return bool
	 */
	public function getIDExists( $ID ) {
		if( !($ID instanceof DataModel_ID_Abstract) ) {
			$ID = $this->getEmptyIDInstance()->unserialize($ID);
		}

		$query = new DataModel_Query( $this );
		$query->setWhere(array());
		$where = $query->getWhere();

		foreach($this->getIDProperties() as $pr_property_name => $pr_property) {
			$value = $ID[$pr_property_name];

			if($value===null) {
				continue;
			}

			$where->addAND();
			$where->addExpression( $pr_property, DataModel_Query::O_EQUAL, $value);
		}

		return (bool)$this->getBackendInstance()->getCount( $query );
	}

	/**
	 *
	 * @param DataModel_Definition_Property_Abstract[] $relation_ID_properties (optional)
	 *
	 * @return DataModel_Query
	 */
	public function getIDQuery( $relation_ID_properties=null ) {
		$query = new DataModel_Query( $this );
		$query->setWhere(array());
		$where = $query->getWhere();

		if($relation_ID_properties) {
			foreach($relation_ID_properties as $pr_property) {
				/**
				 * @var DataModel_Definition_Property_Abstract $pr_property
				 * @var DataModel_Definition_Property_Abstract $rt_property
				 */
				$rt_property = $pr_property->getRelatedToProperty();
				$pr_property_name = $rt_property->getName();
				$value = $this->{$pr_property_name};

				if($value===null)  {
					continue;
				}

				$where->addAND();
				$where->addExpression( $pr_property, DataModel_Query::O_EQUAL, $value);
			}

		} else {
			foreach($this->getIDProperties() as $pr_property_name => $pr_property) {
				$value = $this->$pr_property_name;

				if($value===null) {
					continue;
				}

				$where->addAND();
				$where->addExpression( $pr_property, DataModel_Query::O_EQUAL, $value);
			}
		}

		return $query;
	}


	/**
	 * Generate unique ID
	 *
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 * @throws DataModel_Exception
	 */
	protected function generateID(  /** @noinspection PhpUnusedParameterInspection */
		$called_after_save = false, $backend_save_result = null  ) {


		$ID_properties = $this->getDataModelDefinition()->getIDProperties();
		if(isset($ID_properties[static::DEFAULT_ID_COLUMN_NAME])) {
			$property_name = static::DEFAULT_ID_COLUMN_NAME;
			if(!$this->{$property_name}) {
				$this->{$property_name} = DataModel_ID_Abstract::generateUniqueID();
			}

			return;
		}

		throw new DataModel_Exception(
			'Unable to generate ID. There are two solutions: 1) There must be at least one property that has set type ID  2) Overload '.get_class($this).'::generateID method',
			DataModel_Exception::CODE_DEFINITION_NONSENSE
		);

	}


	/**
	 * Returns true if the model instance is new (was not saved yet)
	 *
	 * @return bool
	 */
	public function getIsNew() {
		return !$this->___data_model_saved;
	}

	/**
	 * Initializes new DataModel
	 *
	 */
	public function initNewObject() {
		$this->___data_model_ready_to_save = false;
		$this->___data_model_saved = false;

		foreach( $this->getDataModelDefinition()->getProperties() as $property_name => $property_definition ) {

			$this->{$property_name} = $property_definition->getDefaultValue();
			$property_definition->checkValueType( $this->{$property_name} );
		}

		$this->generateID();
	}

	/**
	 * @param string $property_name
	 * @param mixed &$value
	 * @param bool $throw_exception (optional, default: true)
	 *
	 * @throws DataModel_Exception
	 * @throws DataModel_Validation_Exception
	 *
	 * @return bool
	 */
	public function validatePropertyValue( $property_name,&$value, $throw_exception=true ) {
		$properties = $this->getDataModelDefinition()->getProperties();
		if( !isset($properties[$property_name]) ) {
			throw new DataModel_Exception(
				'Unknown property \''.$property_name.'\'',
				DataModel_Exception::CODE_UNKNOWN_PROPERTY
			);
		}

		$property_definition = $properties[$property_name];

		$validation_method_name = $property_definition->getValidationMethodName();

		$errors = array();

		if($validation_method_name) {
			$this->{$validation_method_name}($property_definition, $value, $errors);
		} else {
			$property_definition->validateProperties($value, $errors);
		}

		if($errors) {
			if($throw_exception) {
				throw new DataModel_Validation_Exception( $this, $property_definition, $errors );
			} else {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $property_name
	 * @param mixed &$value
	 *
	 * @throws DataModel_Validation_Exception
	 */
	protected  function _setPropertyValue( $property_name, &$value ) {
		$this->validatePropertyValue( $property_name, $value );

		$this->{$property_name} = $value;
	}


	/**
	 * Validates data and returns true if everything is OK and ready to save
	 *
	 * @throws DataModel_Exception
	 * @return bool
	 */
	public function validateProperties() {

		$this->___data_model_data_validation_errors = array();

		$this->___data_model_ready_to_save = false;

		foreach( $this->getDataModelDefinition()->getProperties()  as $property_name=>$property_definition ) {
			if(
				$property_definition->getIsDataModel() &&
				$this->{$property_name}
			) {
				if(!is_object($this->{$property_name})) {

					throw new DataModel_Exception(
						get_class($this).'::'.$property_name.' should be an Object! ',
						DataModel_Exception::CODE_INVALID_PROPERTY_TYPE
					);
				}

				/**
				 * @var DataModel $prop
				 */
				$prop = $this->{$property_name};

				$prop->validateProperties();

				$this->___data_model_data_validation_errors = array_merge(
						$this->___data_model_data_validation_errors,
						$prop->getValidationErrors()
					);

				continue;
			}

			$validation_method_name = $property_definition->getValidationMethodName();

			if($validation_method_name) {
				$this->{$validation_method_name}($property_definition, $this->{$property_name}, $this->___data_model_data_validation_errors);
			} else {
				$property_definition->validateProperties($this->{$property_name}, $this->___data_model_data_validation_errors);
			}
		}

		if(count($this->___data_model_data_validation_errors)) {
			return false;
		}

		$this->___data_model_ready_to_save = true;

		return true;
	}

	/**
	 *
	 * @return DataModel_Validation_Error[]
	 */
	public function getValidationErrors() {
		return $this->___data_model_data_validation_errors;
	}

	/**
	 * Loads DataModel.
	 *
	 * @param DataModel_ID_Abstract $ID
	 *
	 * @throws DataModel_Exception
	 * @return DataModel
	 */
	public function load( DataModel_ID_Abstract $ID ) {

		$cache = $this->getCacheBackendInstance();

		$loaded_instance = null;

		if($cache) {
			$loaded_instance = $cache->get( $this, $ID);
		}

		if(!$loaded_instance) {

			$query = new DataModel_Query( $this );

			$query->setSelect( $this->getDataModelDefinition()->getProperties() );
			$query->setWhere( $ID->getWhere() );

			$data = $this->getBackendInstance()->fetchAll( $query );

			if(!$data) {
				return null;
			}

			list($dat) = $data;

			$loaded_instance = $this->_load_dataToInstance( $dat );

			if($cache) {
				$cache->save($this, $ID, $loaded_instance);
			}
		}


		return $loaded_instance;

	}

	/**
	 * @param array $dat
	 * @param DataModel $main_model_instance
	 *
	 * @return DataModel
	 *
	 * @throws DataModel_Exception
	 */
	protected function _load_dataToInstance( $dat, $main_model_instance=null ) {

		/**
		 * @var DataModel $loaded_instance
		 */
		$loaded_instance = new static();

		foreach( $this->getDataModelDefinition()->getProperties() as $property_name=>$property_definition ) {
			if($property_definition->getIsDataModel()) {
				continue;
			}
			$loaded_instance->$property_name = $dat[$property_name];
			$property_definition->checkValueType( $loaded_instance->$property_name );
		}


		foreach( $this->getDataModelDefinition()->getProperties() as $property_name=>$property_definition ) {
			if(!$property_definition->getIsDataModel()) {
				continue;
			}
			/**
			 * @var DataModel_Definition_Property_DataModel $property_definition
			 */
			$class_name = $property_definition->getDataModelClass();

			/**
			 * @var DataModel_Related_Abstract $related_instance
			 */
			$related_instance = Factory::getInstance( $class_name );

			if(
				!($related_instance instanceof DataModel_Related_Abstract) &&
				!($related_instance instanceof DataModel_Related_MtoN)
			) {
				throw new DataModel_Exception(
					'DataModel \''.get_class($related_instance).'\' is related class to  \''.get_class($loaded_instance).'\' but is not instance of  DataModel_Related*',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			if($main_model_instance) {
				/**
				 * @var DataModel_Related_Abstract $loaded_instance
				 */
				$loaded_instance->{$property_name} = $related_instance->loadRelated( $main_model_instance, $loaded_instance );
			} else {
				/**
				 * @var DataModel $loaded_instance
				 */
				$loaded_instance->{$property_name} = $related_instance->loadRelated( $loaded_instance );
			}
		}


		$loaded_instance->___data_model_saved = true;

		return $loaded_instance;
	}

	/**
	 * Save data.
	 * CAUTION: Call validateProperties first!
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save() {

		$this->_checkBeforeSave();

		$cache = $this->getCacheBackendInstance();
		$backend = $this->getBackendInstance();

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionStart();
		}


		if($this->getSaveAsNew()) {
			$operation = 'save';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_SAVE;
		} else {
			$operation = 'update';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_UPDATE;
		}

		$this->___DataModelHistoryOperationStart( $h_operation );


		try {
			$this->{'_'.$operation}( $backend );
		} catch (Exception $e) {
			$backend->transactionRollback();
			throw $e;
		}

		if($cache) {
			$cache->{$operation}($this, $this->getID(), $this);
		}

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionCommit();
		}

		$this->___data_model_saved = true;

		$this->___DataModelHistoryOperationDone();

	}

	/**
	 * @return bool
	 */
	protected function getSaveAsNew() {
		if( !$this->___data_model_saved ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _checkBeforeSave() {
		if(!$this->___data_model_ready_to_save) {

			$errors = $this->getValidationErrors();
			foreach($errors as $i=>$e) {
				$errors[$i] = (string)$e;
			}

			if(!$errors) {
				$errors[] = 'none';
			}

			throw new DataModel_Exception(
				'Call '.get_class($this).'::validateProperties first! (Validation errors: '.implode(',', $errors).')',
				DataModel_Exception::CODE_SAVE_ERROR_VALIDATE_DATA_FIRST
			);
		}
	}

	/**
	 *
	 * @param DataModel_Backend_Abstract $backend
	 * @param DataModel $main_model_instance
	 */
	protected function _save( DataModel_Backend_Abstract $backend, DataModel $main_model_instance=null ) {
		$definition = $this->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$related_model_properties = array();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( $property_definition->getIsDataModel() ) {
				$related_model_properties[$property_name]  = $property_definition;

				continue;
			}

			$record->addItem($property_definition, $this->{$property_name});
		}

		$backend_result = $backend->save( $record );

		$this->generateID( true, $backend_result );

		if(!$main_model_instance) {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					$prop->saveRelated( $this );
				}
			}
		} else {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					/**
					 * @var DataModel_Related_Abstract $this
					 */
					$prop->saveRelated( $main_model_instance, $this );
				}
			}
		}
	}

	/**
	 *
	 * @param DataModel_Backend_Abstract $backend
	 * @param DataModel $main_model_instance
	 */
	protected function _update( DataModel_Backend_Abstract $backend, DataModel $main_model_instance=null ) {
		$definition = $this->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$related_model_properties = array();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if($property_definition->getIsID()) {
				continue;
			}

			if( $property_definition->getIsDataModel() ) {
				$related_model_properties[$property_name]  = $property_definition;

				continue;
			}

			$record->addItem($property_definition, $this->{$property_name});
		}

		$backend->update($record, $this->getIDQuery());

		if(!$main_model_instance) {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					$prop->saveRelated( $this );
				}
			}
		} else {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					/**
					 * @var DataModel_Related_Abstract $this
					 */
					$prop->saveRelated( $main_model_instance, $this );
				}
			}

		}
	}


	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() {
		if( !$this->getID() || !$this->___data_model_saved ) {
			throw new DataModel_Exception('Nothing to delete... Object was not loaded.', DataModel_Exception::CODE_NOTHING_TO_DELETE);
		}

		$this->___DataModelHistoryOperationStart( DataModel_History_Backend_Abstract::OPERATION_DELETE );

		$backend = $this->getBackendInstance();
		$definition = $this->getDataModelDefinition();

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionStart();
		}

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if(
				$property_definition->getIsDataModel() &&
				$this->{$property_name}
			) {
				/**
				 * @var DataModel $prop
				 */
				$prop = $this->{$property_name};

				$prop->delete();
			}
		}

		$backend->delete( $this->getIDQuery() );

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionCommit();
		}

		$this->___DataModelHistoryOperationDone();

		$cache = $this->getCacheBackendInstance();
		if($cache) {
			$cache->delete( $this, $this->getID() );
		}
 	}

	/**
	 * @param array $data
	 * @param array $where
	 */
	protected function updateData( array $data, array $where ) {
		$cache_enabled = $this->getCacheEnabled();

		$affected_IDs = null;
		if($cache_enabled) {
			$affected_IDs = $this->fetchObjectIDs($where);
		}

		$this->getBackendInstance()->update(
			DataModel_RecordData::createRecordData( $this,
				$data
			),
			DataModel_Query::createQuery( $this,
				$where
			)
		);

		if($affected_IDs) {
			$cache = $this->getCacheBackendInstance();
			foreach($affected_IDs as $ID) {
				$cache->delete( $this, $ID );
			}
		}
	}

	/**
	 * @param string $operation
	 */
	protected function ___DataModelHistoryOperationStart( $operation ) {
		$backend = $this->getHistoryBackendInstance();

		if( !$backend ) {
			return;
		}

		$backend->operationStart( $this, $operation );
	}

	/**
	 *
	 */
	protected function ___DataModelHistoryOperationDone() {
		$backend = $this->getHistoryBackendInstance();

		if( !$backend ) {
			return;
		}
		$backend->operationDone();
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $related_model_definition
	 * @param DataModel_Definition_Property_Abstract $related_model_ID_property_definition
	 *
	 * @return string
	 */
	public static function getRelationIDPropertyName(
			DataModel_Definition_Model_Abstract $related_model_definition,
			DataModel_Definition_Property_Abstract $related_model_ID_property_definition
		) {
			return $related_model_definition->getModelName()
				.'_'
				.$related_model_ID_property_definition->getName();
	}


	/**
	 *
	 * @param array| $query
	 * @return DataModel
	 */
	protected function fetchOneObject( array $query ) {
		$fetch = new DataModel_Fetch_Object_Assoc( $query, $this );
		$fetch->getQuery()->setLimit(1);

		foreach($fetch as $object) {
			return $object;
		}

		return false;
	}

	/**
	 *
	 * @param array $query
	 * @return DataModel_Fetch_Object_Assoc
	 */
	protected function fetchObjects( array  $query=array() ) {
		return new DataModel_Fetch_Object_Assoc( $query, $this );
	}

	/**
	 *
	 * @param array $query
	 * @return DataModel_Fetch_Object_IDs
	 */
	protected function fetchObjectIDs( array $query=array() ) {
		return new DataModel_Fetch_Object_IDs( $query, $this );
	}


	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return DataModel_Fetch_Data_All
	 */
	protected function fetchDataAll( array $load_items, array  $query=array() ) {
		return new DataModel_Fetch_Data_All( $load_items, $query, $this );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return DataModel_Fetch_Data_Assoc
	 */
	protected function fetchDataAssoc( array $load_items, array  $query=array() ) {
		return new DataModel_Fetch_Data_Assoc( $load_items, $query, $this );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return DataModel_Fetch_Data_Pairs
	 */
	protected function fetchDataPairs( array $load_items, array  $query=array() ) {
		return new DataModel_Fetch_Data_Pairs( $load_items, $query, $this );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return mixed|null
	 */
	protected function fetchDataRow( array $load_items, array  $query=array() ) {
		$query = DataModel_Query::createQuery($this, $query);
		$query->setSelect($load_items);

		return $this->getBackendInstance()->fetchRow( $query );

	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 *
	 * @return mixed|null
	 */
	protected function fetchDataOne( array $load_items, array  $query=array() ) {
		$query = DataModel_Query::createQuery($this, $query);
		$query->setSelect($load_items);

		return $this->getBackendInstance()->fetchOne( $query );
	}

	/**
	 *
	 * @param string $form_name
	 * @param array $only_properties
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $only_properties ) {
		$definition = $this->getDataModelDefinition();

		$fields = array();

		foreach($definition->getProperties() as $property_name=>$property) {
			if( !in_array($property_name, $only_properties) ) {
				continue;
			}

			$field = $property->getFormField();
			if(!$field) {
				$class = $definition->getClassName();

				throw new DataModel_Exception(
					'The property '.$class.'::'.$property.' is required for form definition. But property definition '.get_class($property).' prohibits the use of property as form field. ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}

			if(!$this->getIsNew()) {
				$field->setDefaultValue( $this->{$property->getName()} );
			} else {
				$field->setDefaultValue( $property->getDefaultValue() );
			}

			$fields[] = $field;
		}

		return new Form( $form_name, $fields );

	}

	/**
	 * @param string $form_name
	 * @param bool $skip_hidden_fields (optional, default=false)
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name='', $skip_hidden_fields=false ) {
		$definition = $this->getDataModelDefinition();


		$only_properties = array();

		foreach($definition->getProperties() as $property_name => $property) {
			$field = $property->getFormField();

			if(!$field) {
				continue;
			}

			if(
				$skip_hidden_fields &&
				$field instanceof Form_Field_Hidden
			) {
				continue;
			}


			$only_properties[] = $property_name;
		}

		if(!$form_name) {
			//$form_name = $definition->getClassName();
			$form_name = $this->getClassNameWithoutNamespace();
		}

		return $this->getForm($form_name, $only_properties);
	}

	/**
	 * @param Form $form
	 *
	 * @param array $data
	 * @param bool $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data=null, $force_catch=false   ) {
		if(
			!$form->catchValues($data, $force_catch) ||
			!$form->validateValues()
		) {
			return false;
		}

		$data = $form->getValues();

		$properties = $this->getDataModelDefinition()->getProperties();

		foreach( $data as $key=>$val ) {
			$field = $form->getField($key);

			$callback = $field->getCatchDataCallback();

			if($callback) {
				$callback( $field->getValueRaw() );
				continue;
			}

			if(
				!isset($properties[$key]) ||
				$properties[$key]->getIsID()
			) {
				continue;
			}

			$setter_method_name = $this->getSetterMethodName( $key );

			if(method_exists($this, $setter_method_name)) {
				$this->{$setter_method_name}($val);
			} else {
				$this->_setPropertyValue($key, $val);
			}


		}

		if( $this->getIsNew() ) {
			$this->generateID();
		}


		return true;
	}

	/**
	 * @return string
	 */
	public function toXML() {
		return $this->_XMLSerialize();
	}

	/**
	 * @return string
	 */
	public function toJSON() {
		$data = $this->jsonSerialize();
		return json_encode($data);
	}

	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize( $prefix='' ) {
		$definition = $this->getDataModelDefinition();
		$properties = $definition->getProperties();

		$model_name = $definition->getModelName();

		$result = $prefix.'<'.$model_name.'>'.JET_EOL;

		foreach($properties as $property_name=>$property) {
			if($property->getDoNotSerialize()) {
				continue;
			}
			$result .= $prefix.JET_TAB.'<!-- '.$property->getTechnicalDescription().' -->'.JET_EOL;

			$val = $this->{$property_name};

			if($property->getIsDataModel()) {
				$result .= $prefix.JET_TAB.$property_name.JET_EOL;
				if($val) {
					/**
					 * @var DataModel $val
					 */
					$result .= $val->_XMLSerialize( $prefix.JET_TAB );
				}
				$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;

			} else {
				if(is_array($val)) {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.JET_EOL;
					foreach($val as $k=>$v) {
						if(is_numeric($k)) {
							$k = 'item';
						}
						$result .= $prefix.JET_TAB.JET_TAB.'<'.$k.'>'.htmlspecialchars($v).'</'.$k.'>'.JET_EOL;

					}
					$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;
				} else {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.htmlspecialchars($val).'</'.$property_name.'>'.JET_EOL;
				}

			}
		}
		$result .= $prefix.'</'.$model_name.'>'.JET_EOL;

		return $result;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize() {
		$properties = $this->getDataModelDefinition()->getProperties();

		$result = array();
		foreach($properties as $property_name=>$property) {
			if($property->getDoNotSerialize()) {
				continue;
			}

			if($property->getIsDataModel()) {
				if($this->{$property_name}) {
					/**
					 * @var DataModel $prop
					 */
					$prop = $this->{$property_name};
					$result[$property_name] = $prop->jsonSerialize();
				} else {
					$result[$property_name] = null;
				}
			} else {
				$result[$property_name] = $property->getValueForJsonSerialize( $this->{$property_name} );
			}
		}

		return $result;
	}

	/**
	 *
	 * @return array
	 */
	public function __sleep() {
		return array_keys($this->getDataModelDefinition()->getProperties());
	}

	/**
	 *
	 */
	public function __wakeup() {
		$this->___data_model_saved = true;
		$this->___data_model_ready_to_save = false;
	}


	/**
	 * @param string $class
	 *
	 * @return string[]
	 */
	public static function helper_getCreateCommand( $class ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		return $_this->getBackendInstance()->helper_getCreateCommand( $_this );
	}

	/**
	 *
	 * @param string $class
	 * @param bool $including_history_backend (optional, default: true)
	 * @param bool $including_cache_backend (optional, default: true)
	 * @return bool
	 */
	public static function helper_create( $class, $including_history_backend=true, $including_cache_backend=true ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		if( $including_history_backend ) {
			$h_backend = $_this->getHistoryBackendInstance();

			if($h_backend) {
				$h_backend->helper_create();
			}
		}

		if($including_cache_backend) {
			$c_backend = $_this->getCacheBackendInstance();

			if($c_backend) {
				$c_backend->helper_create();
			}

		}

		return $_this->getBackendInstance()->helper_create( $_this );
	}


	/**
	 * Update (actualize) DB table or tables
	 *
	 * @param string $class
	 *
	 * @return string
	 */
	public static function helper_getUpdateCommand( $class ) {
		/**
		 * @var DataModel $_this
		 */
		$_this = Factory::getInstance( $class );

		return $_this->getBackendInstance()->helper_getUpdateCommand( $_this );
	}

	/**
	 * Update (actualize) DB table or tables
	 *
	 * @param bool $including_history_backend (optional, default: true)
	 * @param bool $including_cache_backend (optional, default: true)
	 *
	 * @param string $class
	 */
	public static function helper_update( $class, $including_history_backend=true, $including_cache_backend=true  ) {
		/**
		 * @var DataModel $_this
		 */
		$_this = Factory::getInstance( $class );

		if( $including_history_backend ) {
			$h_backend = $_this->getHistoryBackendInstance();

			if($h_backend) {
				$h_backend->helper_create();
			}
		}

		if($including_cache_backend) {
			$c_backend = $_this->getCacheBackendInstance();

			if($c_backend) {
				$c_backend->helper_create();
			}

		}

		$_this->getBackendInstance()->helper_update( $_this );

		$cache = $_this->getCacheBackendInstance();
		if($cache) {
			$cache->truncate( $_this->getDataModelDefinition()->getModelName() );
		}
	}

	/**
	 * Drop (only rename by default) DB table or tables
	 *
	 * @param string $class
	 */
	public static function helper_drop( $class ) {
		/**
		 * @var DataModel $_this
		 */
		$_this = Factory::getInstance( $class );
		$_this->getBackendInstance()->helper_drop( $_this );

		$cache = $_this->getCacheBackendInstance();
		if($cache) {
			$cache->truncate( $_this->getDataModelDefinition()->getModelName() );
		}

	}


	/**
	 * @param &$reflection_data
	 * @param string $key
	 * @param string $definition
	 * @param mixed $raw_value
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( &$reflection_data, $key, $definition, $raw_value, $value ) {


		switch($key) {
			case 'relation':
				if(
					!is_array($value) ||
					empty($value[0]) ||
					empty($value[1]) ||
					!is_array($value[1]) ||
					!is_string($value[0])
				) {
					throw new Object_Reflection_Exception(
						'Relation definition parse errro. Class: \''.get_called_class().'\', definition: \''.$definition.'\', Example: JetDataModel:relation = [ \'Some\RelatedClass\', [ \'this.class_property_name\'=>\'related_class_property_name\', \'this.another_class_property_name\' => \'another_related_class_property_name\', \'this_value.getValueMethodName\' => \'another_related_class_property\' ], Jet\DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]',
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
						'Unknown relation type. Class: \''.get_called_class().'\', definition: \''.$definition.'\', Use Jet\DataModel_Query::JOIN_TYPE_LEFT_JOIN or Jet\DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN',
						Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
					);

				}

				if( !isset($reflection_data['data_model_outer_relations_definition']) ) {
					$reflection_data['data_model_outer_relations_definition'] = array();
				}

				if(isset( $reflection_data['data_model_outer_relations_definition'][ $value[0] ] )) {
					throw new Object_Reflection_Exception(
						'Duplicit relation! Class: \''.get_called_class().'\', definition: \''.$definition.'\''
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
			default:
				throw new Object_Reflection_Exception(
					'Unknown definition! Class: \''.get_called_class().'\', definition: \''.$definition.'\' ',
					Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $raw_value
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment( &$reflection_data,$property_name, $key, $definition, $raw_value, $value ) {

		if(!isset($reflection_data['data_model_properties_definition'])) {
			$reflection_data['data_model_properties_definition'] = array();
		}
		if(!isset($reflection_data['data_model_properties_definition'][$property_name])) {
			$reflection_data['data_model_properties_definition'][$property_name] = array();
		}

		$value = null;

		$eval_res = @eval('$value='.$raw_value.'; return true;');

		$reflection_data['data_model_properties_definition'][$property_name][$key] = $value;
	}

}
