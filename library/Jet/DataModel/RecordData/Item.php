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
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_RecordData_Item {
	/**
	 *
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $property_definition = null;

	/**
	 *
	 * @var mixed
	 */
	protected  $value = null;

	/**
	 * @param DataModel_Definition_Property_Abstract $property_definition
	 * @param mixed $value
	 */
	public function  __construct( DataModel_Definition_Property_Abstract $property_definition, $value  ) {
		$this->property_definition = $property_definition;
		$this->value = $value;
	}

	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getPropertyDefinition() {
		return $this->property_definition;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
	
}