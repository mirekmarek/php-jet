<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Query_Having_Expression extends DataModel_Query_Where_Expression
{


	/**
	 *
	 * @param DataModel_Query_Select_Item $property
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query_Select_Item $property, string $operator, mixed $value )
	{

		$this->property = $property;
		$this->value = $value;
		$this->_setOperator( $operator );
	}

	/**
	 *
	 * @return string
	 */
	public function toString(): string
	{
		if( $this->property->getItem() instanceof DataModel_Query_Select_Item_Expression ) {
			return $this->property->getItem()->toString() . ' ' . $this->operator . ' \'' . $this->value . '\'';
		} else {
			return $this->property->getItem()->getDataModelDefinition()->getModelName() . '::' . $this->property->getItem()->getName() . ' ' . $this->operator . ' \'' . $this->value . '\'';
		}
	}

}