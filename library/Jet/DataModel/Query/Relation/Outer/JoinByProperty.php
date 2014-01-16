<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
	 * @throws DataModel_Query_Exception
	 * @return string
	 */
	public function getThisModelPropertyValue( DataModel $model_instance ) {


		if(!strpos($this->this_model_property_name, '.')) {
			throw new DataModel_Query_Exception(
				'Invalid property name: \''.$this->this_model_property_name.'\'. Valid examples: this.property_name, this_value.getterMethodName ',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);
		}

		list($prefix, $item) = explode('.', $this->this_model_property_name);

		switch( $prefix ) {
			case 'this':
				$properties = $model_instance->getDataModelDefinition()->getProperties();
				if(!isset($properties[$item])) {
					throw new DataModel_Query_Exception(
						'Unknown property: '.get_class($model_instance).'::$item ',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
				}
				return $properties[$item];
			break;
			case 'this_value':
				return $model_instance->$item();
			break;
			default:
				throw new DataModel_Query_Exception(
					'Invalid property name: \''.$this->this_model_property_name.'\'. Valid examples: this.property_name, this_value.getterMethodName ',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			break;
		}

	}

}