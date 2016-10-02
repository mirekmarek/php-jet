<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

interface DataModel_Related_MtoN_Iterator_Interface extends \ArrayAccess, \Iterator, \Countable, DataModel_Related_Interface   {


	/**
	 * @param DataModel_Definition_Model_Related_MtoN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_MtoN $item_definition );

	/**
	 * @param array $where
	 */
	public function setLoadRelatedDataWhereQueryPart(array $where);

	/**
	 * @return array
	 */
	public function getLoadRelatedDataWhereQueryPart();

	/**
	 * @param array $order_by
	 */
	public function setLoadRelatedDataOrderBy(array $order_by);

	/**
	 * @return array
	 */
	public function getLoadRelatedDataOrderBy();

	/**
	 *
	 */
	public function removeAllItems();

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function addItems( $N_instances );

	/**
	 * @param DataModel[] $N_instances
	 *$this->_items
	 * @throws DataModel_Exception
	 */
	public function setItems( $N_instances );

	/**
	 * @return DataModel_ID_Abstract[]
	 */
	public function getIDs();

}