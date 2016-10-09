<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DateTime
 */
namespace Jet;

class Data_DateTime extends \DateTime {

	/**
	 * @return string
	 */
	public function  __toString() {
		return (string)$this->format('Y-m-d\TH:i:s');
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->__toString();
	}

	/**
	 * @return Data_DateTime
	 */
	public static function now() {
		$date = new static(date('Y-m-d\TH:i:s'));

		return $date;
	}
}
