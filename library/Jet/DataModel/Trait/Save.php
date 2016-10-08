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
        } else {
            $after_method_name = 'afterUpdate';
            $operation = 'update';
        }

        try {
            $this->{'_'.$operation}( $backend );
        } catch (Exception $e) {
            $this->rollbackBackendTransaction($backend);

            throw $e;
        }

        if($this->getBackendTransactionStartedByThisInstance()) {
            $this->commitBackendTransaction( $backend );
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

        $this->generateIdObject();
        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            if( !$property_definition->getCanBeInInsertRecord() ) {
                continue;
            }

            $record->addItem($property_definition, $this->{$property_name});
        }


        $backend_result = $backend->save( $record );

        $this->generateIdObject( true, $backend_result );

        /**
         * @var DataModel_Trait_Save $this
         */
        $this->_saveRelatedObjects();

    }

	/**
	 * @param DataModel_Backend_Abstract $backend
	 *
	 * @throws DataModel_Exception
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
	        $where_query = $this->getIdObject()->getQuery();
	        if($where_query->getWhere()->getIsEmpty()) {
		        throw  new DataModel_Exception('Empty WHERE!');
	        }

            $backend->update($record, $where_query );
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

        $this->getBackendInstance()->update(
            DataModel_RecordData::createRecordData( $this,
                $data
            ),
            DataModel_Query::createQuery( $this->getDataModelDefinition(),
                $where
            )
        );

    }
}