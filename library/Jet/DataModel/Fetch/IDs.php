<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Fetch_IDs extends DataModel_Fetch
{

	/**
	 *
	 * @return array
	 */
	public function toArray(): array
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
	protected function _fetch(): void
	{
		if( $this->data !== null ) {
			return;
		}

		$this->data = [];

		$ids = DataModel_Backend::get( $this->data_model_definition )->fetchAll( $this->query );

		foreach( $ids as $id_data ) {
			$id = clone $this->empty_id_instance;

			foreach( $id->getPropertyNames() as $k ) {
				$id->setValue( $k, $id_data[$k] );
			}

			$this->data[(string)$id] = $id;
		}
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel_IDController
	 */
	protected function _get( mixed $item ): DataModel_IDController
	{
		return $item;
	}

}