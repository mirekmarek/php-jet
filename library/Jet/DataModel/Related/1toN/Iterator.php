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

class DataModel_Related_1toN_Iterator implements \ArrayAccess, \Iterator, \Countable, Object_Serializable_REST   {

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
     * @param $item_class_name
     */
    public function __construct( $item_class_name ) {
        $this->item_class_name = $item_class_name;
    }


    /**
     * Returns model definition
     *
     *
     * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
     */
    public function getDataModelDefinition()  {

        return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->item_class_name );
    }

    /**
     * Returns backend instance
     *
     * @return DataModel_Backend_Abstract
     */
    public function getBackendInstance() {
        return $this->getDataModelDefinition()->getBackendInstance();
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

        $data = $backend->fetchAll( $query );

        $this->items = array();

        if(!$data) {
            return $this;
        }

        $class_name = $this->item_class_name;

        foreach($data as $dat) {

            /**
             * @var DataModel_Related_1toN $loaded_instance
             */
            /** @noinspection PhpUndefinedMethodInspection */
            $loaded_instance = $class_name::createInstance( $dat, $main_model_instance );

            /**
             * @var DataModel_Related_1toN $loaded_instance
             */
            $key = $loaded_instance->getArrayKeyValue();
            if(is_object($key)) {
                $key = (string)$key;
            }

            if($key!==null) {
                $this->items[$key] = $loaded_instance;
            } else {
                $this->items[] = $loaded_instance;
            }
        }

        $this->deleted_items = array();

        return $this;
    }

    /**
     * @param DataModel $main_model_instance
     * @param DataModel_Related_Abstract $parent_model_instance
     */
    public function wakeUp( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {
        foreach( $this->items as $item ) {
            $item->wakeUp( $main_model_instance, $parent_model_instance );
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
     * @param DataModel $main_model_instance (optional)
     * @param DataModel_Related_Abstract $parent_model_instance (optional)
     *
     * @throws Exception
     * @throws DataModel_Exception
     */
    public function saveRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null ) {

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

        if($parent_model_instance) {
            foreach($this->items as $d) {
                $d->saveRelated( $main_model_instance, $parent_model_instance );
            }
        } else {
            foreach($this->items as $d) {
                $d->saveRelated( $main_model_instance );
            }
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