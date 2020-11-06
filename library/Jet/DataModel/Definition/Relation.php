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
abstract class DataModel_Definition_Relation extends BaseObject
{

	/**
	 * @var string
	 */
	protected $this_data_model_class_name;
	
	/**
	 * @var string
	 */
	protected $related_data_model_class_name;


	/**
	 * @var DataModel_Definition_Relation_Join_Item[]|DataModel_Definition_Relation_Join_Condition[]
	 */
	protected $join_by = [];

	/**
	 * @var string
	 */
	protected $join_type = DataModel_Query::JOIN_TYPE_LEFT_JOIN;

	/**
	 * @var array
	 */
	protected $required_relations = [];



	/**
	 * @param string $this_to_class_name
	 */
	public function setThisToClass( $this_to_class_name )
	{
		$this->this_data_model_class_name = $this_to_class_name;
	}

	/**
	 * @return string
	 */
	public function getThisDataModelClassName()
	{
		return $this->this_data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getThisDataModelName()
	{
		return $this->getThisDataModelDefinition()->getModelName();
	}

	/**
	 * @return DataModel_Definition_Model
	 */
	public function getThisDataModelDefinition()
	{
		return DataModel::getDataModelDefinition( $this->this_data_model_class_name );
	}


	/**
	 * @param string $related_to_class_name
	 */
	public function setRelatedToClass( $related_to_class_name )
	{
		$this->related_data_model_class_name = $related_to_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedDataModelClassName()
	{
		return $this->related_data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedDataModelName()
	{
		return $this->getRelatedDataModelDefinition()->getModelName();
	}

	/**
	 * @return DataModel_Definition_Model
	 */
	public function getRelatedDataModelDefinition()
	{
		return DataModel::getDataModelDefinition( $this->related_data_model_class_name );
	}

	/**
	 * @return string
	 */
	public function getJoinType()
	{
		return $this->join_type;
	}

	/**
	 * @param string $join_type
	 */
	public function setJoinType( $join_type )
	{
		$this->join_type = $join_type;
	}

	/**
	 * @return DataModel_Definition_Relation_Join_Item[]|DataModel_Definition_Relation_Join_Condition[]
	 */
	public function getJoinBy()
	{
		return $this->join_by;
	}

	/**
	 * @param array $items
	 */
	public function setJoinBy( array $items )
	{
		$this->join_by = [];

		foreach( $items as $this_property_name=>$related_property_name ) {
			$join_item = new DataModel_Definition_Relation_Join_Item( $this, $this_property_name, $related_property_name );

			$this->join_by[] = $join_item;
		}
	}

	/**
	 * @param DataModel_Definition_Relation_Join_Item $item
	 */
	public function addJoinBy( DataModel_Definition_Relation_Join_Item $item )
	{
		$this->join_by[] = $item;
	}

	/**
	 * @param DataModel_Definition_Relation_Join_Condition $condition
	 */
	public function addJoinCondition( DataModel_Definition_Relation_Join_Condition $condition )
	{
		$this->join_by[] = $condition;

	}

	/**
	 * @return array
	 */
	public function getRequiredRelations()
	{
		return $this->required_relations;
	}

	/**
	 * @param array $required_relations
	 */
	public function setRequiredRelations( array $required_relations )
	{
		$this->required_relations = $required_relations;
	}

}