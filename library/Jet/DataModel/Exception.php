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

	public const CODE_DEFINITION_NONSENSE = 200;
	public const CODE_NOTHING_TO_DELETE = 300;

	public const CODE_INVALID_CLASS = 1500;

	public const CODE_UNKNOWN_PROPERTY = 2000;
}