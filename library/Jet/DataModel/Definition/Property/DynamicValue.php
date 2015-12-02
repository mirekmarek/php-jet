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

class DataModel_Definition_Property_DynamicValue extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_DYNAMIC_VALUE;
	/**
	 * @var bool
	 */
	protected $_is_dynamic_value = true;

	/**
	 * @var string
	 */
	protected $getter_name = null;

	/**
	 * @var mixed
	 */
	protected $default_value = null;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data ) {

		if($definition_data) {
			parent::setUp($definition_data);

			if( !$this->getter_name ) {
				throw new DataModel_Exception(
					'Property '.$this->data_model_class_name.'::'.$this->_name.' is Dynamic Value, but getter_name is missing in definition data.',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}

	/**
	 *
	 * @return void
	 *
	 * @throws DataModel_Exception
	 */
	public function getDefaultValue() {
		throw new DataModel_Exception('You can not use getDefaultValue for the property that is DynamicValue (property: '.$this->_name.')');
	}

	/**
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function checkValueType( &$value ) {
		throw new DataModel_Exception('You can not use checkValueType for the property that is DynamicValue (property: '.$this->_name.')');
	}

	/**
	 * @return mixed
	 */
	public function getGetterName() {
		return $this->getter_name;
	}
}