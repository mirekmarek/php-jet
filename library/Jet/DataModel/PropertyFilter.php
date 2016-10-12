<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 */
namespace Jet;

class DataModel_PropertyFilter extends BaseObject {

	/**
	 * @var array
	 */
	protected $only_properties = [];

	/**
	 *
	 * @param DataModel_Definition_Model_Abstract $model_definition
	 * @param array $only_properties
	 */
	public function __construct( DataModel_Definition_Model_Abstract $model_definition, array $only_properties ) {

		foreach( $only_properties as $lp ) {
			$property_name = null;

			$model_name = $model_definition->getModelName();
			if(strpos($lp, '.')===false) {
				$property_name = $lp;
			} else {
				list($model_name, $property_name) = explode('.', $lp);

				if($model_name=='this') {
					$model_name = $model_definition->getModelName();
				}
			}

			if(!isset($this->only_properties[$model_name])) {
				$this->only_properties[$model_name] = [];
			}

			$this->only_properties[$model_name][] = $property_name;
		}
	}

	/**
	 * @param string $model_name
	 * @return bool
	 */
	public function getModelAllowed($model_name ) {
		return array_key_exists($model_name, $this->only_properties);
	}

	/**
	 * @param string $model_name
	 * @param string $property_name
	 *
	 * @return bool
	 */
	public function getPropertyAllowed($model_name, $property_name ) {
		if(!array_key_exists($model_name, $this->only_properties)) {
			return false;
		}

		return in_array($property_name, $this->only_properties[$model_name]);
	}

	/**
	 * @param $model_name
	 *
	 * @return array
	 */
	public function getPropertyNames($model_name ) {
		if(!array_key_exists($model_name, $this->only_properties)) {
			return [];
		}

		return $this->only_properties[$model_name];
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $model_definition
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array|DataModel_Definition_Property_Abstract[]
	 */
	public static function getQuerySelect(DataModel_Definition_Model_Abstract $model_definition, DataModel_PropertyFilter $load_filter=null ) {

		if(!$load_filter) {
			$select = $model_definition->getProperties();
		} else {
			$select = [];

			foreach( $model_definition->getProperties() as $property ) {
				if(
					!$property->getIsID() &&
					!$load_filter->getPropertyAllowed(
						$model_definition->getModelName(),
						$property->getName()
					)
				) {
					continue;
				}

				$select[] = $property;
			}
		}

		return $select;
	}

}