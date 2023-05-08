<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\ManageAccess\Visitors\Users;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_FirstName extends DataListing_Column
{
	public const KEY = 'first_name';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('First name');
	}
	
}