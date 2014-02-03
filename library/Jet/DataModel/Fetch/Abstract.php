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

abstract class DataModel_Fetch_Abstract extends Object {

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
	 *
	 * @param array|DataModel_Query $query
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( $query, DataModel_Definition_Model_Abstract $data_model_definition  ) {
		$this->data_model_definition = $data_model_definition;

		if(is_array($query)) {
			$query = DataModel_Query::createQuery( $this->data_model_definition, $query);
		}

		if(!$query instanceof DataModel_Query) {
			throw new DataModel_Query_Exception(
				'Query must be an instance of DataModel_Query (or valid query as array) ' ,
				DataModel_Query_Exception::CODE_QUERY_NONSENSE
			);
		}

		$this->query = $query;
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
		return $this->data_model_definition->getBackendInstance()->getCount( $this->query );
	}
}