<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected bool $only_date = false;

	/**
	 * @return static
	 */
	public static function now(): static
	{
		return new static( date( 'Y-m-d\TH:i:s' ) );
	}
	
	/**
	 * @return bool
	 */
	public function isOnlyDate(): bool
	{
		return $this->only_date;
	}
	
	/**
	 * @param bool $only_date
	 */
	public function setOnlyDate( bool $only_date ): void
	{
		$this->only_date = $only_date;
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
		if($this->only_date) {
			return $this->format( 'Y-m-d' );
		} else {
			return $this->format( 'Y-m-d\TH:i:s' );
		}
	}
}
