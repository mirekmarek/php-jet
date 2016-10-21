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
	 * @param DataModel_ID_Abstract $main_ID
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array|mixed
	 */
	public function loadRelatedData(DataModel_ID_Abstract $main_ID, DataModel_PropertyFilter $load_filter=null )
    {
	    if(
		    $load_filter
	    ) {
	    	if(!$load_filter->getModelAllowed( static::getDataModelDefinition()->getModelName() )) {
			    return [];
		    }

		    $this->setLoadFilter($load_filter);
	    }

        $query = $this->getLoadRelatedDataQuery($main_ID, $load_filter);

        return static::getBackendInstance()->fetchAll( $query );
    }

	/**
	 * @param DataModel_ID_Abstract $main_ID
	 * @param DataModel_PropertyFilter|null $load_filter
	 * @return DataModel_Query
	 *
	 * @throws DataModel_Exception
	 */
	protected function getLoadRelatedDataQuery(DataModel_ID_Abstract $main_ID, DataModel_PropertyFilter $load_filter=null ) {

		/**
		 * @var DataModel_Interface|DataModel_Related_Interface $this
		 * @var DataModel_Definition_Model_Related_Abstract $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );

		$select = DataModel_PropertyFilter::getQuerySelect( $data_model_definition, $load_filter );

		$query->setSelect( $select );
		$query->setWhere([]);

		$where = $query->getWhere();

		foreach( $data_model_definition->getMainModelRelationIDProperties() as $property ) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$property_name = $property->getRelatedToPropertyName();
			$value = $main_ID[ $property_name ];

			$where->addAND();
			$where->addExpression(
				$property,
				DataModel_Query::O_EQUAL,
				$value

			);

		}

		return $query;
	}


	/**
	 * @param array &$loaded_related_data
	 * @param DataModel_ID_Abstract|null $parent_ID
	 * @param DataModel_PropertyFilter|null $load_filter
	 * @return mixed
	 */
    public function loadRelatedInstances(array &$loaded_related_data, DataModel_ID_Abstract $parent_ID=null, DataModel_PropertyFilter $load_filter=null ) {

        /**
         * @var DataModel_Definition_Model_Related_1to1 $definition
         */
        $definition = static::getDataModelDefinition();

        $parent_ID_values = [];
        if($parent_ID) {

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

	            $loaded_instance->setLoadFilter($load_filter);

                $loaded_instance->setState( $dat, $loaded_related_data );

                unset($loaded_related_data[$model_name][$i]);

                return $loaded_instance;
            }
        }

        return null;
    }


	/**
	 *
	 * @param DataModel_Definition_Property_Abstract $parent_property_definition
	 * @param DataModel_PropertyFilter $property_filter
	 *
	 * @return Form_Field_Abstract[]
	 */
    public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, DataModel_PropertyFilter $property_filter=null ) {

        $fields = [];

        /**
         * @var Form $related_form
         */
        $related_form = $this->getForm('', $property_filter);

        foreach($related_form->getFields() as $field) {

            $field->setName('/'.$parent_property_definition->getName().'/'.$field->getName() );

            $fields[] = $field;
        }


        return $fields;
    }


}
