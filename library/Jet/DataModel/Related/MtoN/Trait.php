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

trait DataModel_Related_MtoN_Trait {
    use DataModel_Related_Trait;

	/**
	 * @var DataModel_ID_Abstract
	 */
	private $_N_ID;

	/**
	 * @var DataModel
	 */
	private $_N_instance;

    /**
     * @return DataModel_Related_Interface
     */
    public function createNewRelatedDataModelInstance()
    {
	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	     */
	    $data_model_definition = $this->getDataModelDefinition();

	    $iterator_class_name = $data_model_definition->getIteratorClassName();

        $i = new $iterator_class_name( $data_model_definition );

        return $i;
    }

    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_MtoN
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
        return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
    }


    /**
     * @param DataModel_Interface $main_model_instance
     * @param DataModel_Related_Interface $parent_model_instance (optional)
     *
     * @throws DataModel_Exception
     */
    public function setupParentObjects(DataModel_Interface $main_model_instance, DataModel_Related_Interface $parent_model_instance = null)
    {
    	$this->_main_model_instance = $main_model_instance;
	    $this->_parent_model_instance = $parent_model_instance;

	    if( $parent_model_instance ) {
		    $M_instance = $parent_model_instance;
	    } else {
		    $M_instance = $main_model_instance;
	    }

	    $this->setMInstance( $M_instance );

    }



    /**
     * @return null
     */
    public function getArrayKeyValue() {
        return $this->getNID()->toString();
    }


	/**
	 * @param DataModel_Load_OnlyProperties|null $load_only_related_properties
	 *
	 * @return array
	 */
	public function loadRelatedData( DataModel_Load_OnlyProperties $load_only_related_properties=null )
    {
    	return [];
    }


    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    public function loadRelatedInstances(array &$loaded_related_data)
    {

        /**
         * @var DataModel_Definition_Model_Related_1toN $data_model_definition
         */
        $data_model_definition = $this->getDataModelDefinition();


        $model_name = $data_model_definition->getModelName();
        $items = [];

        if(empty($loaded_related_data[$model_name])) {
            return $items;
        }


        foreach( $loaded_related_data[$model_name] as $i=>$dat ) {

            /**
             * @var DataModel_Related_MtoN $loaded_instance
             */
            $loaded_instance = new static();
            $loaded_instance->_setRelatedData( $dat );

            unset($loaded_related_data[$model_name][$i]);

            $key = $loaded_instance->getArrayKeyValue();
            if(is_object($key)) {
                $key = (string)$key;
            }

            if($key!==null) {
                $items[$key] = $loaded_instance;
            } else {
                $items[] = $loaded_instance;
            }

        }

        return $items;
    }


	/**
	 * @param DataModel_Interface $M_instance
	 */
	public function setMInstance( DataModel_Interface $M_instance )
	{
		/**
		 * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
		 */
		$data_model_definition = $this->getDataModelDefinition();

		$M_ID = $M_instance->getIdObject();
		$M_ID_properties = $data_model_definition->getMRelationIDProperties();


		foreach($M_ID_properties as $M_ID_property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $M_ID_property
			 */
			$value = $M_ID[$M_ID_property->getRelatedToPropertyName()];

			$this->{$M_ID_property->getName()} = $value;
		}

	}

    /**
     * @param DataModel_Interface $N_instance
     */
    public function setNInstance( DataModel_Interface $N_instance ) {
    	$this->_N_instance = $N_instance;
	    $this->_N_instance = $N_instance->getIdObject();

	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	     */
	    $data_model_definition = $this->getDataModelDefinition();

	    $N_ID = $N_instance->getIdObject();
	    $N_ID_properties = $data_model_definition->getNRelationIDProperties();


	    foreach($N_ID_properties as $N_ID_property) {
		    /**
		     * @var DataModel_Definition_Property_Abstract $M_ID_property
		     */
		    $value = $N_ID[$N_ID_property->getRelatedToPropertyName()];

		    $this->{$N_ID_property->getName()} = $value;
	    }

    }

	/**
	 * @param DataModel_Load_OnlyProperties|null $load_only_related_properties
	 *
	 * @return DataModel|null
	 */
    public function getNInstance( DataModel_Load_OnlyProperties $load_only_related_properties=null ) {

        if(!$this->_N_instance) {

	        /**
	         * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	         */
	        $data_model_definition = $this->getDataModelDefinition();

            $n_class_name = $data_model_definition->getNModelClassName();

            /** @noinspection PhpUndefinedMethodInspection */
	        $this->_N_instance = $n_class_name::load($this->getNID(), $load_only_related_properties);

        }

        return $this->_N_instance;
    }

    /**
     * @return DataModel_ID_Abstract
     */
    public function getNID() {

        if(!$this->_N_ID) {
            /**
             * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
             */
            $data_model_definition = $this->getDataModelDefinition();

            /**
             * @var DataModel_Definition_Property_Abstract[] $N_ID_properties
             */
            $N_ID_properties = $data_model_definition->getNRelationIDProperties();
            $n_class_name = $data_model_definition->getNModelClassName();

            /**
             * @var DataModel $N_model_instance
             */
            $N_model_instance = new $n_class_name();
            $this->_N_ID = $N_model_instance->getEmptyIdObject();

            foreach( $N_ID_properties as $N_ID_prop_name=>$N_ID_prop) {
                $this->_N_ID[$N_ID_prop->getRelatedToPropertyName()] = $this->{$N_ID_prop_name};
            }

        }

        return $this->_N_ID;
    }




    /**
     * @param string $prefix
     *
     * @return string
     */
    public function XMLSerialize($prefix='' ) {

	    /**
	     * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
	     */
	    $data_model_definition = $this->getDataModelDefinition();

        $N_class_name = $data_model_definition->getNModelClassName();

        $result = '';

        $result .= $prefix . JET_TAB.'<'.$N_class_name.'>'.JET_EOL;
        foreach($this->getNID() as $ID_k=>$ID_v) {
            $result .= $prefix . JET_TAB.JET_TAB.'<'.$ID_k.'>'.Data_Text::htmlSpecialChars($ID_v).'</'.$ID_k.'>'.JET_EOL;
        }
        $result .= $prefix . JET_TAB.'</'.$N_class_name.'>'.JET_EOL;

        return $result;

    }

    /**
     *
     */
    public function __wakeup_relatedItems() {
    }

    /** @noinspection PhpMissingParentCallMagicInspection
     *
     * @return array
     */
    public function __sleep() {
        return [];
    }

    /** @noinspection PhpMissingParentCallMagicInspection
     *
     */
    public function __wakeup() {
    }

    /**
     *
     * @param DataModel_Definition_Property_Abstract $parent_property_definition
     * @param array $properties_list
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields(
        /** @noinspection PhpUnusedParameterInspection */
        DataModel_Definition_Property_Abstract $parent_property_definition,
        array $properties_list
    ) {
        return [];
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm(
        /** @noinspection PhpUnusedParameterInspection */
        array $values
    )
    {
        return true;
    }

}
