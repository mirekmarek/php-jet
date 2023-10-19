<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Web;

use Jet\DataListing_Column;
use Jet\Tr;
use JetApplication\Logger_Web_Event as Event;

class Listing_Column_Event extends DataListing_Column
{
	public const KEY = 'event';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Event');
	}
	
	public function getExportHeader(): string
	{
		return $this->getKey();
	}
	
	/**
	 * @param Event $item
	 * @return string
	 */
	public function getExportData( mixed $item ): string
	{
		return $item->getEvent();
	}
	
}