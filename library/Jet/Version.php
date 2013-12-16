<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Version
 */

namespace Jet;

class Version extends Object {

	const API_VERSION = 201208;

	/**
	 * @return string
	 */
	public static function getVersionNumber() {
		return static::API_VERSION;
	}

	/**
	 * @return int
	 */
	public static function getAPIVersionNumber() {
		return static::API_VERSION;
	}

	/**
	 * @param int $API_version
	 *
	 * @return bool
	 */
	public static function getAPIIsCompatible( $API_version ) {
		return $API_version==static::API_VERSION;
	}
}