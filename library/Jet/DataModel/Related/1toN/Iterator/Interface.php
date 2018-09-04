<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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