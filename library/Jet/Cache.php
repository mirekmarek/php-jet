<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class Cache
{

	/**
	 *
	 */
	public static function resetOPCache(): void
	{
		if( function_exists( 'opcache_reset' ) ) {
			opcache_reset();
		}
	}

}