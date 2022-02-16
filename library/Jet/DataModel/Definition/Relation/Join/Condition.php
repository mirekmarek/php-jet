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
class DataModel_Definition_Relation_Join_Condition extends BaseObject
{

	/**
	 * @var ?DataModel_Definition_Relation
	 */
	protected ?DataModel_Definition_Relation $relation = null;

	/**
	 * @var string
	 */
	protected string $related_property_name = '';

	/**
	 * @var string
	 */
	protected string $operator = '';

	/**
	 * @var mixed
	 */
	protected mixed $value = '';


	/**
	 *
	 *
	 * @param DataModel_Definition_Relation $relation
	 * @param string $related_to_property_name
	 * @param string $operator
	 * @param mixed $value
	 *
	 */
	public function __construct(
		DataModel_Definition_Relation $relation,
		string $related_to_property_name,
		string $operator,
		mixed $value
	)
	{


		$this->relation = $relation;
		$this->related_property_name = $related_to_property_name;

		$this->operator = $operator;
		$this->value = $value;


	}


	/**
	 * @return DataModel_Definition_Property
	 */
	public function getRelatedProperty(): DataModel_Definition_Property
	{
		return $this->relation->getRelatedDataModelDefinition()->getProperty( $this->related_property_name );
	}

	/**
	 * @return string
	 */
	public function getOperator(): string
	{
		return $this->operator;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->relation->getRelatedDataModelClassName() . '.' . $this->related_property_name . ' ' . $this->operator . ' ' . $this->value;
	}

}