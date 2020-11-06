<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface DataModel_Related_MtoN_Iterator_Interface extends DataModel_Related_Iterator_Interface
{

	/**
	 * @param DataModel_Definition_Model_Related_MtoN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_MtoN $item_definition );


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
	 * @return DataModel_IDController[]
	 */
	public function getIds();

}