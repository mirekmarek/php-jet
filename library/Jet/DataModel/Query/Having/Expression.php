<?php
/**
 *
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
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_Query_Having_Expression extends DataModel_Query_Where_Expression {

	/**
	 *
	 * @var DataModel_Query_Select_Item
	 */
	protected $property;


	/** @noinspection PhpMissingParentConstructorInspection
	 *
	 * @param DataModel_Query_Select_Item $property
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function  __construct( DataModel_Query_Select_Item $property, $operator, $value  ) {

		$this->property = $property;
		$this->value = $value;
		$this->_setOperator($operator);
	}

	/**
	 * @return DataModel_Query_Select_Item
	 */
	public function getProperty() {
		return $this->property;
	}

	/**
	 *
	 * @return string
	 */
	public function toString() {
		if($this->property->getItem() instanceof DataModel_Query_Select_Item_BackendFunctionCall) {
			return $this->property->getItem()->toString().' '.$this->operator.' \''.$this->value.'\'';
		} else {
			return $this->property->getItem()->getDataModelDefinition()->getModelName().'::'.$this->property->getItem()->getName().' '.$this->operator.' \''.$this->value.'\'';
		}
	}

}