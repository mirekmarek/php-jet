<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
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

abstract class DataModel_Related_1toN extends DataModel_Related_Abstract {



	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_1toN( $data_model_class_name );
	}


	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue() {
		return null;
	}

    /**
     * Loads DataModel.
     *
     * @param DataModel $main_model_instance
     * @param DataModel_Related_Abstract $parent_model_instance
     *
     * @throws DataModel_Exception
     * @return DataModel
     */
    public function loadRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {
        $backend = $this->getBackendInstance();

        $model_definition = $this->getDataModelDefinition();

        $query = new DataModel_Query( $model_definition );
        $query->setWhere(array());

        $query->getWhere()->attach(
            $main_model_instance->getID()->getQuery(  $model_definition->getMainModelRelationIDProperties() )->getWhere()
        );

        if($parent_model_instance) {
            $query->getWhere()->attach(
                $parent_model_instance->getID()->getQuery(  $model_definition->getParentModelRelationIDProperties() )->getWhere()
            );
        }

        $query->setSelect( $model_definition->getProperties() );

        $data = $backend->fetchRow( $query );

        if(!$data) {
            return null;
        }

        $loaded_instance = $this->createInstance( $data, $main_model_instance );

        return $loaded_instance;

    }

    /**
     * @param DataModel $main_model_instance
     * @param DataModel_Related_Abstract $parent_model_instance
     */
    public function wakeUp( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {
        foreach( $this->getDataModelDefinition()->getProperties() as $property_name=>$property ) {
            if(!$property->getIsDataModel()) {
                continue;
            }

            /**
             * @var DataModel_Related_Abstract $p
             */
            $p = $this->{$property_name};

            $p->wakeUp( $main_model_instance, $this );
        }
    }

}