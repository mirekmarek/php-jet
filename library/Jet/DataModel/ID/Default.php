<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
	 * @param DataModel $data_model_instance
	 * @param string $object_name
	 *
	 * @throws DataModel_ID_Exception
	 *
	 * @return string
	 */
	public function generateID( DataModel $data_model_instance, $object_name  ) {
		$object_name  = trim( $object_name );

		$ID = Data_Text::removeAccents( $object_name );
		$ID = str_replace(" ", static::DELIMITER, $ID);
		$ID = preg_replace("/[^a-z0-9".static::DELIMITER."]/i", "", $ID);
		$ID = strtolower($ID);
		$ID = preg_replace( "~([".static::DELIMITER."]{2,})~", static::DELIMITER , $ID );
		$ID = substr($ID, 0, static::MAX_LEN);


		$this->values[DataModel::DEFAULT_ID_COLUMN_NAME] = $ID;

		if( $data_model_instance->getIDExists(  $this ) ) {
			$_ID = substr($ID, 0, static::MAX_LEN - strlen( (string)static::MAX_SUFFIX_NO )  );

			for($c=1; $c<=static::MAX_SUFFIX_NO; $c++) {
				$this->values[DataModel::DEFAULT_ID_COLUMN_NAME] = $_ID.$c;

				if( !$data_model_instance->getIDExists( $this )) {
					return $this->values[DataModel::DEFAULT_ID_COLUMN_NAME];
				}
			}

			throw new DataModel_ID_Exception(
				"ID generate: Reached the maximim numbers of attemps. (Maximim: ".static::MAX_SUFFIX_NO.")",
				DataModel_ID_Exception::CODE_ID_GENERATE_REACHED_THE_MAXIMUM_NUMBER_OF_ATTEMPTS
			);
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
		return (bool)preg_match("/^([a-z0-9".static::DELIMITER."]{".static::MIN_LEN.",".static::MAX_LEN."})$/", $ID);
	}

}