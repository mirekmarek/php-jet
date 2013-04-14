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
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_Query_Relation_Inner extends DataModel_Query_Relation_Abstract {

	/**
	 * @var DataModel_Definition_Property_Abstract[]
	 */
	protected $join_by_properties = array();


	/**
	 * @param DataModel_Definition_Model_Abstract $related_data_model_definition
	 * @param DataModel_Definition_Property_Abstract[] $join_by_properties
	 * @param string $join_type
	 */
	public function  __construct(
				DataModel_Definition_Model_Abstract $related_data_model_definition,
				array $join_by_properties,
				$join_type=DataModel_Query::JOIN_TYPE_LEFT_JOIN
			) {
		$this->name = $related_data_model_definition->getModelName();
		$this->related_data_model_class_name = $related_data_model_definition->getClassName();
		$this->related_data_model_definition = $related_data_model_definition;
		$this->join_by_properties = $join_by_properties;
		$this->join_type = $join_type;
	}

	/**
	 * @param DataModel_Definition_Property_Abstract[] $join_by_properties
	 */
	public function setJoinByProperties(array $join_by_properties) {
		$this->join_by_properties = $join_by_properties;
	}

	/**
	 * @return DataModel_Definition_Property_Abstract[]
	 */
	public function getJoinByProperties() {
		return $this->join_by_properties;
	}
}