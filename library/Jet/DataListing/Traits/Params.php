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
trait DataListing_Traits_Params
{
	protected array $params = [];
	
	public function setParam( string $parameter, mixed $value ): void
	{
		$this->params[$parameter] = $value;
	}
	
	public function unsetParam( string $parameter ): void
	{
		unset( $this->params[$parameter] );
	}
	
	
	public function getURI(): string
	{
		$get_params = $this->params;
		
		foreach( $get_params as $k => $v ) {
			if( !$v ) {
				unset( $get_params[$k] );
			}
		}
		
		return '?' . http_build_query( $get_params );
	}
	
}