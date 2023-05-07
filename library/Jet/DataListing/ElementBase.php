<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class DataListing_ElementBase extends BaseObject
{
	
	protected DataListing $listing;
	
	public function setListing( DataListing $listing ): void
	{
		$this->listing = $listing;
	}
	
	public function getListing(): DataListing
	{
		return $this->listing;
	}
}