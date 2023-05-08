<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Admin;

use Jet\DataListing_Export_CSV;
use Jet\Tr;

class Listing_Export_CSV extends DataListing_Export_CSV
{
	public const KEY = 'CSV';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Export to CSV file');
	}
	
	
	protected function generateFileName(): string
	{
		return 'events_admin_'.date('Ynd_His').'.csv';
	}
	
}