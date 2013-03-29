<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
		return (string) $this->format(self::ISO8601);
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