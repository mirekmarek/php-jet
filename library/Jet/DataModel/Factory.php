<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Factory
 */
namespace Jet;

class DataModel_Factory {

	/**
	 * Returns instance of Property class
	 *
	 * @param string $data_model_class_name
	 * @param string $name
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property_Abstract
	 */
	public static function getPropertyDefinitionInstance( $data_model_class_name, $name, $definition_data ) {
		if(
			!isset($definition_data['type']) ||
			!$definition_data['type']
		) {
			throw new DataModel_Exception(
				'Property '.$data_model_class_name.'::'.$name.': \'type\' parameter is not defined ... ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$class_name = JET_DATA_MODEL_PROPERTY_DEFINITION_CLASS_NAME_PREFIX.$definition_data['type'];

		return new $class_name( $data_model_class_name, $name, $definition_data );
	}


	/**
	 * Returns instance of DataModel Backend Config class
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 *
	 * @return DataModel_Backend_Config_Abstract
	 */
	public static function getBackendConfigInstance( $type, $soft_mode=false ) {
		$class_name = JET_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX.$type.'_Config';

		return new $class_name($soft_mode);
	}

	/**
	 * Returns instance of DataModel Backend class
	 *
	 * @param string $type
	 * @param DataModel_Backend_Config_Abstract $backend_config
	 *
	 * @return DataModel_Backend_Abstract
	 */
	public static function getBackendInstance( $type, DataModel_Backend_Config_Abstract $backend_config ) {
		$class_name = JET_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX.$type;

		return new $class_name( $backend_config );
	}


}