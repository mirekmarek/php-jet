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


//-- Id -----------------------------------------
    /**
     *
     * @return DataModel_Id_Abstract
     */
    public function getIdObject();


    /**
     * @return DataModel_Id_Abstract
     */
    public static function getEmptyIdObject();

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
     * Loads DataModel.
     *
     * @param DataModel_Id_Abstract|array $id
     *
     * @throws DataModel_Exception
     *
     * @return DataModel
     */
    public static function load( $id );

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
     * @param array|DataModel_PropertyFilter|null $property_filter
     * @throws DataModel_Exception
     *
     * @return Form
     */
    public function getForm( $form_name, $property_filter=null );

    /**
     * @param string $form_name
     *
     * @return Form
     */
    public function getCommonForm( $form_name='' );

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
     * @return DataModel_Fetch_Object_Ids
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

}