<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Web;

use Jet\DataListing_Column;
use Jet\Tr;

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
}