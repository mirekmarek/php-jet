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
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Array extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_ARRAY;
	/**
	 * @var bool
	 */
	protected $_is_array = true;

	/**
	 * @var array
	 */
	protected $default_value = array();

	/**
	 * @var string
	 */
	protected $item_type = null;

	/**
	 * @var string
	 */
	protected $form_field_type = "MultiSelect";

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data ) {
		if(!$definition_data) {
			return;
		}

		parent::setUp($definition_data);

		if( $this->is_ID ) {
			throw new DataModel_Exception(
				"Property {$this->_data_model_definition->getClassName()}::{$this->_name} is Array and Array can't be ID.",
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !$this->item_type ) {
			throw new DataModel_Exception(
				"Property {$this->_data_model_definition->getClassName()}::{$this->_name} is Array, but item_type is missing in definition data.",
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( $this->item_type==DataModel::TYPE_DATA_MODEL ) {
			throw new DataModel_Exception(
				"Property {$this->_data_model_definition->getClassName()}::{$this->_name} is Array and item_type='Jet\\DataModel::TYPE_DATA_MODEL'. Item type can not be 'Jet\\DataModel::TYPE_DATA_MODEL'! Please use Related1toN. Example: array(\"type\"=>Jet\\DataModel::TYPE_DATA_MODEL,\"data_model_class\"=> \"SomeRelatedDataModelClass\")",
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

	}

	/**
	 * @return string
	 */
	public function getItemType() {
		return $this->item_type;
	}

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
		if(!is_array($value)) {
			$value = array($value);
		}
	}
}