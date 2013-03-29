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

abstract class DataModel_Fetch_Abstract extends Object {

	/**
	 * DataModel instance
	 *
	 * @var DataModel
	 */
	protected $data_model;

	/**
	 * Query
	 *
	 * @var DataModel_Query
	 */
	protected $query;


	/**
	 * @return DataModel_Query
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @param $related_class_name
	 *
	 * @return DataModel_Query_Relation_Item
	 */
	public function getRelation( $related_class_name ) {
		return $this->query->getRelation($related_class_name);
	}

	/**
	 * Returns query prepared by backend (SELECT x FROM y ....)
	 *
	 * @return mixed
	 */
	public function getBackendQuery() {
		return $this->data_model->getBackendInstance()->getBackendSelectQuery( $this->query );
	}

	/**
	 * Returns data count
	 *
	 * @return int
	 */
	public function getCount() {
		return $this->data_model->getBackendInstance()->getCount( $this->query );
	}
}