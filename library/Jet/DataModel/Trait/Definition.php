<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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

trait DataModel_Trait_Definition {
    /**
     * Returns model definition
     *
     * @param string $class_name (optional)
     *
     * @return DataModel_Definition_Model_Abstract
     */
    public static function getDataModelDefinition( $class_name='' )  {
        if(!$class_name) {
            $class_name = get_called_class();
        }

        return DataModel_Definition_Model_Abstract::getDataModelDefinition( $class_name );
    }


    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Main
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
        return new DataModel_Definition_Model_Main( $data_model_class_name );
    }

    /**
     * @param array $reflection_data
     * @param string $class_name
     * @param string $key
     * @param string $definition
     * @param mixed $value
     *
     * @throws BaseObject_Reflection_Exception
     */
    public static function parseClassDocComment(&$reflection_data, $class_name, $key, $definition, $value) {
        DataModel_Definition_Model_Abstract::parseClassDocComment($reflection_data, $class_name, $key, $definition, $value);
    }

    /**
     * @param array &$reflection_data
     * @param string $class_name
     * @param string $property_name
     * @param string $key
     * @param string $definition
     * @param mixed $value
     *
     */
    public static function parsePropertyDocComment(&$reflection_data, $class_name, $property_name, $key, $definition, $value) {
        DataModel_Definition_Model_Abstract::parsePropertyDocComment($reflection_data, $class_name, $property_name, $key, $definition, $value);
    }

}