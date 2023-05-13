<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


abstract class DataListing_Operation extends DataListing_ElementBase
{
	abstract public function getKey(): string;
	
	abstract public function getTitle(): string;
	
	public function getIcon() : string
	{
		return '';
	}
	
	abstract public function perform(): void;
	
}