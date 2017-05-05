<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Id_Name
 * @package Jet
 */
class DataModel_Id_Name extends DataModel_Id_Abstract
{
	const MIN_LEN = 3;
	const MAX_LEN = 50;
	const MAX_SUFFIX_NO = 9999;
	const DELIMITER = '_';

	/**
	 * @var string
	 */
	protected $id_property_name = 'id';

	/**
	 * @var string
	 */
	protected $get_name_method_name = 'getName';

	/**
	 *
	 *
	 * @throws DataModel_Exception
	 */
	public function generate()
	{

		if( !array_key_exists( $this->id_property_name, $this->_values ) ) {
			throw new DataModel_Exception(
				'Class \''.$this->_data_model_class_name.'\': Property \''.$this->id_property_name.'\' does not exist. Please configure ID class by @JetDataModel:id_options, or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !$this->_values[$this->id_property_name] ) {
			$this->generateNameId(
				$this->id_property_name, $this->_data_model_instance->{$this->get_name_method_name}()
			);
		}
	}

	/**
	 *
	 * @param string $id_property_name
	 * @param string $object_name
	 *
	 * @throws DataModel_Id_Exception
	 */
	public function generateNameId( $id_property_name, $object_name )
	{

		$object_name = trim( $object_name );

		$id = Data_Text::removeAccents( $object_name );
		$id = str_replace( ' ', static::DELIMITER, $id );
		$id = preg_replace( '/[^a-z0-9'.static::DELIMITER.']/i', '', $id );
		$id = strtolower( $id );
		$id = preg_replace( '~(['.static::DELIMITER.']{2,})~', static::DELIMITER, $id );
		$id = substr( $id, 0, static::MAX_LEN );


		$this->_values[$id_property_name] = $id;

		if( $this->getExists() ) {
			$_id = substr( $id, 0, static::MAX_LEN-strlen( (string)static::MAX_SUFFIX_NO ) );

			for( $c = 1; $c<=static::MAX_SUFFIX_NO; $c++ ) {
				$this->_values[$id_property_name] = $_id.$c;

				if( !$this->getExists() ) {
					return;
				}
			}

			throw new DataModel_Id_Exception(
				'ID generate: Reached the maximum numbers of attempts. (Maximum: '.static::MAX_SUFFIX_NO.')',
				DataModel_Id_Exception::CODE_ID_GENERATE_REACHED_THE_MAXIMUM_NUMBER_OF_ATTEMPTS
			);
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function getExists()
	{
		$query = $this->getQuery();

		return (bool)$this->getDataModelDefinition()->getBackendInstance()->getCount( $query );
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 * @throws DataModel_Exception
	 */
	public function afterSave( $backend_save_result )
	{
	}

}