<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Data_Paginator_DataSource_Interface
 * @package Jet
 */
interface Data_Paginator_DataSource_Interface /* extends Iterator*/ {

	/**
	 * @param int $limit
	 * @param int $offset
	 */
	public function setPagination( $limit, $offset );


	/**
	 * Returns total items count
	 * @return int
	 */
	public function getCount();
}