<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Fetch_Object_Abstract
 * @package Jet
 */
abstract class DataModel_Fetch_Object extends DataModel_Fetch
{

	/**
	 *
	 * @var DataModel_Id
	 */
	protected $empty_id_instance;


	/**
	 *
	 * @var array
	 */
	protected $data;


	/**
	 *
	 * @param array|DataModel_Query $query
	 *
	 * @throws DataModel_Query_Exception
	 */
	final public function __construct( DataModel_Query $query )
	{

		parent::__construct( $query );
		$load_properties = [];
		$group_by = array();

		foreach( $this->data_model_definition->getIdProperties() as $property_definition ) {
			$load_properties[] = $property_definition;
			$group_by[] = $property_definition->getName();
		}

		$this->query->setSelect( $load_properties );
		$this->query->setGroupBy( $group_by );

		$this->empty_id_instance = $this->data_model_definition->getEmptyIdInstance();
	}

	/**
	 * @return string
	 */
	public function toXML()
	{
		$model_name = $this->data_model_definition->getModelName();

		$result = '';
		$result .= '<list model_name="'.$model_name.'">'.JET_EOL;

		foreach( $this as $val ) {
			/**
			 * @var DataModel $val
			 */

			$result .= JET_TAB.'<item>'.JET_EOL;
			$result .= $val->toXML();
			$result .= JET_TAB.'</item>'.JET_EOL;

		}

		$result .= '</list>'.JET_EOL;

		return $result;
	}

	/**
	 * @return string
	 */
	public function toJSON()
	{
		return json_encode( $this->jsonSerialize() );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		$result = [];

		foreach( $this as $val ) {
			/**
			 * @var DataModel $val
			 */
			$result[] = $val->jsonSerialize();
		}

		return $result;
	}

	/**
	 * @return array
	 */
	abstract public function toArray();

	/**
	 * @see Countable
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->getCount();
	}

	/**
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset )
	{
		$this->_fetch();

		return array_key_exists( $offset, $this->data );
	}

	/**
	 * Do nothing - DataModel_FetchAll is readonly
	 *
	 * @see ArrayAccess
	 *
	 * @param int   $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value )
	{
	}

	/**
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset )
	{
		$this->_fetch();

		return $this->_get( $this->data[$offset] );
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel|DataModel_Id
	 */
	abstract protected function _get( $item );

	/**
	 * @see ArrayAccess
	 *
	 * @param int $offset
	 */
	public function offsetUnset( $offset )
	{
		$this->_fetch();
		unset( $this->data[$offset] );
	}

	/**
	 * @see Iterator
	 *
	 * @return DataModel
	 */
	public function current()
	{
		$this->_fetch();

		return $this->_get( current( $this->data ) );
	}

	/**
	 * @see Iterator
	 * @return string
	 */
	public function key()
	{
		$this->_fetch();

		return key( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function next()
	{
		$this->_fetch();
		next( $this->data );
	}

	/**
	 * @see Iterator
	 */
	public function rewind()
	{
		$this->_fetch();
		reset( $this->data );
	}

	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid()
	{
		$this->_fetch();

		return key( $this->data )!==null;
	}

}