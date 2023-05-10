<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\ManageAccess\Administrators\Roles;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_Description extends DataListing_Column
{
	public const KEY = 'description';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Description');
	}
	
}