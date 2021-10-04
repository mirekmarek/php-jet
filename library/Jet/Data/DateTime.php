<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use DateTime;

/**
 *
 */
class Data_DateTime extends DateTime
{

	/**
	 * @return Data_DateTime
	 */
	public static function now(): Data_DateTime
	{
		return new static( date( 'Y-m-d\TH:i:s' ) );
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->__toString();
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->format( 'Y-m-d\TH:i:s' );
	}
}
