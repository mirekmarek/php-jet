<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\REST;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_UserName extends DataListing_Column
{
	public const KEY = 'user_name';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('User name');
	}
}