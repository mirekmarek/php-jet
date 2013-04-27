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
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Query_Relation_Outer_JoinByProperty extends Object {

	/**
	 * @var DataModel_Query_Relation_Outer
	 */
	protected $relation_definition;

	/**
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $related_property;

	/**
	 * @var string
	 */
	protected $this_model_property_name;

	public function __construct( DataModel_Query_Relation_Outer $relation_definition, DataModel_Definition_Property_Abstract $related_property, $this_model_property_name ) {
		$this->relation_definition = $relation_definition;
		$this->related_property = $related_property;
		$this->this_model_property_name = $this_model_property_name;
	}

	/**
	 * @return DataModel_Query_Relation_Outer
	 */
	public function getRelationDefinition() {
		return $this->relation_definition;
	}
	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getRelatedProperty() {
		return $this->related_property;
	}

	/**
	 * @return string
	 */
	public function getThisModelPropertyName() {
		return $this->this_model_property_name;
	}

	/**
	 * @param DataModel $model_instance
	 *
	 * @return string
	 */
	public function getThisModelPropertyValue( DataModel $model_instance ) {


		if(!strpos($this->this_model_property_name, ".")) {
			//TODO: scream!
		}

		list($prefix, $item) = explode(".", $this->this_model_property_name);

		switch( $prefix ) {
			case "this":
				$properties = $model_instance->getDataModelDefinition()->getProperties();
				if(!isset($properties[$item])) {
					//TODO: scream!
				}
				return $properties[$item];
			break;
			case "this_value":
				return $model_instance->$item();
			break;
			default:
				//TODO: scream
			break;
		}

	}

}