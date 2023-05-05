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
class Config_Exception extends Exception
{
	public const CODE_CONFIG_FILE_IS_NOT_READABLE = 2;
	public const CODE_CONFIG_FILE_IS_NOT_VALID = 3;
	public const CODE_DEFINITION_NONSENSE = 100;
	public const CODE_CONFIG_CHECK_ERROR = 200;
}