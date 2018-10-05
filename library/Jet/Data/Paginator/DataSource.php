<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface Data_Paginator_DataSource extends BaseObject_Interface_Serializable_JSON
{

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