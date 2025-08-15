<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Logger;

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
	
	public function getExportHeader(): string
	{
		return $this->getKey();
	}
	
	/**
	 * @param Event $item
	 * @return string
	 */
	public function getExportData( mixed $item ): string
	{
		return $item->getUserId();
	}
	
}