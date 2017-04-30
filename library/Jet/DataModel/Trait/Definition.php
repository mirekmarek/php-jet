<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Trait_Definition
 * @package Jet
 */
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
	 * @param BaseObject_Reflection_ParserData $data
	 * @throws BaseObject_Reflection_Exception
	 */
	public static function parseClassDocComment( BaseObject_Reflection_ParserData $data ) {
        DataModel_Definition_Model_Abstract::parseClassDocComment($data);
    }

	/**
	 * @param BaseObject_Reflection_ParserData $data
	 */
	public static function parsePropertyDocComment( BaseObject_Reflection_ParserData $data ) {
        DataModel_Definition_Model_Abstract::parsePropertyDocComment( $data );
    }

}