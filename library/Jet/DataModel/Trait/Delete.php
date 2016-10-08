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

trait DataModel_Trait_Delete {
    /**
     *
     * @throws DataModel_Exception
     */
    public function delete() {
        /**
         * @var DataModel $this
         */
        if( !$this->getIdObject() || !$this->getIsSaved() ) {
            throw new DataModel_Exception('Nothing to delete... Object was not loaded. (Class: \''.get_class($this).'\', ID:\''.$this->getIdObject().'\')', DataModel_Exception::CODE_NOTHING_TO_DELETE);
        }

        $backend = $this->getBackendInstance();
        $definition = $this->getDataModelDefinition();

        $this->startBackendTransaction( $backend );

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            $prop = $this->{$property_name};
            if( $prop instanceof DataModel_Related_Interface ) {
                $prop->delete();
            }
        }

        $backend->delete( $this->getIdObject()->getQuery() );

        $this->commitBackendTransaction( $backend );

        $this->afterDelete();
    }
}