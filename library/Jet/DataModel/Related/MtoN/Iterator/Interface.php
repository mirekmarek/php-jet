<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public function addItems( array $N_instances ): void;

	/**
	 * @param DataModel[] $N_instances
	 *
	 * @throws DataModel_Exception
	 */
	public function setItems( array $N_instances ): void;

	/**
	 * @return DataModel_IDController[]
	 */
	public function getIds(): array;

}