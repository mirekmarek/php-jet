<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Fetch_Data_Col
 * @package Jet
 */
class DataModel_Fetch_Data_Col extends DataModel_Fetch_Data
{
	/**
	 * @var string
	 */
	protected $backend_fetch_method = 'fetchCol';

	/**
	 *
	 * @param string          $select_item
	 * @param DataModel_Query $query
	 *
	 */
	public function __construct( $select_item, DataModel_Query $query )
	{
		parent::__construct( [ $select_item ], $query );
	}

}