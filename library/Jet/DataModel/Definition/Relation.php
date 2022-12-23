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
abstract class DataModel_Definition_Relation extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $this_data_model_class_name;

	/**
	 * @var string
	 */
	protected string $related_data_model_class_name;


	/**
	 * @var DataModel_Definition_Relation_Join_Item[]|DataModel_Definition_Relation_Join_Condition[]
	 */
	protected array $join_by = [];

	/**
	 * @var string
	 */
	protected string $join_type = DataModel_Query::JOIN_TYPE_LEFT_JOIN;

	/**
	 * @var array
	 */
	protected array $required_relations = [];


	/**
	 * @param string $this_to_class_name
	 */
	public function setThisToClass( string $this_to_class_name ): void
	{
		$this->this_data_model_class_name = $this_to_class_name;
	}

	/**
	 * @return string
	 */
	public function getThisDataModelClassName(): string
	{
		return $this->this_data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getThisDataModelName(): string
	{
		return $this->getThisDataModelDefinition()->getModelName();
	}

	/**
	 * @return DataModel_Definition_Model
	 */
	public function getThisDataModelDefinition(): DataModel_Definition_Model
	{
		return DataModel::getDataModelDefinition( $this->this_data_model_class_name );
	}


	/**
	 * @param string $related_to_class_name
	 */
	public function setRelatedToClass( string $related_to_class_name )
	{
		$this->related_data_model_class_name = $related_to_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedDataModelClassName(): string
	{
		return $this->related_data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedDataModelName(): string
	{
		return $this->getRelatedDataModelDefinition()->getModelName();
	}

	/**
	 * @return DataModel_Definition_Model
	 */
	public function getRelatedDataModelDefinition(): DataModel_Definition_Model
	{
		return DataModel::getDataModelDefinition( $this->related_data_model_class_name );
	}

	/**
	 * @return string
	 */
	public function getJoinType(): string
	{
		return $this->join_type;
	}

	/**
	 * @param string $join_type
	 */
	public function setJoinType( string $join_type ): void
	{
		$this->join_type = $join_type;
	}

	/**
	 * @return DataModel_Definition_Relation_Join_Condition[]|DataModel_Definition_Relation_Join_Item[]
	 */
	public function getJoinBy() : array
	{
		return $this->join_by;
	}

	/**
	 * @param array $items
	 */
	public function setJoinBy( array $items ): void
	{
		$this->join_by = [];

		foreach( $items as $this_property_name => $related_property_name ) {
			$join_item = new DataModel_Definition_Relation_Join_Item( $this, $this_property_name, $related_property_name );

			$this->join_by[] = $join_item;
		}
	}

	/**
	 * @param DataModel_Definition_Relation_Join_Item $item
	 */
	public function addJoinBy( DataModel_Definition_Relation_Join_Item $item ): void
	{
		$this->join_by[] = $item;
	}

	/**
	 * @param DataModel_Definition_Relation_Join_Condition $condition
	 */
	public function addJoinCondition( DataModel_Definition_Relation_Join_Condition $condition ): void
	{
		$this->join_by[] = $condition;

	}

	/**
	 * @return array
	 */
	public function getRequiredRelations(): array
	{
		return $this->required_relations;
	}

	/**
	 * @param array $required_relations
	 */
	public function setRequiredRelations( array $required_relations ): void
	{
		$this->required_relations = $required_relations;
	}

}