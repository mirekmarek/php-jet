<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

trait DataModel_Backend_Trait_Fetch {
	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchAll( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchAll' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchAssoc( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchAssoc' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchPairs( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchPairs' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchRow( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchRow' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchOne( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchOne' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchCol( DataModel_Query $query ): array
	{
		$data = $this->getDbRead()->fetchCol(
			$this->createSelectQuery( $query )
		);
		
		foreach( $data as $i => $d ) {
			foreach( $query->getSelect() as $item ) {
				/**
				 * @var DataModel_Query_Select_Item $item
				 * @var DataModel_Definition_Property $property
				 */
				$property = $item->getItem();
				
				if( !($property instanceof DataModel_Definition_Property) ) {
					continue;
				}
				
				if( $property->getMustBeSerializedBeforeStore() ) {
					$data[$i] = $this->unserialize( $data[$i] );
				}
				
				$property->checkValueType( $data[$i] );
				
				break;
			}
		}
		
		return $data;
	}
	
}