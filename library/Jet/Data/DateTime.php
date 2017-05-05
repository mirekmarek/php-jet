<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Data_DateTime
 * @package Jet
 */
class Data_DateTime extends \DateTime
{

	/**
	 * @return Data_DateTime
	 */
	public static function now()
	{
		$date = new static( date( 'Y-m-d\TH:i:s' ) );

		return $date;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->__toString();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->format( 'Y-m-d\TH:i:s' );
	}
}
