<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var ?DataModel_Definition_Property
	 */
	protected ?DataModel_Definition_Property $property_definition = null;

	/**
	 *
	 * @var mixed
	 */
	protected mixed $value = null;

	/**
	 * @param DataModel_Definition_Property $property_definition
	 * @param mixed $value
	 */
	public function __construct( DataModel_Definition_Property $property_definition, mixed $value )
	{
		$this->property_definition = $property_definition;
		$this->value = $value;
	}

	/**
	 * @return DataModel_Definition_Property
	 */
	public function getPropertyDefinition(): DataModel_Definition_Property
	{
		return $this->property_definition;
	}

	/**
	 * @return mixed
	 */
	public function getValue(): mixed
	{
		return $this->value;
	}

}