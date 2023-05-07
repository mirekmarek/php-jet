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
trait DataListing_Traits_PrevNext
{
	public function getPrevItemId( int $item_id ): string|int|null
	{
		$all_ids = $this->getAllIds();
		
		if($all_ids) {
			$index = array_search( $item_id, $all_ids );
			
			if( $index ) {
				$index--;
				if( isset( $all_ids[$index] ) ) {
					return $all_ids[$index];
				}
			}
		}
		
		return null;
	}
	
	public function getNextItemId( int $item_id ): string|int|null
	{
		$all_ids = $this->getAllIds();
		
		if($all_ids) {
			$index = array_search( $item_id, $all_ids );
			if( $index !== false ) {
				$index++;
				if( isset( $all_ids[$index] ) ) {
					return $all_ids[$index];
				}
			}
		}
		
		return null;
	}
	
}