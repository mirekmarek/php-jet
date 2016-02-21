<?php
/**
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
 * @subpackage DataModel_Related
 */
namespace Jet;

interface DataModel_Related_1to1_Interface extends DataModel_Related_Interface, DataModel_Interface {

    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_1to1
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name );


}