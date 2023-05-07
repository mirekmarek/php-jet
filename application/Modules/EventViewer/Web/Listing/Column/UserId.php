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

class Listing_Column_UserId extends DataListing_Column
{
	public const KEY = 'user_id';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('User ID');
	}
}