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
class DataModel_Exception extends Exception
{

	const CODE_DEFINITION_NONSENSE = 200;
	const CODE_NOTHING_TO_DELETE = 300;

	const CODE_INVALID_CLASS = 1500;

	const CODE_UNKNOWN_PROPERTY = 2000;
}