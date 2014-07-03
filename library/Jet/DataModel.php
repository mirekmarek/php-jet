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
 * Available annotation:
 *
 * @JetDataModel:name = 'some_model_name'
 *      - Internal model name. It does not name a database table! The name is used mainly in queries.
 *
 * @JetDataModel:database_table_name = 'some_table_name'
 *
 * @JetDataModel:ID_class_name = 'Some\ID_Class_Name'
 *      - Optional. You can create your ID class or use one of those: Jet\DataModel_ID_UniqueString (is default), Jet\DataModel_ID_Name, Jet\DataModel_ID_AutoIncrement
 *
 * @JetDataModel:parent_model_class_name = 'Some\Parent_Class_Name'
 *      - ONLY FOR RELATED MODELS!
 *
 * Overrides the default settings:
 *      @JetDataModel:forced_backend_type = 'SomeBackendType'
 *      @JetDataModel:forced_backend_config = ['option'=>'value','option'=>'value']
 *      @JetDataModel:forced_history_enabled = bool
 *      @JetDataModel:forced_history_backend_type = 'SomeBackendType'
 *      @JetDataModel:forced_history_backend_config = ['option'=>'value','option'=>'value']
 *      @JetDataModel:forced_cache_enabled = bool
 *      @JetDataModel:forced_cache_backend_type = 'SomeBackendType'
 *      @JetDataModel:forced_cache_backend_config = ['option'=>'value','option'=>'value']
 *
 * Property definition:
 *      /**
 *       * @JetDataModel:type = Jet\DataModel::TYPE_*
 *       * @JetDataModel:is_ID = bool
 *       *      - optional
 *       * @JetDataModel:default_value = 'some default value'
 *       *      - optional
 *       * @JetDataModel:is_key = bool
 *       *      - optional, default: false or true if is_ID
 *       * @JetDataModel:key_type = Jet\DataModel::KEY_TYPE_*
 *       *      - optional, default: Jet\DataModel::KEY_TYPE_INDEX
 *       * @JetDataModel:description = 'Some description ...'
 *       *      - optional
 *       * @JetDataModel:do_not_serialize = bool
 *       *      - Do not serialize property into the XML/JSON result
 *       *      - optional, default: false
 *       * @JetDataModel:backend_options = ['BackendType'=>['option'=>'value','option'=>'value']]
 *       *      - optional
 *       *
 *       * Validation:
 *       *   @JetDataModel:error_messages = ['error_code'=>'Massage ...','error_code'=>'Massage ...']
 *       *   @JetDataModel:validation_method = 'someCustomValidationMethodName'
 *       *      - optional
 *       *   @JetDataModel:list_of_valid_options = ['option1'=>'Valid option 1','option2'=>'Valid option 2']
 *       *      - optional
 *       * Validation (type Jet\DataModel::TYPE_STRING):
 *       *   @JetDataModel:is_required = bool
 *       *   @JetDataModel:max_len = 255
 *       *   @JetDataModel:validation_regexp = '/some_regexp/'
 *       *      - optional
 *       *
 *       * Validation (type Jet\DataModel::TYPE_INT,Jet\DataModel::TYPE_FLOAT):
 *       *   @JetDataModel:min_value = 1
 *       *      - optional
 *       *   @JetDataModel:max_value = 999
 *       *      - optional
 *       *
 *       * Form field options:
 *       *   @JetDataModel:form_field_creator_method_name = 'someMethodName'
 *       *      - optional
 *       *          Creator example:
 *       *          public function myFieldCreator( DataModel_Definition_Property_Abstract $property_definition ) {
 *       *              $form_field = $property_definition->getFormField();
 *       *              $form_field->setLabel( 'Some special label' );
 *       *              // ... do something with form field
 *       *              return $form_field
 *       *          }
 *       *
 *       *   @JetDataModel:form_field_type = Jet\Form::TYPE_*
 *       *      - optional, default: autodetect
 *       *   @JetDataModel:form_field_label = 'Field label:'
 *       *   @JetDataModel:form_field_options = ['option'=>'value','option'=>'value']
 *       *      - optional
 *       *   @JetDataModel:form_field_error_messages = ['error_code'=>'message','error_code'=>'message']
 *       *   @JetDataModel:form_field_get_default_value_callback = callable
 *       *      - optional
 *       *   @JetDataModel:form_field_get_select_options_callback = callable
 *       *      - optional
 *       *
 *       * Specific (type Jet\DataModel::TYPE_DATA_MODEL):
 *       *   @JetDataModel:data_model_class = 'Some\Related_Model_Class_Name'
 *       *
 *       * Specific (type Jet\DataModel::TYPE_ARRAY):
 *       *   @JetDataModel:item_type = Jet\DataModel::TYPE_*
 *       *
 *       *
 *       * @var string          //some PHP type ...
 *       * /
 *      protected $some_property;
 *
 *
 * Relation on foreign model definition:
 *      @JetDataModel:relation = [ 'Some\RelatedClass', [ 'property_name'=>'related_property_name', 'another_property_name' => 'another_related_property_name' ], Jet\DataModel_Query::JOIN_TYPE_* ]
 *
 *          Warning!
 *          This kind of relation has no affect on saving or deleting object (like DataModel_Related_* models has).
 *
 * Composite keys definition:
 *      @JetDataModel:key = ['key_name', ['property_name', 'next_property_name'], Jet\DataModel::KEY_TYPE_*]
 *
 */



/**
 * Class DataModel
 *
 * @JetDataModel:ID_class_name = 'Jet\DataModel_ID_Default'
 */
abstract class DataModel extends Object implements Object_Serializable_REST, Object_Reflection_ParserInterface {

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

	/**
	 *
	 * @var DataModel_ID_Abstract
	 */
	private $__ID;

	/**
	 *
	 * @var bool
	 */
	private $___data_model_saved = false;

	/**
	 *
	 * @var bool
	 */
	private $___data_model_ready_to_save = false;

	/**
	 *
	 * @var DataModel_History_Backend_Abstract
	 */
	private $___data_model_history_backend_instance = null;

	/**
	 *
	 * @var DataModel_Validation_Error[]
	 */
	protected  $___data_model_data_validation_errors = array();


	/**
	 *
	 */
	public function __construct() {
		$this->initNewObject();
	}

	/**
	 * Initializes new DataModel
	 *
	 */
	protected function initNewObject() {

		$this->___data_model_ready_to_save = false;
		$this->___data_model_saved = false;


		foreach( $this->getDataModelDefinition()->getProperties() as $property_name => $property_definition ) {
			if($property_definition->getIsDataModel()) {
				$default_value = $property_definition->getDefaultValue( $this );

				$this->{$property_name} = $default_value;

			} else {
				if(!$this->{$property_name}) {
					$default_value = $property_definition->getDefaultValue( $this );

					$this->{$property_name} = $default_value;

					$property_definition->checkValueType( $this->{$property_name} );
				}
			}


		}

		//$this->generateID();
	}


	/**
	 * Returns ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function getID() {
		if(!$this->__ID) {
			$this->__ID = $this->getEmptyIDInstance();
		}

		foreach($this->__ID as $property_name => $value) {
			$this->__ID[$property_name] = $this->{$property_name};
		}

		return $this->__ID;
	}


	/**
	 * @return DataModel_ID_Abstract
	 */
	public static function getEmptyIDInstance() {
		return static::getDataModelDefinition()->getEmptyIDInstance();
	}

	/**
	 * @param string $ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public static function createID( $ID ) {
		return static::getEmptyIDInstance()->createID( $ID );
	}


	/**
	 * @return DataModel_ID_Abstract
	 */
	public function resetID() {
		$this->getID();

		$this->__ID->reset();

		foreach( $this->__ID as $property_name=>$value ) {
			$this->{$property_name} = $value;
		}

		return $this->__ID;

	}



	/**
	 * Generate unique ID
	 *
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 * @throws DataModel_Exception
	 */
	public function generateID(  $called_after_save = false, $backend_save_result = null  ) {

		$ID = $this->getID();

		$ID->generate( $this, $called_after_save, $backend_save_result );

		foreach( $ID as $property_name=>$value ) {
			$this->{$property_name} = $value;
		}

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
	 *
	 */
	public function setIsNew() {
		$this->___data_model_saved = false;
	}

	/**
	 * @return bool
	 */
	public function getIsSaved() {
		return $this->___data_model_saved;
	}

	/**
	 *
	 */
	public function setIsSaved() {
		$this->___data_model_saved = true;
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
	 * @throws DataModel_Exception
	 */
	protected function _setPropertyValue( $property_name, &$value ) {
		$properties = $this->getDataModelDefinition()->getProperties();
		if( !isset($properties[$property_name]) ) {
			throw new DataModel_Exception(
				'Unknown property \''.$property_name.'\'',
				DataModel_Exception::CODE_UNKNOWN_PROPERTY
			);
		}

		$property_definition = $properties[$property_name];
		if( $property_definition->getIsDataModel() ) {
			throw new DataModel_Exception(
				'It is not possible to use _setPropertyValue for property \''.$property_name.'\' which is DataModel. (For this property you must create setter.) '
			);

		}


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
				$property_definition->getIsDataModel()
			) {
				if( $this->{$property_name} ) {
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

					$this->appendValidationErrors( $prop->getValidationErrors() );

				}

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
	 * @param DataModel_Validation_Error[] $errors
	 */
	public function appendValidationErrors( $errors ) {
		$this->___data_model_data_validation_errors = array_merge(
			$this->___data_model_data_validation_errors,
			$errors
		);

	}

	/**
	 *
	 * @return DataModel_Validation_Error[]
	 */
	public function getValidationErrors() {
		return $this->___data_model_data_validation_errors;
	}



	/**
	 * Returns model definition
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public static function getDataModelDefinition( $class_name='' )  {
		if(!$class_name) {
			$class_name = get_called_class();
		}

		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $class_name );
	}


	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Main( $data_model_class_name );
	}

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend_Abstract
	 */
	public static function getBackendInstance() {
		return static::getDataModelDefinition()->getBackendInstance();
	}

	/**
	 *
	 * @return bool
	 */
	public static function getCacheEnabled() {
		return static::getDataModelDefinition()->getCacheEnabled();
	}

	/**
	 * Returns cache backend instance
	 *
	 * @return DataModel_Cache_Backend_Abstract
	 */
	public static function getCacheBackendInstance() {
		return static::getDataModelDefinition()->getCacheBackendInstance();
	}

	/**
	 *
	 * @return bool
	 */
	public static function getHistoryEnabled() {
		return static::getDataModelDefinition()->getHistoryEnabled();
	}

	/**
	 * Returns history backend instance
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public function getHistoryBackendInstance() {
		$definition = static::getDataModelDefinition();

		if(!$definition->getHistoryEnabled()) {
			return false;
		}

		if(!$this->___data_model_history_backend_instance) {
			$this->___data_model_history_backend_instance = DataModel_Factory::getHistoryBackendInstance(
				$definition->getHistoryBackendType(),
				$definition->getHistoryBackendConfig()
			);

		}

		return $this->___data_model_history_backend_instance;
	}


	/**
	 * Loads DataModel.
	 *
	 * @param DataModel_ID_Abstract $ID
	 *
	 * @return \Jet\DataModel|mixed|null
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel
	 */
	public static function load( DataModel_ID_Abstract $ID ) {

		$definition = static::getDataModelDefinition();

		$cache = static::getCacheBackendInstance();


		$loaded_instance = null;
		if($cache) {
			$loaded_instance = $cache->get( $definition, $ID);

			if($loaded_instance) {
				foreach( $definition->getProperties() as $property_name=>$property_definition ) {
					if(!$property_definition->getIsDataModel()) {
						continue;
					}

					/**
					 * @var DataModel_Related_Abstract $related_object
					 */
					$related_object = $loaded_instance->{$property_name};

					if($related_object) {
						$related_object->wakeUp( $loaded_instance );
					}
				}

				return $loaded_instance;
			}
		}


		$query = $ID->getQuery();

		/**
		 * @var DataModel $i
		 */
		$i = new static();
		foreach( $ID as $key=>$val ) {
			$i->{$key} = $val;
		}
		$query->setMainDataModel($i);

		$query->setSelect( $definition->getProperties() );


		$dat = static::getBackendInstance()->fetchRow( $query );

		if(!$dat) {
			return null;
		}

		$loaded_instance = static::_load_dataToInstance( $dat );

		if($cache) {
			$cache->save($definition, $ID, $loaded_instance);
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
	protected static function _load_dataToInstance( $dat, $main_model_instance=null ) {

		/**
		 * @var DataModel $loaded_instance
		 */
		$loaded_instance = new static();

		$definition = static::getDataModelDefinition();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if($property_definition->getIsDataModel()) {
				continue;
			}
			$loaded_instance->$property_name = $dat[$property_name];
			$property_definition->checkValueType( $loaded_instance->$property_name );
		}


		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
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


		$loaded_instance->__wakeup();
		$loaded_instance->setIsSaved();

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

		$backend = $this->getBackendInstance();

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionStart();
		}


		if( $this->getIsNew() ) {
			$after_method_name = 'afterAdd';
			$operation = 'save';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_SAVE;
		} else {
			$after_method_name = 'afterUpdate';
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

		$this->updateCache( $operation );

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionCommit();
		}

		$this->___data_model_saved = true;

		$this->___DataModelHistoryOperationDone();


		$this->{$after_method_name}();
	}

	/**
	 *
	 */
	protected function updateCache( $operation ) {
		$cache = $this->getCacheBackendInstance();
		if($cache) {
			$cache->{$operation}($this->getDataModelDefinition(), $this->getID(), $this);
		}

	}

	/**
	 *
	 */
	protected function deleteCache() {
		$cache = $this->getCacheBackendInstance();
		if($cache) {
			$cache->delete($this->getDataModelDefinition(), $this->getID() );
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

		$this->generateID();
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

		$backend->update($record, $this->getID()->getQuery() );

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
		if( !$this->getID() || !$this->getIsSaved() ) {
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

		$backend->delete( $this->getID()->getQuery() );

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionCommit();
		}

		$this->___DataModelHistoryOperationDone();


		$this->deleteCache();

		$this->afterDelete();
 	}

	/**
	 *
	 */
	public function afterAdd() {

	}

	public function afterUpdate() {

	}

	public function afterDelete() {

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
			DataModel_Query::createQuery( $this->getDataModelDefinition(),
				$where
			)
		);

		/**
		 * @var DataModel_ID_Abstract[] $affected_IDs
		 */
		if(count($affected_IDs)) {
			$this->deleteCacheIDs( $affected_IDs );
		}
	}

	/**
	 * @param DataModel_ID_Abstract[] $IDs
	 */
	protected function deleteCacheIDs( $IDs ) {
		$cache = $this->getCacheBackendInstance();
		if(!$cache) {
			return;
		}

		foreach($IDs as $ID) {
			$cache->delete( $this->getDataModelDefinition(), $ID );
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
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	protected function createQuery( array $where=array() ) {
		$query = new DataModel_Query($this->getDataModelDefinition() );
		$query->setMainDataModel( $this );
		$query->setWhere( $where );
		return $query;
	}


	/**
	 *
	 * @param array| $where
	 * @return DataModel
	 */
	protected function fetchOneObject( array $where ) {
		$query = $this->createQuery( $where );
		$query->setLimit(1);

		$fetch = new DataModel_Fetch_Object_Assoc( $query );

		foreach($fetch as $object) {
			return $object;
		}

		return false;
	}

	/**
	 *
	 * @param array $where
	 * @return DataModel_Fetch_Object_Assoc
	 */
	protected function fetchObjects( array  $where=array() ) {
		return new DataModel_Fetch_Object_Assoc( $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $where
	 * @return DataModel_Fetch_Object_IDs
	 */
	protected function fetchObjectIDs( array $where=array() ) {
		return new DataModel_Fetch_Object_IDs(  $this->createQuery($where)  );
	}


	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return DataModel_Fetch_Data_All
	 */
	protected function fetchDataAll( array $load_items, array  $where=array() ) {
		return new DataModel_Fetch_Data_All( $load_items, $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return DataModel_Fetch_Data_Assoc
	 */
	protected function fetchDataAssoc( array $load_items, array  $where=array() ) {
		return new DataModel_Fetch_Data_Assoc( $load_items, $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return DataModel_Fetch_Data_Pairs
	 */
	protected function fetchDataPairs( array $load_items, array  $where=array() ) {
		return new DataModel_Fetch_Data_Pairs( $load_items, $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return mixed|null
	 */
	protected function fetchDataRow( array $load_items, array  $where=array() ) {
		$query = $this->createQuery( $where );
		$query->setSelect($load_items);

		return static::getBackendInstance()->fetchRow( $query );

	}

	/**
	 *
	 * @param array $load_item
	 * @param array $where
	 *
	 * @return mixed|null
	 */
	protected function fetchDataOne( $load_item, array  $where=array() ) {

		$query = $this->createQuery( $where );
		$query->setSelect( [$load_item] );

		return static::getBackendInstance()->fetchOne( $query );
	}

	/**
	 *
	 * @param $load_item
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_Col
	 */
	protected function fetchDataCol( $load_item, array  $where=array() ) {
		$query = $this->createQuery( $where );

		return new DataModel_Fetch_Data_Col( $load_item, $query );
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

			if(
				$property->getIsDataModel() &&
				(
					$this->{$property_name} instanceof DataModel_Related_1toN ||
					$this->{$property_name} instanceof DataModel_Related_1to1
				)
			) {

				if( $this->{$property_name} instanceof DataModel_Related_1toN ) {

					foreach( $this->{$property_name} as $key=>$related_instance) {

						/**
						 * @var DataModel_Related_1toN $related_instance
						 */
						$content_form = $related_instance->getCommonForm();

						foreach($content_form->getFields() as $field) {
							if(
								$field instanceof Form_Field_Hidden
							) {
								continue;
							}

							$field->setName('/'.$property_name.'/'.$key.'/'.$field->getName() );

							$fields[] = $field;
							//$new_field->setForm($form);
							//$form->addField( $new_field );
						}

					}

				}

				if( $this->{$property_name} instanceof DataModel_Related_1to1 ) {
					/**
					 * @var DataModel_Related_1to1 $related_instance
					 */
					$related_instance = $this->{$property_name};

					$content_form = $related_instance->getCommonForm();

					foreach($content_form->getFields() as $field) {
						if(
							$field instanceof Form_Field_Hidden
						) {
							continue;
						}

						$field->setName('/'.$property_name.'/'.$field->getName() );

						$fields[] = $field;
						//$new_field->setForm($form);
						//$form->addField( $new_field );
					}

				}

				continue;
			}

			$field_creator_method_name = $property->getFormFieldCreatorMethodName();

			if(!$field_creator_method_name) {
				$field = $property->getFormField();
			} else {
				$field = $this->{$field_creator_method_name}( $property );
			}

			if(!$field) {
				$class = $definition->getClassName();

				throw new DataModel_Exception(
					'The property '.$class.'::'.$property.' is required for form definition. But property definition '.get_class($property).' prohibits the use of property as form field. ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}

			$field->setDefaultValue( $this->{$property->getName()} );

			$fields[] = $field;
		}

		return new Form( $form_name, $fields );

	}

	/**
	 * @param string $form_name
	 * @param bool $include_related_objects (optional, default=false)
	 * @param bool $skip_hidden_fields (optional, default=false)
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name='', $include_related_objects=true, $skip_hidden_fields=false ) {
		$definition = $this->getDataModelDefinition();


		$only_properties = array();

		foreach($definition->getProperties() as $property_name => $property) {

			if(
				$property->getIsDataModel() &&
				$include_related_objects &&
				(
					$this->$property_name instanceof DataModel_Related_1toN ||
					$this->$property_name instanceof DataModel_Related_1to1
				)
			) {
				if($property->getFormFieldType()!==false) {
					$only_properties[] = $property_name;
				}
				continue;
			}

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
			$form_name = $definition->getModelName();
		}

		return $this->getForm($form_name, $only_properties, $skip_hidden_fields);
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

		$data= $form->getRawData()->getRawData();

		$properties = $this->getDataModelDefinition()->getProperties();
		$form_fields = $form->getFields();

		foreach( $data as $key=>$val ) {
			if(
				!isset($form_fields[$key]) &&
				!isset($properties[$key])
			) {
				continue;
			}


			if(isset($form_fields[$key])) {
				$form_field = $form_fields[$key];
				$val = $form_field->getValue();

				$callback = $form_field->getCatchDataCallback();

				if($callback) {
					$callback( $form_field->getValueRaw() );
					continue;
				}
			}


			if(isset($properties[$key])) {

				$property = $properties[$key];
				$property_name = $property->getName();

				if( $property->getIsDataModel() ) {

					if( $this->$property_name instanceof DataModel_Related_1toN ) {

						foreach( $this->$property_name as $r_key=>$r_instance ) {

							$values = isset( $val[$r_key] ) ? $val[$r_key] : array();

							/**
							 * @var DataModel $r_instance
							 */
							//$r_form = $r_instance->getForm( '', array_keys($values) );
							$r_form = $r_instance->getCommonForm();

							$r_instance->catchForm( $r_form, $values, true );

						}

						continue;
					}

					if( $this->$property_name instanceof DataModel_Related_1to1 ) {
						/**
						 * @var DataModel $r_instance
						 */
						$r_instance = $this->$property_name;
						$r_form = $r_instance->getForm( '', array_keys($val) );

						$r_instance->catchForm( $r_form, $val, true );

						continue;
					}


				}


				if(
					$property->getIsID()
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
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
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
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
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
	 *
	 */
	public function __clone() {
		$this->resetID();
		$this->setIsNew();

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
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

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
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

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
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( &$reflection_data, $key, $definition, $value ) {
		DataModel_Definition_Model_Abstract::parseClassDocComment( get_called_class(), $reflection_data, $key, $definition, $value );
	}

	/**
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment( &$reflection_data,$property_name, $key, $definition, $value ) {
		DataModel_Definition_Model_Abstract::parsePropertyDocComment( get_called_class(), $reflection_data,$property_name, $key, $definition, $value );
	}

}
