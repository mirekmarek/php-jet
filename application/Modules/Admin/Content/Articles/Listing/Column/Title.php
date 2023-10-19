<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\Content\Articles;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_Title extends DataListing_Column
{
	public const KEY = 'title';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Title');
	}
	
	public function getDisallowSort(): bool
	{
		return true;
	}
	
}