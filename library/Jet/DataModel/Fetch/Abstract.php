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

abstract class DataModel_Fetch_Abstract extends Object implements Object_Serializable_REST, Data_Paginator_DataSource_Interface  {

	/**
	 * DataModel instance
	 *
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $data_model_definition;

	/**
	 * Query
	 *
	 * @var DataModel_Query
	 */
	protected $query;

	/**
	 * @var int
	 */
	protected $count = null;

	/**
	 * @var bool
	 */
	protected $pagination_enabled = false;

	/**
	 *
	 * @param DataModel_Query $query
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query ) {
		$this->data_model_definition = $query->getMainDataModelDefinition();

		$this->query = $query;
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 */
	public function setPagination( $limit, $offset ) {
		$this->pagination_enabled = true;
		$this->query->setLimit( $limit, $offset );
	}

	/**
	 * @return DataModel_Query
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * Returns query prepared by backend (SELECT x FROM y ....)
	 *
	 * @return mixed
	 */
	public function getBackendQuery() {
		return $this->data_model_definition->getBackendInstance()->getBackendSelectQuery( $this->query );
	}

	/**
	 * Returns data count
	 *
	 * @return int
	 */
	public function getCount() {
		if($this->count===null) {
			$this->_fetch();
			$this->count = $this->data_model_definition->getBackendInstance()->getCount( $this->query );
		}
		return $this->count;
	}

	/**
	 *
	 */
	abstract protected  function _fetch();

}