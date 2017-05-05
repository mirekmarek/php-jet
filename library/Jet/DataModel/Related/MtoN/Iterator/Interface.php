<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface DataModel_Related_MtoN_Iterator_Interface
 * @package Jet
 */
interface DataModel_Related_MtoN_Iterator_Interface extends DataModel_Related_Interface, \ArrayAccess, \Iterator, \Countable
{


	/**
	 * @param DataModel_Definition_Model_Related_MtoN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_MtoN $item_definition );

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
	 *
	 * @throws DataModel_Exception
	 */
	public function setItems( $N_instances );

	/**
	 * @return DataModel_Id_Abstract[]
	 */
	public function getIds();

}