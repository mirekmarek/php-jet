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

trait DataModel_Related_Trait_Save {
    /**
     *
     */
    protected function _saveRelatedObjects() {
        /**
         * @var DataModel_Interface|DataModel_Related_Interface $this
         * @var DataModel_Definition_Model_Abstract $definition
         */
        $definition = $this->getDataModelDefinition();

        $this_main_model_instance = &DataModel_ObjectState::getVar($this, 'main_model_instance');

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->setupParentObjects( $this_main_model_instance, $this );
            $property->save();

        }
    }

}