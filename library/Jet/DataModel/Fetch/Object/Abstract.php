<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	protected $data = array();

	/**
	 * Internal iterator position index
	 *
	 * @var int
	 */
	protected $iterator_position = 0;

	/**
	 *
	 * @param array|DataModel_Query $query
	 * @param DataModel $data_model
	 *
	 * @throws DataModel_Query_Exception
	 */
	final public function __construct( $query, DataModel $data_model  ) {
		if(is_array($query)) {
			$query = DataModel_Query::createQuery( $data_model, $query);
		}

		if(!$query instanceof DataModel_Query) {
			throw new DataModel_Query_Exception(
				"Query must be an instance of DataModel_Query (or valid query as array) " ,
				DataModel_Query_Exception::CODE_QUERY_NONSENSE
			);
		}

		$this->query = $query;

		$load_properties = array();
		//$group_by = array();

		foreach( $data_model->getDataModelDefinition()->getIDProperties() as $property_definition ) {
			$load_properties[] = $property_definition;
			//$group_by[] = $property_definition->getName();
		}

		$this->query->setSelect($load_properties);
		//$this->query->setGroupBy($group_by);

		$this->data_model = $data_model;
		$this->empty_ID_instance = $data_model->getEmptyIDInstance();
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$result = array();

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
	public function _fetchIDs() {
		if($this->IDs!==NULL) {
			return;
		}

		$this->IDs = array();

		foreach( $this->data_model->getBackendInstance()->fetchAll( $this->query ) as $ID ) {
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

	/**
	 * @param DataModel_ID_Abstract|string $ID
	 * @return DataModel
	 */
	protected function _get( $ID ) {
		$s_ID = (string)$ID;
		if(isset($this->data[$s_ID])) {
			return $this->data[$s_ID];
		}
		$this->data[$s_ID] = Factory::getInstance( get_class($this->data_model) );
		$this->data[$s_ID] = $this->data[$s_ID]->load( $ID );

		return $this->data[$s_ID];
	}
}