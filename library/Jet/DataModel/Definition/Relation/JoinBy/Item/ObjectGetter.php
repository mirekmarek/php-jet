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

class DataModel_Definition_Relation_JoinBy_Item_ObjectGetter extends BaseObject {

	/**
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $model_definition;

	/**
	 * @var string
	 */
	protected $getter_method_name = '';

	/**
	 * @param DataModel_Definition_Model_Abstract $model_definition
	 * @param string $getter_method_name
	 */
	function __construct( DataModel_Definition_Model_Abstract $model_definition, $getter_method_name) {
		$this->model_definition = $model_definition;
		$this->getter_method_name = $getter_method_name;
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->model_definition->getClassName().'::'.$this->getter_method_name;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getModelDefinition() {
		return $this->model_definition;
	}

	/**
	 * @return string
	 */
	public function getGetterMethodName() {
		return $this->getter_method_name;
	}



}