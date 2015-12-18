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
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Array extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_ARRAY;

	/**
	 * @var array
	 */
	protected $default_value = [];

	/**
	 * @var string
	 */
	protected $item_type = null;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_MULTI_SELECT;

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
				'Property '.$this->data_model_class_name.'::'.$this->_name.' is Array and Array can\'t be ID.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( !$this->item_type ) {
			throw new DataModel_Exception(
				'Property '.$this->data_model_class_name.'::'.$this->_name.' is Array, but item_type is missing in definition data.',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		if( $this->item_type==DataModel::TYPE_DATA_MODEL ) {
			throw new DataModel_Exception(
				'Property '.$this->data_model_class_name.'::'.$this->_name.' is Array and item_type=\'DataModel::TYPE_DATA_MODEL\'. Item type can not be \'DataModel::TYPE_DATA_MODEL\'! Please use Related1toN. Example: @JetDataModel:type = DataModel::TYPE_DATA_MODEL @JetDataModel:data_model_class = \'SomeRelatedDataModelClass\' ',
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
     * @return bool
     */
    public function getMustBeSerializedBeforeStore() {
        return true;
    }


	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
		if(!is_array($value)) {
			$value = [$value];
		}
	}
}