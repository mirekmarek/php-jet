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
	 * @var mixed|DataModel_Definition_Property_Abstract
	 */
	protected $this_model_property_or_value;

	/**
	 * @param DataModel_Definition_Property_Abstract $related_property
	 * @param DataModel_Definition_Property_Abstract|string $this_model_property_definition
	 * @param DataModel_Definition_Model_Abstract $this_model_definition
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Definition_Property_Abstract $related_property, $this_model_property_definition, DataModel_Definition_Model_Abstract $this_model_definition ) {
		$this->related_property = $related_property;
		$this->this_model_property_or_value = $this_model_property_definition;

		if( !($this->this_model_property_or_value instanceof DataModel_Definition_Property_Abstract) ) {
			$properties = $this_model_definition->getProperties();

			if(strpos($this->this_model_property_or_value, '.')===false) {
				$item = $this->this_model_property_or_value;
				$prefix = 'this';
			} else {
				list($prefix, $item) = explode('.', $this->this_model_property_or_value);
			}


			switch( $prefix ) {
				case 'this':
					if(!isset($properties[$item])) {
						throw new DataModel_Query_Exception(
							'Unknown property: '.$this_model_definition->getClassName().'::'.$item,
							DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
						);
					}
					$this->this_model_property_or_value = $properties[$item];
					break;
				case 'this_value':
					$class = $this_model_definition->getClassName();
					$this->this_model_property_or_value = $class::$item();
					break;
				default:
					throw new DataModel_Query_Exception(
						'Invalid property name: \''.$this->this_model_property_or_value.'\'. Valid examples: this.property_name, this_value.getterMethodName ',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
					break;
			}
		}


	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->this_model_property_or_value.'<->'.$this->related_property;
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
	 *
	 * @throws DataModel_Query_Exception
	 *
	 * @return mixed|DataModel_Definition_Property_Abstract
	 */
	public function getThisModelPropertyOrValue() {
		return $this->this_model_property_or_value;
	}

}