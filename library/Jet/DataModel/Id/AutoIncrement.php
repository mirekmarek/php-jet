<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Id_AutoIncrement extends DataModel_Id
{
	/**
	 *
	 * @var string
	 */
	protected $id_property_name = 'id';


	/**
	 *
	 * @throws DataModel_Exception
	 *
	 */
	public function generate()
	{
		if( !array_key_exists( $this->id_property_name, $this->_values ) ) {
			throw new DataModel_Exception(
				'Class \''.$this->_data_model_class_name.'\': Property \''.$this->id_property_name.'\' does not exist. Please configure ID class by @JetDataModel:id_options, or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 * @throws DataModel_Exception
	 */
	public function afterSave( $backend_save_result )
	{
		if( !array_key_exists( $this->id_property_name, $this->_values ) ) {
			throw new DataModel_Exception(
				'Class \''.$this->_data_model_class_name.'\': Property \''.$this->id_property_name.'\' does not exist. Please configure ID class by @JetDataModel:id_options, or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		$this->_values[$this->id_property_name] = (int)$backend_save_result;
	}

}