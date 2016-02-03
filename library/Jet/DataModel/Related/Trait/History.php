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
    protected function dataModelHistoryOperationStart( $operation ) {
        /**
         * @var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        if(!$this_main_model_instance) {
            return;
        }
        $this_main_model_instance->dataModelHistoryOperationStart( $operation );
    }

    /**
     *
     */
    protected function dataModelHistoryOperationDone() {
        /**
         * @var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        if(!$this_main_model_instance) {
            return;
        }
        $this_main_model_instance->dataModelHistoryOperationDone();
    }



}
