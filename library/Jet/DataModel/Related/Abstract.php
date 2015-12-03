<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
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

abstract class DataModel_Related_Abstract extends DataModel implements DataModel_Related_Interface {


    /**
     * @var DataModel
     */
    protected $__main_model_instance;

    /**
     * @var DataModel_Related_Abstract
     */
    protected $__parent_model_instance;

    /**
     * @return DataModel_Related_Interface
     */
    public function createNewRelatedDataModelInstance() {
        return new static();
    }


    /**
     * @return bool
     */
    public function getBackendTransactionStarted() {
        if(
            $this->__main_model_instance &&
            $this->__main_model_instance->getBackendTransactionStarted()
        ) {
            return true;
        }

        if(
            $this->__parent_model_instance &&
            $this->__parent_model_instance->getBackendTransactionStarted()
        ) {
            return true;
        }

        return $this->__backend_transaction_started;
    }

    /**
     * @return bool
     */
    public function getBackendTransactionStartedByThisInstance() {
        if(!$this->getBackendTransactionStarted()) {
            return false;
        }

        if(
            $this->__main_model_instance &&
            $this->__main_model_instance->getBackendTransactionStarted()
        ) {
            return false;
        }

        if(
            $this->__parent_model_instance &&
            $this->__parent_model_instance->getBackendTransactionStarted()
        ) {
            return false;
        }

        return true;
    }


    /**
     * @return DataModel_Query
     */
    protected function getLoadRelatedDataQuery() {
        $data_model_definition = $this->getDataModelDefinition();

        $query = new DataModel_Query( $data_model_definition );

        $query->setSelect( $data_model_definition->getProperties() );
        $query->setWhere([]);

        $where = $query->getWhere();

        if( $this->__main_model_instance ) {
            $main_model_ID = $this->__main_model_instance->getID();

            foreach( $data_model_definition->getMainModelRelationIDProperties() as $property ) {
                /**
                 * @var DataModel_Definition_Property_Abstract $property
                 */
                $property_name = $property->getRelatedToPropertyName();
                $value = $main_model_ID[ $property_name ];

                $where->addAND();
                $where->addExpression(
                    $property,
                    DataModel_Query::O_EQUAL,
                    $value

                );

            }
        } else {
            if( $this->__parent_model_instance ) {
                $parent_model_ID = $this->__parent_model_instance->getID();

                foreach( $data_model_definition->getParentModelRelationIDProperties() as $property ) {
                    /**
                     * @var DataModel_Definition_Property_Abstract $property
                     */
                    $property_name = $property->getRelatedToPropertyName();
                    $value = $parent_model_ID[ $property_name ];

                    $where->addAND();
                    $where->addExpression(
                        $property,
                        DataModel_Query::O_EQUAL,
                        $value

                    );

                }

            } else {
                throw new DataModel_Exception('Parents are not set!');
            }
        }

        return $query;
    }

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    protected function initRelatedProperties( array &$loaded_related_data ) {
        $definition = static::getDataModelDefinition();

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {
            if(!$property_definition->getIsDataModel()) {
                continue;
            }

            /**
             * @var DataModel_Definition_Property_DataModel $property_definition
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            $property->setupParentObjects( $this->__main_model_instance, $this );

            $this->{$property_name} = $property->createRelatedInstancesFromLoadedRelatedData( $loaded_related_data );
        }

    }


    /**
     * @param DataModel $main_model_instance
     * @param DataModel_Related_Abstract $parent_model_instance (optional)
     *
     */
    public function setupParentObjects( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null ) {

        $this->__main_model_instance = $main_model_instance;
        $this->__parent_model_instance = $parent_model_instance;

        $main_ID = $main_model_instance->getID();
        $definition = $this->getDataModelDefinition();

        foreach( $definition->getMainModelRelationIDProperties() as $property_definition ) {

            if(isset($main_ID[$property_definition->getRelatedToPropertyName()])) {
                if(
                    $this->getIsSaved() &&
                    $this->{$property_definition->getName()} != $main_ID[$property_definition->getRelatedToPropertyName()]
                ) {
                    $this->setIsNew();
                }

                $this->{$property_definition->getName()} = $main_ID[$property_definition->getRelatedToPropertyName()];
            }
        }

        if($parent_model_instance) {
            $parent_ID = $parent_model_instance->getID();

            foreach( $definition->getParentModelRelationIDProperties() as $property_definition ) {

                if(
                    $this->getIsSaved() &&
                    $this->{$property_definition->getName()} != $parent_ID[$property_definition->getRelatedToPropertyName()]
                ) {
                    $this->setIsNew();
                }

                if(isset($parent_ID[$property_definition->getRelatedToPropertyName()])) {
                    $this->{$property_definition->getName()} = $parent_ID[$property_definition->getRelatedToPropertyName()];
                }
            }

        }


        foreach( $definition->getProperties() as $property_definition ) {
            if(!$property_definition->getIsDataModel()) {
                continue;
            }

            /**
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_definition->getName()};
            if(!$property) {
                continue;
            }

            $property->setupParentObjects($this->__main_model_instance, $this);

        }


    }


    /**
     *
     */
    protected function _saveRelatedObjects() {
        $definition = $this->getDataModelDefinition();

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            if(
                !$property_definition->getIsDataModel() ||
                $this->{$property_name}===null
            ) {
                continue;
            }

            /**
             * @var DataModel_Related_Interface $prop
             */
            $prop = $this->{$property_name};

            $prop->setupParentObjects( $this->__main_model_instance, $this );
            $prop->save();

        }
    }



    /**
     *
     */
    public function updateDataModelCache( $operation ) {
        if(!$this->__main_model_instance) {
            return;
        }
        $this->__main_model_instance->updateDataModelCache($operation);
    }

    /**
     *
     */
    public function deleteDataModelCache() {
        if(!$this->__main_model_instance) {
            return;
        }
        $this->__main_model_instance->deleteDataModelCache();
    }

    /**
     * @param string $operation
     */
    protected function dataModelHistoryOperationStart( $operation ) {
        if(!$this->__main_model_instance) {
            return;
        }
        $this->__main_model_instance->dataModelHistoryOperationStart( $operation );
    }

    /**
     *
     */
    protected function dataModelHistoryOperationDone() {
        if(!$this->__main_model_instance) {
            return;
        }
        $this->__main_model_instance->dataModelHistoryOperationDone();
    }



	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_Abstract( $data_model_class_name );
	}


    /**
     *
     */
    public function __wakeup_relatedItems() {
        foreach( $this->getDataModelDefinition()->getProperties() as $property_name=>$property ) {
            if(!$property->getIsDataModel()) {
                continue;
            }

            /**
             * @var DataModel_Related_Abstract $p
             */
            $p = $this->{$property_name};

            $p->__wakeup_relatedItems();
        }
    }

}