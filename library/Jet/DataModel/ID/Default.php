<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_ID
 */
namespace Jet;

class DataModel_ID_Default extends DataModel_ID_Abstract {
	const MIN_LEN = 3;
	const MAX_LEN = 50;
	const MAX_SUFFIX_NO = 9999;
	const DELIMITER = "_";

	/**
	 * @return int
	 */
	public function getMaxLength() {
		return static::MAX_LEN;
	}

	/**
	 *
	 * @param string $name
	 * @param callable $exists_check
	 * @return string
	 */
	public function generateID( $name, callable $exists_check  ) {
		$name  = trim( $name );

		$ID = Data_Text::removeAccents( $name );
		$ID = str_replace(" ", self::DELIMITER, $ID);
		$ID = preg_replace("/[^a-z0-9".self::DELIMITER."]/i", "", $ID);
		$ID = strtolower($ID);
		$ID = preg_replace( "~([".self::DELIMITER."]{2,})~", self::DELIMITER , $ID );
		$ID = substr($ID, 0, self::MAX_LEN);

		if( $exists_check( $ID, $exists_check ) ) {
			$_ID = substr($ID, 0, self::MAX_LEN - strlen( (string)self::MAX_SUFFIX_NO )  );

			for($c=1; $c<=self::MAX_SUFFIX_NO; $c++) {
				$ID = $_ID.$c;

				if( !$exists_check( $ID )) {
					break;
				}
			}
		}

		return $ID;
	}


	/**
	 * Returns true if the ID format is valid
	 *
	 * @param string $ID
	 * @return bool
	 */
	public function checkFormat( $ID ) {
		return (bool)preg_match("/^([a-z0-9".self::DELIMITER."]{".self::MIN_LEN.",".self::MAX_LEN."})$/", $ID);
	}

}