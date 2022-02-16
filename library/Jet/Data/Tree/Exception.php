<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Data_Tree_Exception extends Exception
{

	const CODE_INCONSISTENT_TREE_DATA = 1;
	const CODE_TREE_ALREADY_IN_FOREST = 2;

	const CODE_NODE_ALREADY_EXISTS = 3;
	const CODE_MISSING_VALUE = 4;

}