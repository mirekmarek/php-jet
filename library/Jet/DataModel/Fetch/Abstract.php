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