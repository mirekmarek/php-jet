<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\Auth\Admin\Users;

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