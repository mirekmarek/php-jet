<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\ManageAccess\Visitors\Roles;

use Jet\DataListing_Column;

class Listing_Column_Edit extends DataListing_Column
{
	public const KEY = '_edit_';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return '';
	}
	
	public function getDisallowSort(): bool
	{
		return true;
	}
	
}