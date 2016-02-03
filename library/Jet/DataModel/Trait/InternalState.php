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

trait DataModel_Trait_InternalState {

    /**
     * Initializes new DataModel
     *
     */
    public function initNewObject() {
        $this->setIsNew();

        /**
         * @var DataModel $this
         */
        $data_model_definition = $this->getDataModelDefinition();

        foreach( $data_model_definition->getProperties() as $property_name => $property_definition ) {

            $property_definition->initPropertyDefaultValue( $this->{$property_name}, $this );

        }

    }




    /**
     * Returns true if the model instance is new (was not saved yet)
     *
     * @return bool
     */
    public function getIsNew() {
        /**
         * @var DataModel $this
         */
        return !DataModel_ObjectState::getVar($this, 'data_model_saved', false);
    }

    /**
     *
     */
    public function setIsNew() {
        /**
         * @var DataModel $this
         */
        $data_model_saved = &DataModel_ObjectState::getVar($this, 'data_model_saved', false);
        $data_model_saved = false;
    }

    /**
     * @return bool
     */
    public function getIsSaved() {
        /**
         * @var DataModel $this
         */
        return DataModel_ObjectState::getVar($this, 'data_model_saved', false);
    }

    /**
     *
     */
    public function setIsSaved() {
        /**
         * @var DataModel $this
         */
        $data_model_saved = &DataModel_ObjectState::getVar($this, 'data_model_saved', false);
        $data_model_saved = true;
    }

}