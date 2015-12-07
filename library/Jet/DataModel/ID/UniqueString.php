<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @param DataModel $data_model_instance
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 * @throws DataModel_Exception
	 */
	public function generate( DataModel $data_model_instance, $called_after_save = false, $backend_save_result = null ) {

		if(!array_key_exists($this->ID_property_name, $this->values)) {
			throw new DataModel_Exception(
				'Class \''.$data_model_instance->getDataModelDefinition()->getClassName().'\': Property \''.$this->ID_property_name.'\' does not exist. Please configure ID class by @JetDataModel:ID_options, or define that property, or create your own ID class.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if(!$this->values[$this->ID_property_name]) {
			$this->generateUniqueID( $data_model_instance, $this->ID_property_name );
		}
	}
}