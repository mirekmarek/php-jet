<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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