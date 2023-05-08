<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Content\Articles\Admin;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_DateTime extends DataListing_Column
{
	public const KEY = 'date_time';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Date and time');
	}
	
	public function getDisallowSort(): bool
	{
		return true;
	}
	
}