<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_IDController_AutoIncrement extends DataModel_IDController
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
	public function beforeSave()
	{
		$this->_check();
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 * @throws DataModel_Exception
	 */
	public function afterSave( $backend_save_result )
	{
		$this->_check();

		$this->values[$this->id_property_name] = (int)$backend_save_result;
	}

	protected function _check() {
		if( !array_key_exists( $this->id_property_name, $this->values ) ) {
			throw new DataModel_Exception(
				'Class \''.$this->data_model_class_name.'\': Property \''.$this->id_property_name.'\' does not exist. Please configure ID class by @JetDataModel:id_controller_options, or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

	}

}