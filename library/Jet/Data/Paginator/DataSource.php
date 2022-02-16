<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public function setPagination( int $limit, int $offset ): void;


	/**
	 * @return int
	 */
	public function getCount(): int;
}