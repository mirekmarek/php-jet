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

class DataModel_ID_UniqueString extends DataModel_ID_Abstract {
	/**
	 * @var string
	 */
	protected $ID_property_name = 'ID';


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
			$ID = uniqid(date('Ymdhis'), false);

			$this->_values[$this->ID_property_name] = $ID;
		}
	}

	/**
	 * @param mixed $backend_save_result
	 *
	 */
	public function afterSave($backend_save_result)
	{
	}

}