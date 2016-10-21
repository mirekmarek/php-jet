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
	 * @var bool
	 */
	private $_data_model_saved = false;

    /**
     * Initializes new DataModel
     *
     */
    public function initNewObject() {
        $this->setIsNew();

        /**
         * @var DataModel $this
         * @var DataModel_Definition_Model_Abstract $data_model_definition
         */
        $data_model_definition = static::getDataModelDefinition();

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
        return !$this->_data_model_saved;
    }

    /**
     *
     */
    public function setIsNew() {
        $this->_data_model_saved = false;
    }

    /**
     * @return bool
     */
    public function getIsSaved() {
	    return $this->_data_model_saved;
    }

    /**
     *
     */
    public function setIsSaved() {
        $this->_data_model_saved = true;
    }

}