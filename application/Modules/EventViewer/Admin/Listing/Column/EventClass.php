<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Admin;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_EventClass extends DataListing_Column
{
	public const KEY = 'event_class';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Event class');
	}
}