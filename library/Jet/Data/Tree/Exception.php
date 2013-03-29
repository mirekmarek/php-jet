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
 * @category Jet
 * @package Data
 * @subpackage Data_Tree
 */
namespace Jet;

class Data_Tree_Exception extends Exception {

	const CODE_INCONSISTENT_TREE_DATA = 1;
	const CODE_TREE_ALREADY_IN_FOREST = 2;

	const CODE_NODE_ALREADY_EXISTS = 3;
	const CODE_MISSING_VALUE = 4;
	const CODE_COLLISION = 5;
	const CODE_INVALID_NODES_CLASS = 6;

}