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

trait DataModel_Trait_History {

    /**
     *
     * @return bool
     */
    public static function getHistoryEnabled() {
        return static::getDataModelDefinition()->getHistoryEnabled();
    }

    /**
     * Returns history backend instance
     *
     * @return DataModel_History_Backend_Abstract
     */
    public function getHistoryBackendInstance() {
        /**
         * @var DataModel $this
         */
        $definition = $this->getDataModelDefinition();

        if(!$definition->getHistoryEnabled()) {
            return false;
        }

        $history_backend_instance = &DataModel_ObjectState::getVar($this, 'history_backend_instance' );

        if(!$history_backend_instance) {
            $history_backend_instance = DataModel_Factory::getHistoryBackendInstance(
                $definition->getHistoryBackendType(),
                $definition->getHistoryBackendConfig()
            );

        }

        return $history_backend_instance;
    }

    /**
     * @param string $operation
     */
    protected function dataModelHistoryOperationStart( $operation ) {
        $backend = $this->getHistoryBackendInstance();

        if( !$backend ) {
            return;
        }

        /**
         * @var DataModel $this
         */
        $backend->operationStart( $this, $operation );
    }

    /**
     *
     */
    protected function dataModelHistoryOperationDone() {
        $backend = $this->getHistoryBackendInstance();

        if( !$backend ) {
            return;
        }
        $backend->operationDone();
    }
}