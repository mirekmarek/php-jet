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
	 * @return list<mixed>
	 */
	public function fetchAll( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchAll' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return array<string,mixed>
	 */
	public function fetchAssoc( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchAssoc' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return array<string,mixed>
	 */
	public function fetchPairs( DataModel_Query $query ): mixed
	{
		return $this->_fetch( $query, 'fetchPairs' );
	}
	
	/**
	 * @param DataModel_Query $query
	 *
	 * @return array<string,mixed>
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
	 * @return list<mixed>
	 */
	public function fetchCol( DataModel_Query $query ): array
	{
		$property_name = null;
		$property_definition = null;
		foreach( $query->getSelect() as $property_name=>$pd ) {
			/**
			 * @var DataModel_Query_Select_Item $pd
			 */
			$property = $pd->getItem();
			
			if( !($property instanceof DataModel_Definition_Property) ) {
				continue;
			}
			
			$property_definition = $property;
			break;
		}
		
		
		$data = $this->_fetch( $query, 'fetchAll' );
		$result = [];
		
		foreach( $data as $i => $d ) {
			$value = $d[$property_name];
			
			if( $property_definition->getMustBeSerializedBeforeStore() ) {
				$value = $this->unserialize( $value );
			}
			
			$property_definition->checkValueType( $value );
			
			$result[] = $value;
		}
		
		return $result;
	}
	
}