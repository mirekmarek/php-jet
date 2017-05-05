<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Config_Exception
 * @package Jet
 */
class Config_Exception extends Exception
{
	const CODE_CONFIG_FILE_PATH_NOT_DEFINED = 1;
	const CODE_CONFIG_FILE_IS_NOT_READABLE = 2;
	const CODE_CONFIG_FILE_IS_NOT_VALID = 3;
	const CODE_DEFINITION_NONSENSE = 100;
	const CODE_CONFIG_CHECK_ERROR = 200;
}