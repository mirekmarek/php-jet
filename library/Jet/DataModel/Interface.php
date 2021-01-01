<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface DataModel_Interface extends BaseObject_Interface_Serializable_JSON
{
//-- Definition ---------------------------------

	/**
	 *
	 * @param string $class_name
	 *
	 * @return DataModel_Definition_Model
	 */
	public static function getDataModelDefinition( string $class_name = '' ) : DataModel_Definition_Model;


	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public static function dataModelDefinitionFactory( string $data_model_class_name ) : DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN;


//-- Id -----------------------------------------

	/**
	 * @return DataModel_IDController
	 */
	public static function getEmptyIDController() : DataModel_IDController;


	/**
	 *
	 * @return DataModel_IDController
	 */
	public function getIDController() : DataModel_IDController;

//-- InternalState ------------------------------

	/**
	 *
	 */
	public function initNewObject() : void;

	/**
	 *
	 * @return bool
	 */
	public function getIsNew() : bool;

	/**
	 *
	 */
	public function setIsNew() : void;

	/**
	 * @return bool
	 */
	public function getIsSaved() : bool;

	/**
	 *
	 */
	public function setIsSaved() : void;


//-- Backend ------------------------------------
	/**
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance() : DataModel_Backend;

	/**
	 * @return bool
	 */
	public function getBackendTransactionStarted() : bool;

	/**
	 * @return bool
	 */
	public function getBackendTransactionStartedByThisInstance() : bool;

	/**
	 *
	 */
	public function startBackendTransaction() : void;

	/**
	 *
	 */
	public function commitBackendTransaction() : void;

	/**
	 *
	 */
	public function rollbackBackendTransaction() : void;

//-- Load ---------------------------------------

	/**
	 * @return DataModel_PropertyFilter|null
	 */
	public function getLoadFilter() : DataModel_PropertyFilter|null;

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( array $where = [] ) : DataModel_Query;


	/**
	 * @param array $this_data
	 * @param array $related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel_Interface
	 */
	public static function initByData( array $this_data, array $related_data = [], DataModel_PropertyFilter $load_filter=null ) : DataModel_Interface;


	/**
	 * Loads DataModel.
	 *
	 * @param array|string|int|DataModel_IDController $id_or_where
	 * @param array|DataModel_PropertyFilter|null $load_filter
	 *
	 * @return static|null
	 */
	public static function load( array|string|int|DataModel_IDController $id_or_where,
	                             array|DataModel_PropertyFilter|null $load_filter = null ) : static|null;


	/**
	 * @param array $where_per_model
	 * @param array|string|null $order_by
	 * @param callable|null $item_key_generator
	 * @param array|DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel[]
	 */
	public static function fetch( array $where_per_model=[],
	                              array|string|null $order_by=null,
	                              callable|null $item_key_generator = null,
	                              array|DataModel_PropertyFilter|null $load_filter=null ) : array;


	/**
	 * @param array $select
	 * @param array $where
	 * @param string $fetch_method
	 *
	 * @return mixed
	 *
	 * @throws DataModel_Query_Exception
	 */
	public static function fetchData( array $select, array $where, string $fetch_method='fetchAll' ) : mixed;


	/**
	 *
	 * @param array $where
	 * @param array $load_filter (optional)
	 *
	 * @return DataModel_Fetch_Instances
	 */
	public static function fetchInstances( array $where = [], array $load_filter = [] ) : DataModel_Fetch_Instances;

	/**
	 *
	 * @param array $where
	 *
	 * @return DataModel_Fetch_IDs
	 */
	public static function fetchIDs( array $where = [] ) : DataModel_Fetch_IDs;

//-- Save ---------------------------------------

	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save() : void;

	/**
	 * @param array $data
	 * @param array $where
	 */
	public static function updateData( array $data, array $where ) : void;

//-- Delete -------------------------------------
	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() : void;


//-- Forms --------------------------------------
	/**
	 *
	 * @param string                              $form_name
	 * @param array|DataModel_PropertyFilter|null $property_filter
	 *
	 * @throws DataModel_Exception
	 *
	 * @return Form
	 */
	public function getForm( string $form_name, array|DataModel_PropertyFilter|null $property_filter = null ) : Form;

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( string $form_name = '' ) : Form;

	/**
	 * @param Form  $form
	 *
	 * @param array|null $data
	 * @param bool  $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, array|null $data = null, bool $force_catch = false ) : bool;


//-- Events -------------------------------------
	/**
	 *
	 */
	public function afterLoad() : void;


	/**
	 *
	 */
	public function beforeSave() : void;

	/**
	 *
	 */
	public function afterAdd() : void;

	/**
	 *
	 */
	public function afterUpdate() : void;

	/**
	 *
	 */
	public function afterDelete() : void;

}