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
interface DataModel_Interface extends BaseObject_Interface_Serializable_JSON
{
//-- Definition ---------------------------------

	/**
	 * Returns model definition
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model
	 */
	public static function getDataModelDefinition( $class_name = '' );


	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public static function dataModelDefinitionFactory( $data_model_class_name );


//-- Id -----------------------------------------

	/**
	 * @return DataModel_IDController
	 */
	public static function getEmptyIDController();

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance();

//-- InternalState ------------------------------



	/**
	 *
	 * @return DataModel_IDController
	 */
	public function getIDController();

	/**
	 * Initializes new DataModel
	 *
	 */
	public function initNewObject();

	/**
	 * Returns true if the model instance is new (was not saved yet)
	 *
	 * @return bool
	 */
	public function getIsNew();

	/**
	 *
	 */
	public function setIsNew();

//-- Backend ------------------------------------

	/**
	 * @return bool
	 */
	public function getIsSaved();

	/**
	 *
	 */
	public function setIsSaved();

	/**
	 * @return bool
	 */
	public function getBackendTransactionStarted();

	/**
	 * @return bool
	 */
	public function getBackendTransactionStartedByThisInstance();

	/**
	 *
	 */
	public function startBackendTransaction();

	/**
	 *
	 */
	public function commitBackendTransaction();

	/**
	 *
	 */
	public function rollbackBackendTransaction();

//-- Load ---------------------------------------

	/**
	 * @return DataModel_PropertyFilter
	 */
	public function getLoadFilter();

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( array $where = [] );


	/**
	 * @param array $this_data
	 * @param array $related_data
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel
	 */
	public static function initByData( array $this_data, $related_data = [], DataModel_PropertyFilter $load_filter=null );


	/**
	 * Loads DataModel.
	 *
	 * @param array|string|int               $id_or_where
	 * @param array|DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel
	 */
	public static function load( $id_or_where, $load_filter = null );


	/**
	 * @param array $where_per_model
	 * @param array|string|null $order_by
	 * @param callable|null $item_key_generator
	 * @param array|DataModel_PropertyFilter|null $load_filter
	 *
	 * @return DataModel[]
	 */
	public static function fetch( array $where_per_model=[], $order_by=null, callable $item_key_generator = null, $load_filter=null );


	/**
	 * @param array $select
	 * @param array $where
	 * @param string $fetch_method
	 *
	 * @return mixed
	 *
	 * @throws DataModel_Query_Exception
	 */
	public static function fetchData( array $select, array $where, $fetch_method='fetchAll' );


	/**
	 *
	 * @param array $where
	 * @param array $load_filter (optional)
	 *
	 * @return DataModel_Fetch_Instances
	 */
	public static function fetchInstances( array $where = [], array $load_filter = [] );

	/**
	 *
	 * @param array $where
	 *
	 * @return DataModel_Fetch_IDs
	 */
	public static function fetchIDs( array $where = [] );

//-- Save ---------------------------------------

	/**
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save();

	/**
	 * @param array $data
	 * @param array $where
	 */
	public static function updateData( array $data, array $where );

//-- Delete -------------------------------------
	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete();


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
	public function getForm( $form_name, $property_filter = null );

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name = '' );

	/**
	 * @param Form  $form
	 *
	 * @param array|null $data
	 * @param bool  $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data = null, $force_catch = false );


//-- Events -------------------------------------
	/**
	 *
	 */
	public function afterLoad();


	/**
	 *
	 */
	public function beforeSave();

	/**
	 *
	 */
	public function afterAdd();

	/**
	 *
	 */
	public function afterUpdate();

	/**
	 *
	 */
	public function afterDelete();

}