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
interface DataModel_Related_1toN_Iterator_Interface extends DataModel_Related_Iterator_Interface
{


	/**
	 * @param DataModel_Definition_Model_Related_1toN $item_definition
	 */
	public function __construct( DataModel_Definition_Model_Related_1toN $item_definition );

}