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

class Listing_Column_ContextObjectName extends DataListing_Column
{
	public const KEY = 'context_object_name';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Context object name');
	}
}