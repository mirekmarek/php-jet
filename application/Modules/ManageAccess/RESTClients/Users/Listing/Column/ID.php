<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\ManageAccess\RESTClients\Users;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_ID extends DataListing_Column
{
	public const KEY = 'id';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('ID');
	}
	
}