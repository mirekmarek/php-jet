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

trait DataModel_Related_Trait_History {

    /**
     * @param string $operation
     */
    public function dataModelHistoryOperationStart( $operation ) {
        if(!$this->_main_model_instance) {
            return;
        }
	    /** @noinspection PhpUndefinedMethodInspection */
	    $this->_main_model_instance->dataModelHistoryOperationStart( $operation );
    }

    /**
     *
     */
    public function dataModelHistoryOperationDone() {
        if(!$this->_main_model_instance) {
            return;
        }
	    /** @noinspection PhpUndefinedMethodInspection */
        $this->_main_model_instance->dataModelHistoryOperationDone();
    }



}
