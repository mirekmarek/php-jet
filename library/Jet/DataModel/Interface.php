<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface DataModel_Interface extends BaseObject_Serializable
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
	 * @return DataModel_Id
	 */
	public static function getEmptyIdObject();

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend
	 */
	public static function getBackendInstance();

//-- InternalState ------------------------------

	/**
	 * Loads DataModel.
	 *
	 * @param DataModel_Id|array $id
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel
	 */
	public static function load( $id );

	/**
	 *
	 * @return DataModel_Id
	 */
	public function getIdObject();

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

//-- Load ---------------------------------------

	/**
	 *
	 */
	public function rollbackBackendTransaction();

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
	public function updateData( array $data, array $where );

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
	 * @param array $data
	 * @param bool  $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data = null, $force_catch = false );

//-- Fetch --------------------------------------

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( array $where = [] );

	/**
	 *
	 * @param array| $where
	 *
	 * @return DataModel
	 */
	public static function fetchOneObject( array $where );

	/**
	 *
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Object_Assoc
	 */
	public static function fetchObjects( array  $where = [] );

	/**
	 *
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Object_Ids
	 */
	public static function fetchObjectIDs( array $where = [] );


	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_All
	 */
	public static function fetchDataAll( array $load_items, array  $where = [] );

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function fetchDataAssoc( array $load_items, array  $where = [] );

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_Pairs
	 */
	public static function fetchDataPairs( array $load_items, array  $where = [] );

	/**
	 *
	 * @param array $load_items
	 * @param array $where
	 *
	 * @return mixed|null
	 */
	public static function fetchDataRow( array $load_items, array  $where = [] );

	/**
	 *
	 * @param array $load_item
	 * @param array $where
	 *
	 * @return mixed|null
	 */
	public static function fetchDataOne( $load_item, array  $where = [] );

	/**
	 *
	 * @param string $select_item
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_Col
	 */
	public static function fetchDataCol( $select_item, array  $where = [] );


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