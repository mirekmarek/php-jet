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
class SysConf_Jet_Data_Paginator
{
	protected static int $max_items_per_page = 500;

	/**
	 * @return int
	 */
	public static function getMaxItemsPerPage(): int
	{
		return static::$max_items_per_page;
	}

	/**
	 * @param int $max_items_per_page
	 */
	public static function setMaxItemsPerPage( int $max_items_per_page ): void
	{
		static::$max_items_per_page = $max_items_per_page;
	}


}