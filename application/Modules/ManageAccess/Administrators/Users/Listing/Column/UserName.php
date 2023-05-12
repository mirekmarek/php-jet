<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\ManageAccess\Administrators\Users;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_UserName extends DataListing_Column
{
	public const KEY = 'username';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Username');
	}
	
}