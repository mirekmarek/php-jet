<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use Jet\DataListing_Column;
use Jet\Tr;

class Listing_Column_ID extends DataListing_Column
{
	public const KEY = '%<ID_PROPERTY>%';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('%<TXT_LISTING_TITLE_ID>%');
	}
	
}