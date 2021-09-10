<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
			@opcache_reset();
		}
	}

}