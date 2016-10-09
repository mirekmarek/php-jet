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
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

class DataModel_Fetch_Object_IDs extends DataModel_Fetch_Object_Abstract implements \ArrayAccess, \Iterator, \Countable  {

	/**
	 * @param $item
	 * @return DataModel_ID_Abstract
	 */
	protected function _get( $item ) {
		return $item;
	}

	/**
	 *
	 */
	public function _fetch() {
		if($this->data!==null) {
			return;
		}

		$this->data = [];

		$backend = $this->data_model_definition->getBackendInstance();

		$pm = $backend->getDataPaginationMode();
		$backend->setDataPaginationMode( $this->pagination_enabled );

		$l = $backend->fetchAll( $this->query );

		$backend->setDataPaginationMode($pm);

		foreach( $l as $item ) {
			$l_ID = clone $this->empty_ID_instance;

			foreach($l_ID as $k=>$v) {
				$l_ID[$k] = $item[$k];
			}

			$this->data[(string)$l_ID] = $l_ID;
		}
	}

	/**
	 *
	 * @return array
	 */
	public function toArray() {
		$this->_fetch();

		$result = [];

		foreach($this->data as $ID ) {
			$result[] = (string)$ID;
		}

		return $result;

	}

}