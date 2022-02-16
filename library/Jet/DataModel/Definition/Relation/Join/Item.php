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
class DataModel_Definition_Relation_Join_Item extends BaseObject
{

	/**
	 * @var ?DataModel_Definition_Relation
	 */
	protected ?DataModel_Definition_Relation $relation = null;

	/**
	 * @var string
	 */
	protected string $this_property_name = '';


	/**
	 * @var string
	 */
	protected string $related_property_name = '';

	/**
	 *
	 * @param DataModel_Definition_Relation $relation
	 * @param string $this_property_name
	 * @param string $related_to_property_name
	 */
	public function __construct(
		DataModel_Definition_Relation $relation,
		string $this_property_name,
		string $related_to_property_name
	)
	{

		$this->relation = $relation;

		$this->related_property_name = $related_to_property_name;
		$this->this_property_name = $this_property_name;


	}

	/**
	 * @return string
	 */
	public function getThisClassName(): string
	{
		return $this->relation->getThisDataModelClassName();
	}

	/**
	 * @return string
	 */
	public function getThisPropertyName(): string
	{
		return $this->this_property_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedClassName(): string
	{
		return $this->relation->getRelatedDataModelClassName();
	}

	/**
	 * @return string
	 */
	public function getRelatedPropertyName(): string
	{
		return $this->related_property_name;
	}


	/**
	 *
	 *
	 * @return DataModel_Definition_Property
	 */
	public function getThisProperty(): DataModel_Definition_Property
	{
		return $this->relation->getThisDataModelDefinition()->getProperty( $this->this_property_name );
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
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->getThisClassName() . '.' . $this->this_property_name . '<->' . $this->getRelatedClassName() . '.' . $this->related_property_name;
	}

}