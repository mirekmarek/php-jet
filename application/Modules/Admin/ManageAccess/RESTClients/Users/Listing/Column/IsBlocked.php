<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\ManageAccess\RESTClients\Users;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_IsBlocked extends DataListing_Column
{
	public const KEY = 'is_blocked';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Is blocked');
	}
	
	public function getOrderByAsc(): array|string
	{
		return '+user_is_blocked';
	}
	
	public function getOrderByDesc(): array|string
	{
		return '-user_is_blocked';
	}
	
	
}