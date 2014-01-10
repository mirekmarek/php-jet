<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DateTime
 */
namespace Jet;

class DateTime extends \DateTime {

	/**
	 * @return string
	 */
	public function  __toString() {
		return (string)$this->format(self::ISO8601);
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->__toString();
	}

	/**
	 * @return DateTime
	 */
	public static function now() {
		return new self(date(self::ISO8601));
	}
}