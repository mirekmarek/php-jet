<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Admin;

use Jet\DataListing_Column;
use Jet\Tr;

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
	 * @noinspection PhpFullyQualifiedNameUsageInspection
	 * @var \JetApplicationModule\Admin\Logger\Event $item
	 * @return string
	 */
	public function getExportData( mixed $item ): string
	{
		return $item->getEventMessage();
	}

}