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

trait DataModel_Related_1to1_Trait {
    use DataModel_Related_Trait;

    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_1to1
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
        return new DataModel_Definition_Model_Related_1to1( $data_model_class_name );
    }


	/**
	 * @param DataModel_Load_OnlyProperties|null $load_only_properties
	 *
	 * @return array
	 */
	public function loadRelatedData( DataModel_Load_OnlyProperties $load_only_properties=null )
    {
	    if(
		    $load_only_properties
	    ) {
	    	if(!$load_only_properties->getAllowToLoadModel( $this->getDataModelDefinition()->getModelName() )) {
			    return [];
		    }

		    $this->setLoadOnlyProperties($load_only_properties);
	    }

        $query = $this->getLoadRelatedDataQuery();

        return $this->getBackendInstance()->fetchAll( $query );
    }

	/**
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Query
	 */
	protected function getLoadRelatedDataQuery() {

		/**
		 * @var DataModel_Interface|DataModel_Related_Interface $this
		 * @var DataModel_Definition_Model_Related_Abstract $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );

		$select = DataModel_Load_OnlyProperties::getSelectProperties( $data_model_definition, $this->getLoadOnlyProperties() );

		$query->setSelect( $select );
		$query->setWhere([]);

		$where = $query->getWhere();

		if( $this->_main_model_instance ) {
			$main_model_ID = $this->_main_model_instance->getIdObject();

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
			if( $this->_parent_model_instance ) {
				$parent_model_ID = $this->_parent_model_instance->getIdObject();

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
    public function loadRelatedInstances(array &$loaded_related_data ) {

        /**
         * @var DataModel_Definition_Model_Related_1to1 $definition
         */
        $definition = $this->getDataModelDefinition();

        $parent_ID_values = [];
        if($this->_parent_model_instance) {
            $parent_ID = $this->_parent_model_instance->getIdObject();

            foreach( $definition->getParentModelRelationIDProperties() as $property ) {

                /**
                 * @var DataModel_Definition_Property_Abstract $property
                 */
                $parent_ID_values[$property->getName()] = $parent_ID[$property->getRelatedToPropertyName()];

            }
        }

        $model_name = $definition->getModelName();
        if(!empty($loaded_related_data[$model_name])) {


            foreach( $loaded_related_data[$model_name] as $i=>$dat ) {
                if($parent_ID_values) {
                    foreach($parent_ID_values as $k=>$v) {
                        if($dat[$k]!=$v) {
                            continue 2;
                        }
                    }
                }

                /**
                 * @var DataModel_Related_1to1 $loaded_instance
                 */
                $loaded_instance = new static();
                $loaded_instance->setupParentObjects(
                    $this->_main_model_instance,
                    $this->_parent_model_instance
                );

	            $loaded_instance->setLoadOnlyProperties($this->getLoadOnlyProperties());

                $loaded_instance->_setRelatedData( $dat, $loaded_related_data );
                $loaded_instance->afterLoad();

                unset($loaded_related_data[$model_name][$i]);

                return $loaded_instance;
            }
        }

        return null;
    }


    /**
     *
     * @param DataModel_Definition_Property_Abstract $parent_property_definition
     * @param array $properties_list
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list ) {

        $fields = [];

        /**
         * @var Form $related_form
         */
        $related_form = $this->getForm('', $properties_list);

        foreach($related_form->getFields() as $field) {

            $field->setName('/'.$parent_property_definition->getName().'/'.$field->getName() );

            $fields[] = $field;
        }


        return $fields;
    }


    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm( array $values ) {

        //$r_form = $this->getForm( '', array_keys($values) );
        $r_form = $this->getCommonForm();

        return $this->catchForm( $r_form, $values, true );
    }


}
