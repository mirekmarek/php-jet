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
	const DELIMITER = '_';

	/**
	 * @return int
	 */
	public function getMaxLength() {
		return static::MAX_LEN;
	}

	/**
	 * Generate unique ID
	 *
	 * @param DataModel $data_model_instance
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 */
	public function generate( DataModel $data_model_instance, $called_after_save = false, $backend_save_result = null ) {

		foreach( $this->values as $ID_property_name=>$value ) {
			if($value===null || $value==='') {
				$this->generateUniqueID( $data_model_instance, $ID_property_name );
			}
		}
	}

	/**
	 * @param DataModel $data_model_instance
	 * @param string $ID_property_name
	 */
	public function generateUniqueID( DataModel $data_model_instance, $ID_property_name  ) {
		//do {
			$time = floor(microtime(true) * 1000);

			$unique_ID = uniqid('', true);

			$u_name = substr(php_uname('n'), 0,14);

			$ID = $u_name.$time .$unique_ID;

			$ID = substr( preg_replace('~[^a-zA-Z0-9]~', '_', $ID), 0, 50);

			$this->values[$ID_property_name] = $ID;
		//} while( $this->getExists() );
	}


	/**
	 *
	 * @param DataModel $data_model_instance
	 * @param string $ID_property_name
	 * @param string $object_name
	 *
	 * @throws DataModel_ID_Exception
	 * @return string
	 */
	public function generateNameID( DataModel $data_model_instance, $ID_property_name, $object_name  ) {
		$object_name  = trim( $object_name );

		$ID = Data_Text::removeAccents( $object_name );
		$ID = str_replace(' ', static::DELIMITER, $ID);
		$ID = preg_replace('/[^a-z0-9'.static::DELIMITER.']/i', '', $ID);
		$ID = strtolower($ID);
		$ID = preg_replace( '~(['.static::DELIMITER.']{2,})~', static::DELIMITER , $ID );
		$ID = substr($ID, 0, static::MAX_LEN);


		$this->values[$ID_property_name] = $ID;

		if( $this->getExists() ) {
			$_ID = substr($ID, 0, static::MAX_LEN - strlen( (string)static::MAX_SUFFIX_NO )  );

			for($c=1; $c<=static::MAX_SUFFIX_NO; $c++) {
				$this->values[$ID_property_name] = $_ID.$c;

				if( !$this->getExists()) {
					return;
				}
			}

			throw new DataModel_ID_Exception(
				'ID generate: Reached the maximim numbers of attemps. (Maximim: '.static::MAX_SUFFIX_NO.')',
				DataModel_ID_Exception::CODE_ID_GENERATE_REACHED_THE_MAXIMUM_NUMBER_OF_ATTEMPTS
			);
		}
	}


	/**
	 * Returns true if the ID format is valid
	 *
	 * @param string $ID
	 * @return bool
	 */
	public function checkFormat( $ID ) {
		return (bool)preg_match('/^([a-z0-9'.static::DELIMITER.']{'.static::MIN_LEN.','.static::MAX_LEN.'})$/', $ID);
	}

}