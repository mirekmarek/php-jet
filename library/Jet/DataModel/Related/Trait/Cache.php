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

trait DataModel_Related_Trait_Cache {

    /**
     * @param string $operation
     */
    public function updateDataModelCache( $operation ) {
        /**
         * @var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        if(!$this_main_model_instance) {
            return;
        }
        $this_main_model_instance->updateDataModelCache($operation);
    }

    /**
     *
     */
    public function deleteDataModelCache() {
        /**
         * @var DataModel $this_main_model_instance
         */
        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');
        if(!$this_main_model_instance) {
            return;
        }
        $this_main_model_instance->deleteDataModelCache();
    }

}
