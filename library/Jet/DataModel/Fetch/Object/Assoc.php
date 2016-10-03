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
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

class DataModel_Fetch_Object_Assoc extends DataModel_Fetch_Object_Abstract implements Data_Paginator_DataSource_Interface,\ArrayAccess, \Iterator, \Countable  {

    /**
     * @var array|DataModel_Load_OnlyProperties
     */
    protected $load_only_properties;

    /**
     * @param array|DataModel_Load_OnlyProperties $load_only_properties
     */
    public function setLoadOnlyProperties($load_only_properties)
    {
        $this->load_only_properties = $load_only_properties;
    }

    /**
     * @return array|DataModel_Load_OnlyProperties
     */
    public function getLoadOnlyProperties()
    {
        return $this->load_only_properties;
    }

	/**
	 * @see Countable
	 *
	 * @return int
	 */
	public function count() {
		return $this->getCount();
	}


	/**
	 * @see ArrayAccess
	 * @param int $offset
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		$this->_fetch();
		return array_key_exists($offset, $this->data);
	}
	/**
	 * @see ArrayAccess
	 * @param int $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset ) {
		$this->_fetch();
		return $this->_get($offset);
	}

	/**
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @see ArrayAccess
	 * @param int $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset , $value ) {}

	/**
	 * @see ArrayAccess
	 * @param int $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_fetch();
		unset( $this->data[$offset] );
		foreach($this->IDs as $i=>$ID) {
			if((string)$ID==$offset) {
				unset($this->IDs[$i]);
				break;
			}
		}
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		$this->_fetch();

		return $this->_get($this->IDs[$this->iterator_position]);
	}
	/**
	 * @see Iterator
	 * @return string
	 */
	public function key() {
		$this->_fetch();
		return (string)$this->IDs[$this->iterator_position];
	}
	/**
	 * @see Iterator
	 */
	public function next() {
		$this->_fetch();
		++$this->iterator_position;
	}
	/**
	 * @see Iterator
	 */
	public function rewind() {
		$this->_fetch();
		$this->iterator_position=0;
	}
	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid()	{
		$this->_fetch();
		return isset( $this->IDs[$this->iterator_position] );
	}

    /**
     * @param DataModel_ID_Abstract|string $ID
     * @return DataModel
     */
    protected function _get( $ID ) {
        $s_ID = (string)$ID;
        if(isset($this->data[$s_ID])) {
            return $this->data[$s_ID];
        }

        $class_name = $this->data_model_definition->getClassName();

        /**
         * @var DataModel $class_name
         */
        $this->data[$s_ID] = $class_name::load( $ID, $this->getLoadOnlyProperties() );

        return $this->data[$s_ID];
    }
}