<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Content\Articles\Admin;

use Jet\DataListing_Column;

class Listing_Column_Edit extends DataListing_Column
{
	public const KEY = '_edit_';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return '';
	}
	
	public function getDisallowSort(): bool
	{
		return true;
	}
	
}