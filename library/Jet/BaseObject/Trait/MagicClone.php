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
trait BaseObject_Trait_MagicClone
{

	/**
	 *
	 */
	public function __clone()
	{
		//debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

		$properties = get_object_vars( $this );

		foreach( $properties as $key => $val ) {
			if( is_object( $val ) ) {
				$this->{$key} = clone $val;
			}
		}
	}
}