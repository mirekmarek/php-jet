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
trait DataModel_Trait_Fetch
{

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	public static function createQuery( array $where = [] )
	{

		/**
		 * @var DataModel_Interface $this
		 */
		$query = new DataModel_Query( static::getDataModelDefinition() );
		$query->setWhere( $where );

		return $query;
	}


	/**
	 *
	 * @param array $where
	 * @param array $load_filter (optional)
	 *
	 * @return bool|DataModel
	 */
	public static function fetchOneObject( array $where, array $load_filter = [] )
	{

		$query = static::createQuery( $where );
		$query->setLimit( 1 );

		$fetch = new DataModel_Fetch_Object_Assoc( $query );
		if( $load_filter ) {
			$fetch->setLoadFilter( $load_filter );
		}

		foreach( $fetch as $object ) {
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
	public static function fetchObjects( array $where = [], array $load_filter = [] )
	{

		$fetch = new DataModel_Fetch_Object_Assoc( static::createQuery( $where ) );
		if( $load_filter ) {
			$fetch->setLoadFilter( $load_filter );
		}

		return $fetch;
	}

	/**
	 *
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Object_Ids
	 */
	public static function fetchObjectIds( array $where = [] )
	{
		return new DataModel_Fetch_Object_Ids( static::createQuery( $where ) );
	}


	/**
	 *
	 * @param array $load_properties
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_All
	 */
	public static function fetchDataAll( array $load_properties, array  $where = [] )
	{
		return new DataModel_Fetch_Data_All( $load_properties, static::createQuery( $where ) );
	}

	/**
	 *
	 * @param array $load_properties
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function fetchDataAssoc( array $load_properties, array  $where = [] )
	{
		return new DataModel_Fetch_Data_Assoc( $load_properties, static::createQuery( $where ) );
	}

	/**
	 *
	 * @param array $load_properties
	 * @param array $where
	 *
	 * @return DataModel_Fetch_Data_Pairs
	 */
	public static function fetchDataPairs( array $load_properties, array  $where = [] )
	{
		return new DataModel_Fetch_Data_Pairs( $load_properties, static::createQuery( $where ) );
	}

	/**
	 *
	 * @param array $load_properties
	 * @param array $where
	 *
	 * @return mixed|null
	 */
	public static function fetchDataRow( array $load_properties, array  $where = [] )
	{
		$query = static::createQuery( $where );
		$query->setSelect( $load_properties );

		/**
		 * @var DataModel         $this
		 * @var DataModel_Backend $backend
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
	public static function fetchDataOne( $load_item, array  $where = [] )
	{

		$query = static::createQuery( $where );
		$query->setSelect( [ $load_item ] );

		/**
		 * @var DataModel         $this
		 * @var DataModel_Backend $backend
		 */
		$backend = static::getBackendInstance();

		return $backend->fetchOne( $query );
	}

	/**
	 *
	 * @param string $select_item
	 * @param array  $where
	 *
	 * @return DataModel_Fetch_Data_Col
	 */
	public static function fetchDataCol( $select_item, array  $where = [] )
	{
		$query = static::createQuery( $where );

		return new DataModel_Fetch_Data_Col( $select_item, $query );
	}

}