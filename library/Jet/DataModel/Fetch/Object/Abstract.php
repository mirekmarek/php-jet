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
 * @subpackage DataModel_Fetch
 */
namespace Jet;

abstract class DataModel_Fetch_Object_Abstract extends DataModel_Fetch_Abstract {

	/**
	 *
	 * @var DataModel_ID_Abstract
	 */
	protected $empty_ID_instance;


	/**
	 * Data items 
	 * 
	 * @var DataModel_ID_Abstract[]
	 */
	protected $IDs = null;

	/**
	 * @var DataModel[]
	 */
	protected $data = [];

	/**
	 * Internal iterator position index
	 *
	 * @var int
	 */
	protected $iterator_position = 0;

	/**
	 *
	 * @param array|DataModel_Query $query
	 *
	 * @throws DataModel_Query_Exception
	 */
	final public function __construct( DataModel_Query $query ) {

		parent::__construct($query);
		$load_properties = [];
		//$group_by = array();

		foreach( $this->data_model_definition->getIDProperties() as $property_definition ) {
			$load_properties[] = $property_definition;
			//$group_by[] = $property_definition->getName();
		}

		$this->query->setSelect($load_properties);
		//$this->query->setGroupBy($group_by);

		$this->empty_ID_instance = $this->data_model_definition->getEmptyIDInstance();
	}


	/**
	 * @return array
	 */
	public function jsonSerialize() {
		$result = [];

		foreach($this as $val) {
			/**
			 * @var DataModel $val
			 */
			$result[] = $val->jsonSerialize();
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function toXML() {
		$model_name = $this->data_model_definition->getModelName();

		$result = '';
		$result .= '<list model_name="'.$model_name.'">'.JET_EOL;

		foreach($this as $val) {
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
	public function toJSON() {
		return json_encode($this->jsonSerialize());
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$result = [];

		foreach($this as $key=>$val) {
			/**
			 * @var DataModel $val
			 */
			$result[$key] = $val->jsonSerialize();
		}

		return $result;
	}


	/**
	 * Fetches IDs...
	 *
	 */
	public function _fetch() {
		if($this->IDs!==null) {
			return;
		}

		$this->IDs = [];

		$backend = $this->data_model_definition->getBackendInstance();

		$pm = $backend->getDataPaginationMode();
		$backend->setDataPaginationMode( $this->pagination_enabled );

		$l = $backend->fetchAll( $this->query );

		$backend->setDataPaginationMode($pm);

		foreach( $l as $ID ) {
			$l_ID = clone $this->empty_ID_instance;

			foreach($ID as $k=>$v) {
				$l_ID[$k] = $v;
			}
			$this->IDs[] = $l_ID;
		}

		foreach($this->IDs as $ID) {
			$this->data[(string)$ID] = null;
		}
	}


}