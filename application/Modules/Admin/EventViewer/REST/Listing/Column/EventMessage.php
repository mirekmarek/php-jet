<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\REST;

use Jet\DataListing_Column;
use Jet\Tr;
use JetApplication\Logger_REST_Event as Event;

class Listing_Column_EventMessage extends DataListing_Column
{
	public const KEY = 'event_message';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Event message');
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
		return $item->getEventMessage();
	}

}