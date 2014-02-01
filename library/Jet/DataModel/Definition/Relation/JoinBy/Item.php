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

class DataModel_Definition_Relation_JoinBy_Item extends Object {


	/**
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $related_property;

	/**
	 * @var string|DataModel_Definition_Property_Abstract
	 */
	protected $this_model_property;

	/**
	 * @param DataModel_Definition_Property_Abstract $related_property
	 * @param DataModel_Definition_Property_Abstract|string $this_model_property
	 *
	 */
	public function __construct( DataModel_Definition_Property_Abstract $related_property, $this_model_property ) {
		$this->related_property = $related_property;
		$this->this_model_property = $this_model_property;
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->this_model_property.'<->'.$this->related_property;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getRelatedProperty() {
		return $this->related_property;
	}

	/**
	 * @param DataModel $model_instance
	 *
	 * @throws DataModel_Query_Exception
	 * @return string
	 */
	public function getThisModelPropertyValue( DataModel $model_instance ) {
		if( $this->this_model_property instanceof DataModel_Definition_Property_Abstract ) {
			return $this->this_model_property;
		}

		$properties = $model_instance->getDataModelDefinition()->getProperties();

		if(strpos($this->this_model_property, '.')===false) {
			$item = $this->this_model_property;
			$prefix = 'this';
		} else {
			list($prefix, $item) = explode('.', $this->this_model_property);
		}


		switch( $prefix ) {
			case 'this':
				if(!isset($properties[$item])) {
					throw new DataModel_Query_Exception(
						'Unknown property: '.get_class($model_instance).'::'.$item,
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
					'Invalid property name: \''.$this->this_model_property.'\'. Valid examples: this.property_name, this_value.getterMethodName ',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			break;
		}

	}

}