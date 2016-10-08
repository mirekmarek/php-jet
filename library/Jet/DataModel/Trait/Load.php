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

trait DataModel_Trait_Load {

	/**
	 * @var DataModel_Load_OnlyProperties
	 */
	protected $load_only_properties;

    /**
     * Loads DataModel.
     *
     * @param array|DataModel_ID_Abstract $ID
     * @param array|DataModel_Load_OnlyProperties $load_only_properties
     *
     * @return DataModel
     */
    public static function load( $ID, $load_only_properties=null ) {

	    /**
	     * @var DataModel $_this
	     */
        $_this = new static();
        foreach( $ID as $key=>$val ) {
            $_this->{$key} = $val;
        }

	    if($load_only_properties) {
	    	$_this->setLoadOnlyProperties($load_only_properties);
	    }

	    $main_data = $_this->loadMainData();

        if(!$main_data) {
            return null;
        }

	    $_this->setState($main_data);

	    $_this->setRelatedState(
		    $_this->loadRelatedData()
	    );

	    /** @noinspection PhpUndefinedMethodInspection */
	    $_this->afterLoad();

	    /** @noinspection PhpIncompatibleReturnTypeInspection */
	    return $_this;
    }

	/**
	 * @return DataModel_Load_OnlyProperties
	 */
	public function getLoadOnlyProperties()
	{
		return $this->load_only_properties;
	}

	/**
	 * @param DataModel_Load_OnlyProperties|array $load_only_properties
	 */
	public function setLoadOnlyProperties($load_only_properties)
	{
		if(!$load_only_properties) {
			$this->load_only_properties = null;
			return;
		}

		$definition = $this->getDataModelDefinition();

		if(!($load_only_properties instanceof DataModel_Load_OnlyProperties)) {
			$this->load_only_properties = new DataModel_Load_OnlyProperties( $definition, $load_only_properties );
		} else {
			$this->load_only_properties = $load_only_properties;
		}
	}

	/**
	 * @return array|bool
	 */
    protected function loadMainData() {
	    /**
	     * @var DataModel $this
	     */

	    $ID = $this->getIdObject();

	    $definition = $this->getDataModelDefinition();

	    $query = $ID->getQuery();


	    /** @noinspection PhpParamsInspection */
	    $query->setMainDataModel($this);

	    $select = DataModel_Load_OnlyProperties::getSelectProperties( $definition, $this->getLoadOnlyProperties() );

	    $query->setSelect( $select );

	    return $this->getBackendInstance()->fetchRow( $query );

    }

	/**
	 * @return array|bool
	 */
	protected function loadRelatedData()
	{
		/**
		 * @var DataModel $this
		 */

		$definition = $this->getDataModelDefinition();
		$related_properties = $definition->getAllRelatedPropertyDefinitions();

		$related_data = [];
		foreach( $related_properties as $related_model_name=>$related_property ) {

			/**
			 * @var DataModel_Related_Interface $related_object
			 */
			$related_object = $related_property->getDefaultValue();
			$related_object->setupParentObjects( $this );
			$_related_data = $related_object->loadRelatedData( $this->getLoadOnlyProperties() );
			unset($related_object);

			if(!isset($related_data[$related_model_name])) {
				$related_data[$related_model_name] = [];

				foreach($_related_data as $rd) {
					$related_data[$related_model_name][] = $rd;
				}
			}
		}

		return $related_data;
	}

	/**
	 * @param array $data
	 */
    public function setState( array $data ) {
	    /**
	     * @var DataModel $this
	     */
	    $definition = $this->getDataModelDefinition();

	    foreach( $definition->getProperties() as $property_name=>$property_definition ) {

		    if(!($this->{$property_name} instanceof DataModel_Related_Interface)) {
			    $property_definition->loadPropertyValue( $this->{$property_name}, $data );
		    }
	    }
	    $this->setIsSaved();
    }

	/**
	 * @param array $related_data
	 */
	public function setRelatedState( array $related_data )
	{
		/**
		 * @var DataModel $this
		 */

		$definition = $this->getDataModelDefinition();
		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			$property = $this->{$property_name};

			if(($property instanceof DataModel_Related_Interface)) {
				$property->setupParentObjects( $this );
				$property_definition->loadPropertyValue( $property, $related_data );
			}
		}
	}


    /**
     * @param array &$loaded_related_data
     */
    protected function initRelatedProperties( array &$loaded_related_data ) {
        /**
         * @var DataModel $this
         */
        $definition = $this->getDataModelDefinition();

        foreach( $definition->getProperties() as $property_name=>$property_definition ) {

            /**
             * @var DataModel_Definition_Property_DataModel $property_definition
             * @var DataModel_Related_Interface $property
             */
            $property = $this->{$property_name};
            if(!($property instanceof DataModel_Related_Interface)) {
                continue;
            }

            $property->setupParentObjects( $this );

            $this->{$property_name} = $property->loadRelatedInstances( $loaded_related_data );
        }

    }


}