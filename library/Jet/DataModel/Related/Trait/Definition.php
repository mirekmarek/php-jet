<?php
/**
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
 * @subpackage DataModel_Related
 */
namespace Jet;

trait DataModel_Related_Trait_Definition {

    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_Abstract
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
        return new DataModel_Definition_Model_Related_Abstract( $data_model_class_name );
    }

}
