<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Fetch_Data_One
 * @package Jet
 */
class DataModel_Fetch_Data_One extends DataModel_Fetch_Data
{
	/**
	 * @var string
	 */
	protected $backend_fetch_method = 'fetchOne';
}