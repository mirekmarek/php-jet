<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class DataModel_Definition_Relation_Join_Condition extends BaseObject
{

	/**
	 * @var DataModel_Definition_Relation
	 */
	protected $relation;

	/**
	 * @var string
	 */
	protected $related_property_name = '';

	/**
	 * @var string
	 */
	protected $operator;

	/**
	 * @var string
	 */
	protected $value;


	/**
	 *
	 *
	 * @param DataModel_Definition_Relation $relation
	 * @param string $related_to_property_name
	 * @param string $operator
	 * @param mixed  $value
	 *
	 */
	public function __construct(
				DataModel_Definition_Relation $relation,
				$related_to_property_name,
				$operator,
				$value
	) {


		$this->relation = $relation;
		$this->related_property_name = $related_to_property_name;

		$this->operator = $operator;
		$this->value = $value;


	}


	/**
	 * @return DataModel_Definition_Property
	 */
	public function getRelatedProperty()
	{
		return $this->relation->getRelatedDataModelDefinition()->getProperty( $this->related_property_name );
	}

	/**
	 * @return string
	 */
	public function getOperator()
	{
		return $this->operator;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->relation->getRelatedDataModelClassName().'.'.$this->related_property_name.' '.$this->operator.' '.$this->value;
	}

}