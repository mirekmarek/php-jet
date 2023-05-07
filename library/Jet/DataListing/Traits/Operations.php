<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait DataListing_Traits_Operations
{
	/**
	 * @var DataListing_Operation[]
	 */
	protected array $operations = [];
	
	/**
	 * @return DataListing_Operation[]
	 */
	public function getOperations() : array
	{
		return $this->operations;
	}
	
	public function addOperation( DataListing_Operation $operation ) : void
	{
		$this->operations[$operation->getKey()] = $operation;
		$operation->setListing( $this );
	}
	
	public function operationExists( string $operation ) : bool
	{
		return isset( $this->operations[$operation] );
	}
	
	public function operation( string $operation ) : DataListing_Operation
	{
		return $this->operations[$operation];
	}
	
}
