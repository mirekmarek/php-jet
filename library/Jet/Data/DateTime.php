<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	public static function now() : Data_DateTime
	{
		$date = new static( date( 'Y-m-d\TH:i:s' ) );

		return $date;
	}

	/**
	 * @return string
	 */
	public function toString() : string
	{
		return $this->__toString();
	}

	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return (string)$this->format( 'Y-m-d\TH:i:s' );
	}
}
