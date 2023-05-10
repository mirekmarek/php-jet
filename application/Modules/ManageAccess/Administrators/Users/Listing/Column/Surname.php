<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\ManageAccess\Administrators\Users;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_Surname extends DataListing_Column
{
	public const KEY = 'surname';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Surname');
	}
	
}