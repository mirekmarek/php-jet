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

class Listing_Column_ContextObjectId extends DataListing_Column
{
	public const KEY = 'context_object_id';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Context object ID');
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
		return $item->getContextObjectId();
	}
}