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

trait DataModel_Trait_Save {
    /**
     *
     * @throws Exception
     * @throws DataModel_Exception
     */
    public function save() {
        /**
         * @var DataModel $this
         */

        $backend = $this->getBackendInstance();

        $this->startBackendTransaction( $backend );


        if( $this->getIsNew() ) {
            $after_method_name = 'afterAdd';
            $operation = 'save';
            $h_operation = DataModel_History_Backend_Abstract::OPERATION_SAVE;
        } else {
            $after_method_name = 'afterUpdate';
            $operation = 'update';
            $h_operation = DataModel_History_Backend_Abstract::OPERATION_UPDATE;
        }

        if($this->getBackendTransactionStartedByThisInstance()) {
            $this->dataModelHistoryOperationStart( $h_operation );
        }


        try {
            $this->{'_'.$operation}( $backend );
        } catch (Exception $e) {
            $this->rollbackBackendTransaction($backend);

            throw $e;
        }

        if($this->getBackendTransactionStartedByThisInstance()) {
            $this->updateDataModelCache( $operation );
            $this->commitBackendTransaction( $backend );

            $this->dataModelHistoryOperationDone();
        }


        $this->setIsSaved();

        $this->{$after_method_name}();
    }


    /**
     *
     * @param DataModel_Backend_Abstract $backend
     */
    protected function _save( DataModel_Backend_Abstract $backend ) {
        /**
         * @var DataModel $this
         */
        $definition = $this->getDataModelDefinition();

        $record = new DataModel_RecordData( $definition );

        $this->generateID();
        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            if( !$property_definition->getCanBeInInsertRecord() ) {
                continue;
            }

            $record->addItem($property_definition, $this->{$property_name});
        }


        $backend_result = $backend->save( $record );

        $this->generateID( true, $backend_result );

        /**
         * @var DataModel_Trait_Save $this
         */
        $this->_saveRelatedObjects();

    }

    /**
     *
     * @param DataModel_Backend_Abstract $backend
     */
    protected function _update( DataModel_Backend_Abstract $backend ) {
        /**
         * @var DataModel $this
         */
        $definition = $this->getDataModelDefinition();

        $record = new DataModel_RecordData( $definition );


        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            if( !$property_definition->getCanBeInUpdateRecord() ) {
                continue;
            }

            $record->addItem($property_definition, $this->{$property_name});
        }

        if(!$record->getIsEmpty()) {
            $backend->update($record, $this->getID()->getQuery() );
        }

        /**
         * @var DataModel_Trait_Save $this
         */
        $this->_saveRelatedObjects();

    }


    /**
     *
     */
    protected function _saveRelatedObjects() {
        /**
         * @var DataModel $this
         */
        $definition = $this->getDataModelDefinition();

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Related_Interface $prop
             */
            $prop = $this->{$property_name};
            if(!($prop instanceof DataModel_Related_Interface)) {
                continue;
            }

            $prop->setupParentObjects( $this );
            $prop->save();
        }
    }


    /**
     * @param array $data
     * @param array $where
     */
    public function updateData( array $data, array $where ) {
        /**
         * @var DataModel $this
         */
        $cache_enabled = $this->getCacheEnabled();

        $affected_IDs = null;
        if($cache_enabled) {
            $affected_IDs = $this->fetchObjectIDs($where);
        }

        $this->getBackendInstance()->update(
            DataModel_RecordData::createRecordData( $this,
                $data
            ),
            DataModel_Query::createQuery( $this->getDataModelDefinition(),
                $where
            )
        );

        /**
         * @var DataModel_ID_Abstract[] $affected_IDs
         */
        if(count($affected_IDs)) {
            $this->deleteDataModelCacheIDs( $affected_IDs );
        }
    }
}