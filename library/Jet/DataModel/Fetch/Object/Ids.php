<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Fetch_Object_Ids
 * @package Jet
 */
class DataModel_Fetch_Object_Ids extends DataModel_Fetch_Object implements \ArrayAccess, \Iterator, \Countable
{

	/**
	 *
	 * @return array
	 */
	public function toArray()
	{
		$this->_fetch();

		$result = [];

		foreach( $this->data as $id ) {
			$result[] = (string)$id;
		}

		return $result;

	}

	/**
	 *
	 */
	public function _fetch()
	{
		if( $this->data!==null ) {
			return;
		}

		$this->data = [];

		$backend = $this->data_model_definition->getBackendInstance();

		$l = $backend->fetchAll( $this->query );

		foreach( $l as $item ) {
			$l_id = clone $this->empty_id_instance;

			foreach( $l_id as $k => $v ) {
				$l_id[$k] = $item[$k];
			}

			$this->data[(string)$l_id] = $l_id;
		}
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel_Id
	 */
	protected function _get( $item )
	{
		return $item;
	}

}