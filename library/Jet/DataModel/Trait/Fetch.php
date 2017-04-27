<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Trait_Fetch
 * @package Jet
 */
trait DataModel_Trait_Fetch {


    /**
     * @param array $where
     *
     * @return DataModel_Query
     */
    public function createQuery( array $where=[] ) {
        /**
         * @var DataModel $this
         */
        $query = new DataModel_Query(static::getDataModelDefinition() );
        $query->setMainDataModel( $this );
        $query->setWhere( $where );
        return $query;
    }

	/**
	 *
	 * @param array| $where
	 * @param array $load_filter (optional)
	 *
	 * @return bool|DataModel
	 */
    public function fetchOneObject( array $where, array $load_filter=[] ) {

        $query = $this->createQuery( $where );
        $query->setLimit(1);

        $fetch = new DataModel_Fetch_Object_Assoc( $query );
	    if($load_filter) {
	    	$fetch->setLoadFilter($load_filter);
	    }

        foreach($fetch as $object) {
            return $object;
        }

        return false;
    }

	/**
	 *
	 * @param array $where
	 * @param array $load_filter (optional)
	 *
	 * @return DataModel_Fetch_Object_Assoc
	 */
    public function fetchObjects( array $where= [], array $load_filter=[] ) {

        $fetch = new DataModel_Fetch_Object_Assoc( $this->createQuery($where) );
	    if($load_filter) {
		    $fetch->setLoadFilter($load_filter);
	    }

		return $fetch;
    }

    /**
     *
     * @param array $where
     * @return DataModel_Fetch_Object_Ids
     */
    public function fetchObjectIds(array $where= []) {
        return new DataModel_Fetch_Object_Ids(  $this->createQuery($where)  );
    }


    /**
     *
     * @param array $load_properties
     * @param array $where
     * @return DataModel_Fetch_Data_All
     */
    public function fetchDataAll( array $load_properties, array  $where= []) {
        return new DataModel_Fetch_Data_All( $load_properties, $this->createQuery($where) );
    }

    /**
     *
     * @param array $load_properties
     * @param array $where
     * @return DataModel_Fetch_Data_Assoc
     */
    public function fetchDataAssoc( array $load_properties, array  $where= []) {
        return new DataModel_Fetch_Data_Assoc( $load_properties, $this->createQuery($where) );
    }

    /**
     *
     * @param array $load_properties
     * @param array $where
     * @return DataModel_Fetch_Data_Pairs
     */
    public function fetchDataPairs( array $load_properties, array  $where= []) {
        return new DataModel_Fetch_Data_Pairs( $load_properties, $this->createQuery($where) );
    }

    /**
     *
     * @param array $load_properties
     * @param array $where
     * @return mixed|null
     */
    public function fetchDataRow( array $load_properties, array  $where= []) {
        $query = $this->createQuery( $where );
        $query->setSelect($load_properties);

        /**
         * @var DataModel $this
         * @var DataModel_Backend_Abstract $backend
         */
        $backend = static::getBackendInstance();
        return $backend->fetchRow( $query );

    }

    /**
     *
     * @param array $load_item
     * @param array $where
     *
     * @return mixed|null
     */
    public function fetchDataOne( $load_item, array  $where= []) {

        $query = $this->createQuery( $where );
        $query->setSelect( [$load_item] );

        /**
         * @var DataModel $this
         * @var DataModel_Backend_Abstract $backend
         */
        $backend = static::getBackendInstance();
        return $backend->fetchOne( $query );
    }

    /**
     *
     * @param $load_item
     * @param array $where
     *
     * @return DataModel_Fetch_Data_Col
     */
    public function fetchDataCol( $load_item, array  $where= []) {
        $query = $this->createQuery( $where );

        return new DataModel_Fetch_Data_Col( $load_item, $query );
    }

}