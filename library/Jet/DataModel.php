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
 *      - You can create your ID class or use one of those: DataModel_ID_UniqueString, DataModel_ID_Name, DataModel_ID_AutoIncrement, DataModel_ID_Passive
 *
 * @JetDataModel:ID_options = ['option'=>'value', 'next_option'=>123]
 *      - A practical example: @JetDataModel:ID_options = ['ID_property_name'=>'some_id_property_name']
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
 *       * @JetDataModel:type = DataModel::TYPE_*
 *       * @JetDataModel:is_ID = bool
 *       *      - optional
 *       * @JetDataModel:default_value = 'some default value'
 *       *      - optional
 *       * @JetDataModel:is_key = bool
 *       *      - optional, default: false or true if is_ID
 *       * @JetDataModel:key_type = DataModel::KEY_TYPE_*
 *       *      - optional, default: DataModel::KEY_TYPE_INDEX
 *       * @JetDataModel:description = 'Some description ...'
 *       *      - optional
 *       * @JetDataModel:do_not_export = bool
 *       *      - Do not export property into the XML/JSON result
 *       *      - optional, default: false
 *       * @JetDataModel:backend_options = ['BackendType'=>['option'=>'value','option'=>'value']]
 *       *      - optional
 *       *      - optional
 *       *
 *       * Validation:
 *       *   @JetDataModel:error_messages = ['error_code'=>'Massage ...','error_code'=>'Massage ...']
 *       *   @JetDataModel:validation_method = 'someCustomValidationMethodName'
 *       *      - optional
 *       *   @JetDataModel:list_of_valid_options = ['option1'=>'Valid option 1','option2'=>'Valid option 2']
 *       *      - optional
 *       * Validation (type DataModel::TYPE_STRING):
 *       *   @JetDataModel:is_required = bool
 *       *   @JetDataModel:max_len = 255
 *       *   @JetDataModel:validation_regexp = '/some_regexp/'
 *       *      - optional
 *       *
 *       * Validation (type DataModel::TYPE_INT,DataModel::TYPE_FLOAT):
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
 *       *   @JetDataModel:form_field_type = Form::TYPE_*
 *       *      - optional, default: autodetect
 *       *   @JetDataModel:form_field_label = 'Field label:'
 *       *   @JetDataModel:form_field_options = ['option'=>'value','option'=>'value']
 *       *      - optional
 *       *   @JetDataModel:form_field_error_messages = ['error_code'=>'message','error_code'=>'message']
 *       *   @JetDataModel:form_field_get_default_value_callback = callable
 *       *      - optional
 *       *   @JetDataModel:form_field_get_select_options_callback = callable
 *       *      - optional
 *       *   @JetDataModel:form_catch_value_method_name = 'someMethodName'
 *       *      - optional
 *       *
 *       * Specific (type DataModel::TYPE_DATA_MODEL):
 *       *   @JetDataModel:data_model_class = 'Some\Related_Model_Class_Name'
 *       *
 *       * Specific (type DataModel::TYPE_ARRAY):
 *       *   @JetDataModel:item_type = DataModel::TYPE_*
 *       *
 *       *
 *       * @var string          //some PHP type ...
 *       * /
 *      protected $some_property;
 *
 *
 * Relation on foreign model definition:
 *      @JetDataModel:relation = [ 'Some\RelatedClass', [ 'property_name'=>'related_property_name', 'another_property_name' => 'another_related_property_name' ], DataModel_Query::JOIN_TYPE_* ]
 *
 *          Warning!
 *          This kind of relation has no affect on saving or deleting object (like DataModel_Related_* models has).
 *
 * Composite keys definition:
 *      @JetDataModel:key = ['key_name', ['property_name', 'next_property_name'], DataModel::KEY_TYPE_*]
 *
 */



/**
 * Class DataModel
 *
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
	const TYPE_DYNAMIC_VALUE = 'DynamicValue';

	const KEY_TYPE_PRIMARY = 'PRIMARY';
	const KEY_TYPE_INDEX = 'INDEX';
	const KEY_TYPE_UNIQUE = 'UNIQUE';


	/**
	 *
	 */
	public function __construct() {
		$this->initNewObject();
	}

	/**
	 *
	 */
	public function __destruct() {
		DataModel_ObjectState::destruct($this);
	}

	/**
	 * Initializes new DataModel
	 *
	 */
	protected function initNewObject() {
		$this->setIsNew();

		$ready_to_save = &DataModel_ObjectState::getVar($this, 'ready_to_save',false);
		$ready_to_save = false;

		$data_model_definition = $this->getDataModelDefinition();

		foreach( $data_model_definition->getProperties() as $property_name => $property_definition ) {

			$property_definition->initPropertyDefaultValue( $this->{$property_name}, $this );

		}

	}


	/**
	 * Returns ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function getID() {

		$ID = &DataModel_ObjectState::getVar($this, 'ID');

		if(!$ID) {
			$ID = $this->getEmptyIDInstance();
		}

		foreach($ID as $property_name => $value) {
			$ID[$property_name] = $this->{$property_name};
		}

		return $ID;
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
	public static function createID(
		/** @noinspection PhpUnusedParameterInspection */
		$ID
	) {
		$arguments = func_get_args();

		return call_user_func_array( [static::getEmptyIDInstance(),'createID'], $arguments );
	}


	/**
	 * @return DataModel_ID_Abstract
	 */
	public function resetID() {
		$ID = $this->getID();

		$ID->reset();

		foreach( $ID as $property_name=>$value ) {
			$this->{$property_name} = $value;
		}

		return $ID;

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
		return !DataModel_ObjectState::getVar($this, 'data_model_saved', false);
	}

	/**
	 *
	 */
	public function setIsNew() {
		$data_model_saved = &DataModel_ObjectState::getVar($this, 'data_model_saved', false);
		$data_model_saved = false;
	}

	/**
	 * @return bool
	 */
	public function getIsSaved() {
		return DataModel_ObjectState::getVar($this, 'data_model_saved', false);
	}

	/**
	 *
	 */
	public function setIsSaved() {
		$data_model_saved = &DataModel_ObjectState::getVar($this, 'data_model_saved', false);
		$data_model_saved = true;
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

		$errors = [];

		$property_definition->validatePropertyValue($this, $value, $errors);

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
	public function _setPropertyValue( $property_name, &$value ) {
		$properties = $this->getDataModelDefinition()->getProperties();
		if( !isset($properties[$property_name]) ) {
			throw new DataModel_Exception(
				'Unknown property \''.$property_name.'\'',
				DataModel_Exception::CODE_UNKNOWN_PROPERTY
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

		$validation_errors = &DataModel_ObjectState::getVar($this, 'validation_errors',[]);
		$validation_errors = [];

		$ready_to_save = &DataModel_ObjectState::getVar($this, 'ready_to_save',false);
		$ready_to_save = false;

		foreach( $this->getDataModelDefinition()->getProperties()  as $property_name=>$property_definition ) {

			$property_definition->validatePropertyValue($this, $this->{$property_name}, $validation_errors);
		}

		if(count($validation_errors)) {
			return false;
		}

		$ready_to_save = true;

		return true;
	}

	/**
	 *
	 * @return DataModel_Validation_Error[]
	 */
	public function getValidationErrors() {
		$validation_errors = &DataModel_ObjectState::getVar($this, 'validation_errors',[]);

		return $validation_errors;
	}



	/**
	 * Returns model definition
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model_Abstract
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

		$history_backend_instance = &DataModel_ObjectState::getVar($this, 'history_backend_instance' );

		if(!$history_backend_instance) {
			$history_backend_instance = DataModel_Factory::getHistoryBackendInstance(
				$definition->getHistoryBackendType(),
				$definition->getHistoryBackendConfig()
			);

		}

		return $history_backend_instance;
	}

	/**
	 * Loads DataModel.
	 *
	 * @param DataModel_ID_Abstract|array $ID
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel
	 */
	public static function load( $ID ) {

		/**
		 * @var DataModel $loaded_instance
		 */
		$loaded_instance = new static();
		foreach( $ID as $key=>$val ) {
			$loaded_instance->{$key} = $val;
		}
		$ID = $loaded_instance->getID();


		$definition = static::getDataModelDefinition();

		$cache = static::getCacheBackendInstance();


		if($cache) {
			$cached_instance = $cache->get( $definition, $ID);

			if($cached_instance) {
				/**
				 * @var DataModel $loaded_instance
				 */

				foreach( $definition->getProperties() as $property_name=>$property_definition ) {

					/**
					 * @var DataModel_Related_Interface $related_object
					 */
					$related_object = $cached_instance->{$property_name};

					if($related_object instanceof DataModel_Related_Interface) {
						$related_object->setupParentObjects( $cached_instance );

					}
				}

				$cached_instance->setIsSaved();
				$cached_instance->afterLoad();

				return $cached_instance;
			}
		}




		$query = $ID->getQuery();
		$query->setMainDataModel($loaded_instance);

		$query->setSelect( $definition->getProperties() );


		$data = static::getBackendInstance()->fetchRow( $query );

		if(!$data) {
			return null;
		}


		static::createInstanceFromData( $data, $loaded_instance );


		$related_properties = $definition->getAllRelatedPropertyDefinitions();

		$loaded_related_data = [];

		foreach( $related_properties as $related_model_name=>$related_property ) {

			/**
			 * @var DataModel_Related_Interface $related_object
			 */
			$related_object = $related_property->getDefaultValue();
			$related_object->setupParentObjects( $loaded_instance );


			$related_data = $related_object->loadRelatedData();

			if(!isset($loaded_related_data[$related_model_name])) {
				$loaded_related_data[$related_model_name] = [];

				foreach($related_data as $rd) {
					$loaded_related_data[$related_model_name][] = $rd;
				}
			}
		}

		$loaded_instance->initRelatedProperties( $loaded_related_data );


		if($cache) {
			$cache->save($definition, $ID, $loaded_instance);
		}

		$loaded_instance->afterLoad();

		return $loaded_instance;
	}


	/**
	 * @param array &$loaded_related_data
	 * @return mixed
	 */
	protected function initRelatedProperties( array &$loaded_related_data ) {
		$definition = static::getDataModelDefinition();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {

			/**
			 * @var DataModel_Definition_Property_DataModel $property_definition
			 * @var DataModel_Related_Interface $property
			 */
			$property = $this->{$property_name};
			if(!($property instanceof DataModel_Related_Interface)) {
				continue;
			}

			$property->setupParentObjects( $this );

			$this->{$property_name} = $property->createRelatedInstancesFromLoadedRelatedData( $loaded_related_data );
		}

	}


	/**
	 * @param array $data
	 * @param DataModel $instance
	 *
	 * @return DataModel
	 */
	public static function createInstanceFromData( $data, DataModel $instance=null ) {

		if(!$instance) {
			$instance = new static();
		}

		$definition = static::getDataModelDefinition();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			$property_definition->loadPropertyValue( $instance->{$property_name}, $data );
		}

		$instance->__wakeup();
		$instance->setIsSaved();

		return $instance;
	}

	/**
	 * @return bool
	 */
	public function getBackendTransactionStarted() {
		$backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );

		return $backend_transaction_started;
	}

	/**
	 * @return bool
	 */
	public function getBackendTransactionStartedByThisInstance() {
		$backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );

		return $backend_transaction_started;
	}

	/**
	 * @param DataModel_Backend_Abstract $backend
	 */
	public function startBackendTransaction( DataModel_Backend_Abstract $backend ) {
		if(!$this->getBackendTransactionStarted()) {
			$backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );
			$backend_transaction_started = true;

			$backend->transactionStart();;
		}
	}

	/**
	 * @param DataModel_Backend_Abstract $backend
	 */
	public function commitBackendTransaction( DataModel_Backend_Abstract $backend ) {
		if($this->getBackendTransactionStartedByThisInstance()) {
			$backend->transactionCommit();

			$backend_transaction_started = &DataModel_ObjectState::getVar($this, 'backend_transaction_started', false );
			$backend_transaction_started = false;
		}
	}

	/**
	 * @param DataModel_Backend_Abstract $backend
	 */
	public function rollbackBackendTransaction( DataModel_Backend_Abstract $backend ) {
		$backend->transactionRollback();
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

		$this->startBackendTransaction( $backend );


		if( $this->getIsNew() ) {
			$after_method_name = 'afterAdd';
			$operation = 'save';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_SAVE;
		} else {
			$after_method_name = 'afterUpdate';
			$operation = 'update';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_UPDATE;
		}

		if($this->getBackendTransactionStartedByThisInstance()) {
			$this->dataModelHistoryOperationStart( $h_operation );
		}


		try {
			$this->{'_'.$operation}( $backend );
		} catch (Exception $e) {
			$this->rollbackBackendTransaction($backend);

			throw $e;
		}

		if($this->getBackendTransactionStartedByThisInstance()) {
			$this->updateDataModelCache( $operation );
			$this->commitBackendTransaction( $backend );

			$this->dataModelHistoryOperationDone();
		}


		$this->setIsSaved();

		$this->{$after_method_name}();
	}


	/**
	 *
	 * @param DataModel_Backend_Abstract $backend
	 */
	protected function _save( DataModel_Backend_Abstract $backend ) {
		$definition = $this->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$this->generateID();
		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( !$property_definition->getCanBeInInsertRecord() ) {
				continue;
			}

			$record->addItem($property_definition, $this->{$property_name});
		}


		$backend_result = $backend->save( $record );

		$this->generateID( true, $backend_result );

		$this->_saveRelatedObjects();

	}

	/**
	 *
	 * @param DataModel_Backend_Abstract $backend
	 */
	protected function _update( DataModel_Backend_Abstract $backend ) {
		$definition = $this->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );


		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( !$property_definition->getCanBeInUpdateRecord() ) {
				continue;
			}

			$record->addItem($property_definition, $this->{$property_name});
		}

		if(!$record->getIsEmpty()) {
			$backend->update($record, $this->getID()->getQuery() );
		}

		$this->_saveRelatedObjects();

	}


	/**
	 *
	 */
	protected function _saveRelatedObjects() {
		$definition = $this->getDataModelDefinition();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {

			/**
			 * @var DataModel_Related_Interface $prop
			 */
			$prop = $this->{$property_name};
			if(!($prop instanceof DataModel_Related_Interface)) {
				continue;
			}

			$prop->setupParentObjects( $this );
			$prop->save();
		}
	}


	/**
	 *
	 * @param string $operation
	 */
	public function updateDataModelCache( $operation ) {
		$cache = $this->getCacheBackendInstance();
		if($cache) {
			$cache->{$operation}($this->getDataModelDefinition(), $this->getID(), $this);
		}
	}

	/**
	 *
	 */
	public function deleteDataModelCache() {

		$cache = $this->getCacheBackendInstance();
		if($cache) {
			$cache->delete($this->getDataModelDefinition(), $this->getID() );
		}

	}


	/**
	 * @throws DataModel_Exception
	 */
	protected function _checkBeforeSave() {
		if(! DataModel_ObjectState::getVar($this, 'ready_to_save',false) ) {

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
	 * @throws DataModel_Exception
	 */
	public function delete() {
		if( !$this->getID() || !$this->getIsSaved() ) {
			throw new DataModel_Exception('Nothing to delete... Object was not loaded. (Class: \''.get_class($this).'\', ID:\''.$this->getID().'\')', DataModel_Exception::CODE_NOTHING_TO_DELETE);
		}

		$this->dataModelHistoryOperationStart( DataModel_History_Backend_Abstract::OPERATION_DELETE );

		$backend = $this->getBackendInstance();
		$definition = $this->getDataModelDefinition();

		$this->startBackendTransaction( $backend );

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			$prop = $this->{$property_name};
			if( $prop instanceof DataModel_Related_Interface ) {
				$prop->delete();
			}
		}

		$backend->delete( $this->getID()->getQuery() );

		$this->commitBackendTransaction( $backend );

		$this->dataModelHistoryOperationDone();


		$this->deleteDataModelCache();

		$this->afterDelete();
	}

	/**
	 *
	 */
	public function afterLoad() {

	}

	/**
	 *
	 */
	public function afterAdd() {

	}

	/**
	 *
	 */
	public function afterUpdate() {

	}

	/**
	 *
	 */
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
			$this->deleteDataModelCacheIDs( $affected_IDs );
		}
	}

	/**
	 * @param DataModel_ID_Abstract[] $IDs
	 */
	protected function deleteDataModelCacheIDs( $IDs ) {
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
	protected function dataModelHistoryOperationStart( $operation ) {
		$backend = $this->getHistoryBackendInstance();

		if( !$backend ) {
			return;
		}

		$backend->operationStart( $this, $operation );
	}

	/**
	 *
	 */
	protected function dataModelHistoryOperationDone() {
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
	protected function createQuery( array $where= []) {
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
	protected function fetchObjects( array  $where= []) {
		return new DataModel_Fetch_Object_Assoc( $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $where
	 * @return DataModel_Fetch_Object_IDs
	 */
	protected function fetchObjectIDs( array $where= []) {
		return new DataModel_Fetch_Object_IDs(  $this->createQuery($where)  );
	}


	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return DataModel_Fetch_Data_All
	 */
	protected function fetchDataAll( array $load_items, array  $where= []) {
		return new DataModel_Fetch_Data_All( $load_items, $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return DataModel_Fetch_Data_Assoc
	 */
	protected function fetchDataAssoc( array $load_items, array  $where= []) {
		return new DataModel_Fetch_Data_Assoc( $load_items, $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return DataModel_Fetch_Data_Pairs
	 */
	protected function fetchDataPairs( array $load_items, array  $where= []) {
		return new DataModel_Fetch_Data_Pairs( $load_items, $this->createQuery($where) );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 * @return mixed|null
	 */
	protected function fetchDataRow( array $load_items, array  $where= []) {
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
	protected function fetchDataOne( $load_item, array  $where= []) {

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
	protected function fetchDataCol( $load_item, array  $where= []) {
		$query = $this->createQuery( $where );

		return new DataModel_Fetch_Data_Col( $load_item, $query );
	}


	/**
	 *
	 * @param string $form_name
	 * @param array $properties_list
	 * @throws DataModel_Exception
	 *
	 * @return Form
	 */
	protected function getForm( $form_name, array $properties_list ) {

		$definition = $this->getDataModelDefinition();
		$propertied_definition = $definition->getProperties();

		$form_fields = [];

		foreach( $properties_list as $key=>$val ) {
			if(is_array($val)) {
				$property_name = $key;
				$related_data = $val;
			} else {
				$property_name = $val;
				$related_data = [];
			}

			$property_definition = $propertied_definition[$property_name];
			$property = $this->{$property_name};

			$created_field = $property_definition->createFormField( $this, $property, $related_data );

			if(!$created_field) {
				continue;
			}

			if(is_array($created_field)) {
				foreach( $created_field as $f ) {
					$form_fields[] = $f;
				}
			} else {
				$form_fields[] = $created_field;
			}


		}


		return new Form( $form_name, $form_fields );

	}

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name='' ) {

		$properties_list = $this->getCommonFormPropertiesList();

		if(!$form_name) {
			$definition = $this->getDataModelDefinition();
			$form_name = $definition->getModelName();
		}

		return $this->getForm($form_name, $properties_list );
	}


	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList() {
		$definition = $this->getDataModelDefinition();
		$properties_list = [];

		foreach($definition->getProperties() as $property_name => $property_definition) {
			if(
				!$property_definition->getCanBeFormField() ||
				$property_definition->getFormFieldType()===false
			) {
				continue;
			}

			$property = $this->{$property_name};

			if($property instanceof DataModel_Related_Interface) {
				$properties_list[$property_name] = $property->getCommonFormPropertiesList();

			} else {
				$properties_list[] = $property_name;
			}

		}

		return $properties_list;

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


			if(isset($properties[$key])) {

				$property_definition = $properties[$key];
				$property_name = $property_definition->getName();

				$property_definition->catchFormField( $this, $this->{$property_name}, $val );

			}

		}

		return true;
	}

	/**
	 * @return string
	 */
	public function toXML() {
		return $this->XMLSerialize();
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
	public function XMLSerialize( $prefix='' ) {
		$definition = $this->getDataModelDefinition();
		$properties = $definition->getProperties();

		$model_name = $definition->getModelName();

		$result = $prefix.'<'.$model_name.'>'.JET_EOL;

		foreach($properties as $property_name=>$property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			if($property->doNotExport()) {
				continue;
			}
			$result .= $prefix.JET_TAB.'<!-- '.$property->getTechnicalDescription().' -->'.JET_EOL;

			$val = $property->getXmlExportValue( $this, $this->{$property_name} );


			if( ($val instanceof DataModel_Related_Interface)) {
				$result .= $prefix.JET_TAB.'<'.$property_name.'>'.JET_EOL;
				if($val) {
					/**
					 * @var DataModel $val
					 */
					$result .= $val->XMLSerialize( $prefix.JET_TAB );
				}
				$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;

			} else {
				if(is_array($val)) {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.JET_EOL;
					foreach($val as $k=>$v) {
						if(is_numeric($k)) {
							$k = 'item';
						}
						$result .= $prefix.JET_TAB.JET_TAB.'<'.$k.'>'.Data_Text::htmlSpecialChars($v).'</'.$k.'>'.JET_EOL;

					}
					$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;
				} else {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.Data_Text::htmlSpecialChars($val).'</'.$property_name.'>'.JET_EOL;
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

		$result = [];
		foreach($properties as $property_name=>$property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			if($property->doNotExport()) {
				continue;
			}

			$result[$property_name] = $property->getValueForJsonSerialize( $this, $this->{$property_name} );

		}

		return $result;
	}


	/**
	 *
	 * @return array
	 */
	public function __sleep() {
		return parent::__sleep();
		//return array_keys($this->getDataModelDefinition()->getProperties());
	}

	/**
	 *
	 */
	public function __wakeup() {
		$this->setIsSaved();
		$ready_to_save = &DataModel_ObjectState::getVar($this, 'ready_to_save',false);
		$ready_to_save = false;
	}

	/**
	 *
	 */
	public function __clone() {
		parent::__clone();

		$this->resetID();
		$this->setIsNew();
	}


	/**
	 * @param string $class
	 *
	 * @return string[]
	 */
	public static function helper_getCreateCommand( $class ) {
		//DO NOT CHANGE CLASS NAME BY FACTORY HERE!
		$class = Object_Reflection::parseClassName($class);
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
		//DO NOT CHANGE CLASS NAME BY FACTORY HERE!

		$class = Object_Reflection::parseClassName($class);
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
		//DO NOT CHANGE CLASS NAME BY FACTORY HERE!
		$class = Object_Reflection::parseClassName($class);
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
		//DO NOT CHANGE CLASS NAME BY FACTORY HERE!
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
		//DO NOT CHANGE CLASS NAME BY FACTORY HERE!
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
