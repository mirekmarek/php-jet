<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait BaseObject_Trait_MagicSleep
{
	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep()
	{
		$vars = get_object_vars( $this );
		foreach( $vars as $k => $v ) {
			if( substr( $k, 0, 2 )==='__' ) {
				unset( $vars[$k] );
			}
		}

		return array_keys( $vars );
	}

}