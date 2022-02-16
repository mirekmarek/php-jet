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
class Cache
{

	/**
	 *
	 */
	public static function resetOPCache(): void
	{
		if( function_exists( 'opcache_reset' ) ) {
			Debug_ErrorHandler::doItSilent(function() {
				opcache_reset();
			});
		}
	}

}