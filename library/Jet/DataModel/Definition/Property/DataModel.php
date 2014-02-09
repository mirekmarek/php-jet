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

class DataModel_Definition_Property_DataModel extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_DATA_MODEL;
	/**
	 * @var bool
	 */
	protected $_is_data_model = true;

	/**
	 * @var string
	 */
	protected $data_model_class = null;

	/**
	 * @var DataModel
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

			if( !$this->data_model_class ) {
				throw new DataModel_Exception(
					'Property '.$this->data_model_class_name.'::'.$this->_name.' is DataModel, but data_model_class is missing in definition data.',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}

	/**
	 * @param DataModel $data_model
	 *
	 * @return mixed
	 */
	public function getDefaultValue( DataModel $data_model ) {

		$class_name =  $this->getDataModelClass();

		$default_value = new $class_name();

		if($default_value instanceof DataModel_Related_MtoN) {
			$default_value->setMRelatedModel( $data_model );
		}


		return $default_value;
	}

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
	}

	/**
	 *
	 * @return string
	 */
	public function getDataModelClass() {
		return Factory::getClassName($this->data_model_class);
	}
}