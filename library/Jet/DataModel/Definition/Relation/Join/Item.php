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
class DataModel_Definition_Relation_Join_Item extends BaseObject
{

	/**
	 * @var DataModel_Definition_Relation
	 */
	protected $relation;

	/**
	 * @var string
	 */
	protected $this_property_name = '';


	/**
	 * @var string
	 */
	protected $related_property_name = '';

	/**
	 *
	 * @param DataModel_Definition_Relation $relation
	 * @param string $this_property_name
	 * @param string $related_to_property_name
	 */
	public function __construct(
				DataModel_Definition_Relation $relation,
				$this_property_name,
				$related_to_property_name
	) {

		$this->relation = $relation;

		$this->related_property_name = $related_to_property_name;
		$this->this_property_name = $this_property_name;


	}

	/**
	 * @return string
	 */
	public function getThisClassName()
	{
		return $this->relation->getThisDataModelClassName();
	}

	/**
	 * @return string
	 */
	public function getThisPropertyName()
	{
		return $this->this_property_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedClassName()
	{
		return $this->relation->getRelatedDataModelClassName();
	}

	/**
	 * @return string
	 */
	public function getRelatedPropertyName()
	{
		return $this->related_property_name;
	}



	/**
	 *
	 *
	 * @return DataModel_Definition_Property
	 */
	public function getThisProperty()
	{
		return $this->relation->getThisDataModelDefinition()->getProperty( $this->this_property_name );
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
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->getThisClassName().'.'.$this->this_property_name.'<->'.$this->getRelatedClassName().'.'.$this->related_property_name;
	}

}