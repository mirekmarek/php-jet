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

class DataModel_Definition_Model_Main extends DataModel_Definition_Model_Abstract {

    /**
     * @param string $data_model_class_name
     *
     * @return array
     * @throws DataModel_Exception
     */
    protected function _mainInit( $data_model_class_name ) {

        parent::_mainInit($data_model_class_name);

        $this->_initBackendsConfig();
   }

}