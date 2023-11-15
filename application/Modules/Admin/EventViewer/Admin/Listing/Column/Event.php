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
	 * @noinspection PhpFullyQualifiedNameUsageInspection
	 * @var \JetApplicationModule\Admin\Logger\Event $item
	 * @return string
	 */
	public function getExportData( mixed $item ): string
	{
		return $item->getEvent();
	}
	
}