<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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

interface DataModel_Interface extends BaseObject_Serializable_REST, BaseObject_Reflection_ParserInterface {
//-- Definition ---------------------------------

    /**
     * Returns model definition
     *
     * @param string $class_name (optional)
     *
     * @return DataModel_Definition_Model_Abstract
     */
    public static function getDataModelDefinition( $class_name='' );


    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Main
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name );


//-- ID -----------------------------------------
    /**
     * Returns ID
     *
     * @return DataModel_ID_Abstract
     */
    public function getIdObject();


    /**
     * @return DataModel_ID_Abstract
     */
    public static function getEmptyIdObject();

    /**
     * @param string $ID
     *
     * @return DataModel_ID_Abstract
     */
    public static function createIdObject( $ID );


    /**
     * @return DataModel_ID_Abstract
     */
    public function resetIdObject();

    /**
     * Generate unique ID
     *
     * @param bool $called_after_save (optional, default = false)
     * @param mixed $backend_save_result  (optional, default = null)
     *
     * @throws DataModel_Exception
     */
    public function generateIdObject(  $called_after_save = false, $backend_save_result = null  );

//-- InternalState ------------------------------
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

    /**
     * @return bool
     */
    public function getIsSaved();

    /**
     *
     */
    public function setIsSaved();

//-- Backend ------------------------------------

    /**
     * Returns backend instance
     *
     * @return DataModel_Backend_Abstract
     */
    public static function getBackendInstance();

    /**
     * @return bool
     */
    public function getBackendTransactionStarted();

    /**
     * @return bool
     */
    public function getBackendTransactionStartedByThisInstance();

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function startBackendTransaction( DataModel_Backend_Abstract $backend );

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function commitBackendTransaction( DataModel_Backend_Abstract $backend );

    /**
     * @param DataModel_Backend_Abstract $backend
     */
    public function rollbackBackendTransaction( DataModel_Backend_Abstract $backend );

//-- Load ---------------------------------------

    /**
     * Loads DataModel.
     *
     * @param DataModel_ID_Abstract|array $ID
     *
     * @throws DataModel_Exception
     *
     * @return DataModel
     */
    public static function load( $ID );

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
     * @param string $form_name
     * @param array $properties_list
     * @throws DataModel_Exception
     *
     * @return Form
     */
    public function getForm( $form_name, array $properties_list );

    /**
     * @param string $form_name
     *
     * @return Form
     */
    public function getCommonForm( $form_name='' );


    /**
     * @return array
     */
    public function getCommonFormPropertiesList();

    /**
     * @param Form $form
     *
     * @param array $data
     * @param bool $force_catch
     *
     * @return bool;
     */
    public function catchForm( Form $form, $data=null, $force_catch=false );

//-- Fetch --------------------------------------

    /**
     * @param array $where
     *
     * @return DataModel_Query
     */
    public function createQuery( array $where= []);

    /**
     *
     * @param array| $where
     * @return DataModel
     */
    public function fetchOneObject( array $where );

    /**
     *
     * @param array $where
     * @return DataModel_Fetch_Object_Assoc
     */
    public function fetchObjects( array  $where= []);

    /**
     *
     * @param array $where
     * @return DataModel_Fetch_Object_IDs
     */
    public function fetchObjectIDs( array $where= []);


    /**
     *
     * @param array $load_items
     * @param array $where
     * @return DataModel_Fetch_Data_All
     */
    public function fetchDataAll( array $load_items, array  $where= []);

    /**
     *
     * @param array $load_items
     * @param array $where
     * @return DataModel_Fetch_Data_Assoc
     */
    public function fetchDataAssoc( array $load_items, array  $where= []);

    /**
     *
     * @param array $load_items
     * @param array $where
     * @return DataModel_Fetch_Data_Pairs
     */
    public function fetchDataPairs( array $load_items, array  $where= []);

    /**
     *
     * @param array $load_items
     * @param array $where
     * @return mixed|null
     */
    public function fetchDataRow( array $load_items, array  $where= []);

    /**
     *
     * @param array $load_item
     * @param array $where
     *
     * @return mixed|null
     */
    public function fetchDataOne( $load_item, array  $where= []);

    /**
     *
     * @param $load_item
     * @param array $where
     *
     * @return DataModel_Fetch_Data_Col
     */
    public function fetchDataCol( $load_item, array  $where= []);

//-- History ------------------------------------
    /**
     *
     * @return bool
     */
    public static function getHistoryEnabled();

//-- Cache --------------------------------------
    /**
     *
     * @return bool
     */
    public static function getCacheEnabled();

//-- Events -------------------------------------
    /**
     *
     */
    public function afterLoad();

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


//-- Helpers ------------------------------------
    /**
     * @param string $class
     *
     * @return string
     */
    public static function helper_getCreateCommand( $class );

    /**
     *
     * @param string $class
     * @param bool $including_history_backend (optional, default: true)
     * @param bool $including_cache_backend (optional, default: true)
     * @return bool
     */
    public static function helper_create( $class, $including_history_backend=true, $including_cache_backend=true );


    /**
     * Update (actualize) DB table or tables
     *
     * @param string $class
     *
     * @return string
     */
    public static function helper_getUpdateCommand( $class );

    /**
     * Update (actualize) DB table or tables
     *
     * @param bool $including_history_backend (optional, default: true)
     * @param bool $including_cache_backend (optional, default: true)
     *
     * @param string $class
     */
    public static function helper_update( $class, $including_history_backend=true, $including_cache_backend=true  );

    /**
     * Drop (only rename by default) DB table or tables
     *
     * @param string $class
     */
    public static function helper_drop( $class );

}