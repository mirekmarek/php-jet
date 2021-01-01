<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Fetch_IDs extends DataModel_Fetch implements BaseObject_Interface_ArrayEmulator
{

	/**
	 *
	 * @return array
	 */
	public function toArray() : array
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
	protected function _fetch() : void
	{
		if( $this->data!==null ) {
			return;
		}

		$this->data = [];

		$l = DataModel_Backend::get($this->data_model_definition)->fetchAll( $this->query );

		foreach( $l as $item ) {
			$l_id = clone $this->empty_id_instance;

			foreach( $l_id->getPropertyNames() as $k ) {
				$l_id->setValue( $k, $item[$k]);
			}

			$this->data[(string)$l_id] = $l_id;
		}
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel_IDController
	 */
	protected function _get( mixed $item ) : DataModel_IDController
	{
		return $item;
	}

}