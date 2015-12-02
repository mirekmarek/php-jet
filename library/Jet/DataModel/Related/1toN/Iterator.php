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

class DataModel_Related_1toN_Iterator implements \ArrayAccess, \Iterator, \Countable, DataModel_Related_Interface   {


    /**
     * @var string
     */
    protected $item_class_name = '';

    /**
     * @var DataModel_Related_1toN[]
     */
    protected $items = null;

    /**
     * @var DataModel_Related_1to1[]
     */
    protected $deleted_items = array();

    /**
     * @var DataModel
     */
    protected $__main_model_instance;

    /**
     * @var DataModel_Related_Abstract
     */
    protected $__parent_model_instance;

    /**
     * @var DataModel_Related_1toN
     */
    protected $__empty_item_instance;

    /**
     * @param $item_class_name
     */
    public function __construct( $item_class_name ) {
        $this->item_class_name = $item_class_name;
    }


    /**
     * @return DataModel_Related_1toN
     */
    protected function _getEmptyItemInstance() {
        if(!$this->__empty_item_instance) {
            $this->__empty_item_instance = new $this->item_class_name();

            $this->__empty_item_instance->setMainDataModelInstance( $this->__main_model_instance );
            if($this->__parent_model_instance) {
                $this->__empty_item_instance->setMainDataModelInstance( $this->__parent_model_instance );
            }
        }

        return $this->__empty_item_instance;

    }

    /**
     * @return DataModel_Related_Interface|null
     */
    public function createNewRelatedDataModelInstance() {
        return $this;
    }

    /**
     * @param DataModel $main_model_instance
     */
    public function setMainDataModelInstance( DataModel $main_model_instance ) {
        $this->__main_model_instance = $main_model_instance;

        if($this->__empty_item_instance) {
            $this->__empty_item_instance->setMainDataModelInstance($main_model_instance);
        }

        if($this->items) {
            foreach( $this->items as $item ) {
                $item->setMainDataModelInstance( $main_model_instance );
            }
        }
    }

    /**
     * @param DataModel_Related_Abstract $parent_model_instance
     */
    public function setParentDataModelInstance( DataModel_Related_Abstract $parent_model_instance ) {
        $this->__parent_model_instance = $parent_model_instance;

        if($this->__empty_item_instance) {
            $this->__empty_item_instance->setParentDataModelInstance($parent_model_instance);
        }

        if($this->items) {
            foreach( $this->items as $item ) {
                $item->setParentDataModelInstance( $parent_model_instance );
            }
        }
    }

    /**
     * @return array|DataModel_Definition_Property_DataModel[]
     */
    public function getAllRelatedPropertiesDefinitions() {

        $class_name = $this->item_class_name;

        /**
         * @var DataModel_Related_1toN $data_model
         */
        $data_model = new $class_name();

        return $data_model->getAllRelatedPropertiesDefinitions();
    }


    /**
     * @return array|void
     */
    public function loadRelatedData() {

        return $this->_getEmptyItemInstance()->loadRelatedData();
    }

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    public function createRelatedInstancesFromLoadedRelatedData( array &$loaded_related_data ) {
        $this->deleted_items = array();

        $this->items = $this->_getEmptyItemInstance()->createRelatedInstancesFromLoadedRelatedData($loaded_related_data);

        return $this;
    }

    /**
     * @param string $parent_field_name
     * @param string$related_form_getter_method_name
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields( $parent_field_name, $related_form_getter_method_name ) {

        $fields = array();
        if(!$this->items) {
            return $fields;

        }

        foreach($this->items as $key=>$related_instance) {

            /**
             * @var DataModel_Related_1toN $related_instance
             * @var Form $related_form
             */
            $related_form = $related_instance->{$related_form_getter_method_name}();

            foreach($related_form->getFields() as $field) {

                if(
                    $field instanceof Form_Field_Hidden
                ) {
                    continue;
                }

                $field_name = $field->getName();

                if($field_name[0]=='/') {
                    $field->setName('/'.$parent_field_name.'/'.$key.$field_name );
                } else {
                    $field->setName('/'.$parent_field_name.'/'.$key.'/'.$field_name );
                }


                $fields[] = $field;
            }

        }

        return $fields;
    }


    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm( array $values ) {

        $ok = true;
        if(!$this->items) {
            return $ok;

        }

        foreach( $this->items as $r_key=>$r_instance ) {

            $values = isset( $values[$r_key] ) ? $values[$r_key] : array();

            /**
             * @var DataModel $r_instance
             */
            //$r_form = $r_instance->getForm( '', array_keys($values) );
            $r_form = $r_instance->getCommonForm();

            if(!$r_instance->catchForm( $r_form, $values, true )) {
                $ok = false;
            }

        }

        return $ok;
    }



    /**
     * @return DataModel_Validation_Error[]
     */
    public function getValidationErrors() {
        $result = array();

        foreach( $this-> items as $item) {
            foreach( $item->getValidationErrors() as $error ) {
                $result[] = $error;
            }
        }

        return $result;
    }


    /**
     *
     */
    public function __wakeup_relatedItems() {
        if($this->items) {
            foreach( $this->items as $item ) {
                $item->__wakeup_relatedItems();
            }
        }
    }

    /**
     * Validates data and returns true if everything is OK and ready to save
     *
     * @throws DataModel_Exception
     * @return bool
     */
    public function validateProperties() {
        if( !$this->items ) {
            return true;
        }

        foreach($this->items as $d) {
            if( !$d->validateProperties() ) {
                return false;
            }
        }

        return true;
    }


    /**
     * Save data.
     * CAUTION: Call validateProperties first!
     *
     *
     * @throws Exception
     * @throws DataModel_Exception
     */
    public function save() {

        foreach($this->deleted_items as $item) {
            /**
             * @var DataModel_Related_1toN $item
             */
            if($item->getIsSaved()) {
                $item->delete();
            }
        }

        if( !$this->items ) {
            return;
        }

        foreach($this->items as $d) {
            $d->save();
        }

    }


    /**
     *
     * @throws DataModel_Exception
     */
    public function delete() {
        foreach($this->deleted_items as $item) {
            $item->delete();
        }

        if( !$this->items ) {
            return;
        }

        foreach($this->items as $d) {
            if($d->getIsSaved()) {
                $d->delete();
            }
        }
    }


    /**
     * @return array
     */
    public function jsonSerialize() {

        $res = array();

        if(!$this->items) {
            return $res;
        }

        foreach($this->items as $k=>$d) {
            $res[$k] = $d->jsonSerialize();
        }

        return $res;

    }

    /**
     * @return string
     */
    public function toXML() {
        $res = array();
        if(is_array($this->items)) {
            foreach($this->items as $d) {
                /**
                 * @var DataModel_Related_1toN $d
                 */
                $res[] = $d->toXML();
            }
        }

        return implode(JET_EOL,$res);
    }

    /**
     * @return string
     */
    public function toJSON() {
        $data = $this->jsonSerialize();
        return json_encode($data);
    }



//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------

    public function clearData() {
        if($this->items) {
            $this->deleted_items = $this->items;
        }
        $this->items = array();
    }

    /**
     * @see \Countable
     *
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists( $offset  ) {
        return isset($this->items[$offset]);
    }
    /**
     * @see \ArrayAccess
     * @param mixed $offset
     *
     * @return DataModel
     */
    public function offsetGet( $offset ) {
        return $this->items[$offset];
    }

    /**
     *
     * @see \ArrayAccess
     *
     * @param mixed $offset
     * @param DataModel_Related_1toN $value
     *
     * @throws DataModel_Exception
     */
    public function offsetSet( $offset , $value ) {

        $valid_class = $this->item_class_name;

        if( !($value instanceof $valid_class) ) {
            throw new DataModel_Exception(
                'New item must be instance of \''.$valid_class.'\' class. \''.get_class($value).'\' given.',
                DataModel_Exception::CODE_INVALID_CLASS
            );
        }

        if(is_null($offset)) {
            /**
             * @var DataModel_Related_1toN $value
             */
            $offset = $value->getArrayKeyValue();
            if(is_object($offset)) {
                $offset = (string)$offset;
            }
        }

        if(!$offset) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     */
    public function offsetUnset( $offset )	{
        $this->deleted_items[] = $this->items[$offset];

        unset( $this->items[$offset] );
    }

    /**
     * @see \Iterator
     *
     * @return DataModel
     */
    public function current() {
        if( $this->items===null ) {
            return null;
        }
        return current($this->items);
    }
    /**
     * @see \Iterator
     *
     * @return string
     */
    public function key() {
        if( $this->items===null ) {
            return null;
        }
        return key($this->items);
    }
    /**
     * @see \Iterator
     */
    public function next() {
        if( $this->items===null ) {
            return null;
        }
        return next($this->items);
    }
    /**
     * @see \Iterator
     */
    public function rewind() {
        if( $this->items!==null ) {
            reset($this->items);
        }
    }
    /**
     * @see \Iterator
     * @return bool
     */
    public function valid()	{
        if( $this->items===null ) {
            return false;
        }
        return key($this->items)!==null;
    }

    /**
     *
     * @return array
     */
    public function __sleep() {
        $this->validateKeys();

        return array('item_class_name', 'items');
    }

    /**
     *
     */
    public function __wakeup() {
        if(!$this->items) {
            $this->items = array();
            $this->deleted_items = array();
        } else {
            $this->validateKeys();
        }
    }

    /**
     *
     */
    protected function validateKeys() {
        if(!$this->items) {
            return;
        }
        $items = array();
        foreach($this->items as $key=>$item) {

            $new_key = $item->getArrayKeyValue();
            $key = $new_key!==null ? $new_key : $key;

            if(is_object($key)) {
                $key = (string)$key;
            }

            if(!$key) {
                $items[] = $item;
            } else {
                $items[$key] = $item;
            }

        }

        $this->items = $items;

    }

}