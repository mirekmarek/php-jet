<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_RecordData_Item
{
	/**
	 *
	 * @var DataModel_Definition_Property
	 */
	protected $property_definition = null;

	/**
	 *
	 * @var mixed
	 */
	protected $value = null;

	/**
	 * @param DataModel_Definition_Property $property_definition
	 * @param mixed                         $value
	 */
	public function __construct( DataModel_Definition_Property $property_definition, $value )
	{
		$this->property_definition = $property_definition;
		$this->value = $value;
	}

	/**
	 * @return DataModel_Definition_Property
	 */
	public function getPropertyDefinition()
	{
		return $this->property_definition;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}