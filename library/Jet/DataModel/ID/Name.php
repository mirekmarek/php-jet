<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_ID
 */
namespace Jet;

class DataModel_ID_Name extends DataModel_ID_Abstract {
	const MIN_LEN = 3;
	const MAX_LEN = 50;
	const MAX_SUFFIX_NO = 9999;
	const DELIMITER = '_';

	/**
	 * @var string
	 */
	protected $ID_property_name = 'ID';

	/**
	 * @var string
	 */
	protected $get_name_method_name = 'getName';

	/**
	 *
	 *
	 * @throws DataModel_Exception
	 */
	public function generate() {

		if(!array_key_exists($this->ID_property_name, $this->_values)) {
			throw new DataModel_Exception(
				'Class \''.$this->_data_model_class_name.'\': Property \''.$this->ID_property_name.'\' does not exist. Please configure ID class by @JetDataModel:ID_options, or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if(!$this->_values[$this->ID_property_name]) {
			$this->generateNameID( $this->ID_property_name, $this->_data_model_instance->{$this->get_name_method_name}() );
		}
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 * @throws DataModel_Exception
	 */
	public function afterSave( $backend_save_result  )
	{
	}

	/**
	 *
	 * @param string $ID_property_name
	 * @param string $object_name
	 *
	 * @throws DataModel_ID_Exception
	 */
	public function generateNameID(
		$ID_property_name, $object_name
	) {

		$object_name  = trim( $object_name );

		$ID = Data_Text::removeAccents( $object_name );
		$ID = str_replace(' ', static::DELIMITER, $ID);
		$ID = preg_replace('/[^a-z0-9'.static::DELIMITER.']/i', '', $ID);
		$ID = strtolower($ID);
		$ID = preg_replace( '~(['.static::DELIMITER.']{2,})~', static::DELIMITER , $ID );
		$ID = substr($ID, 0, static::MAX_LEN);


		$this->_values[$ID_property_name] = $ID;

		if( $this->getExists() ) {
			$_ID = substr($ID, 0, static::MAX_LEN - strlen( (string)static::MAX_SUFFIX_NO )  );

			for($c=1; $c<=static::MAX_SUFFIX_NO; $c++) {
				$this->_values[$ID_property_name] = $_ID.$c;

				if( !$this->getExists()) {
					return;
				}
			}

			throw new DataModel_ID_Exception(
				'ID generate: Reached the maximum numbers of attempts. (Maximum: '.static::MAX_SUFFIX_NO.')',
				DataModel_ID_Exception::CODE_ID_GENERATE_REACHED_THE_MAXIMUM_NUMBER_OF_ATTEMPTS
			);
		}
	}


	/**
	 *
	 * @return bool
	 */
	public function getExists() {
		$query = $this->getQuery();

		return (bool)$this->getDataModelDefinition()->getBackendInstance()->getCount( $query );
	}

}