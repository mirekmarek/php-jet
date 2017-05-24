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
trait BaseObject_Trait_MagicDebug
{

	/**
	 * @return array
	 */
	public function __debugInfo()
	{
		$vars = get_object_vars( $this );

		$r = [];
		foreach( $vars as $k => $v ) {
			if( $k[0]=='_' ) {
				continue;
			}
			$r[$k] = $v;
		}

		return $r;
	}

}