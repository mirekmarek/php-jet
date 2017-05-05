<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Application_Modules_Exception
 * @package Jet
 */
class Application_Modules_Exception extends Exception
{

	const CODE_MODULE_NAME_FORMAT_IS_NOT_VALID = 1;
	const CODE_MODULE_DOES_NOT_EXIST = 2;
	const CODE_MANIFEST_IS_NOT_READABLE = 3;
	const CODE_MANIFEST_NONSENSE = 4;


	const CODE_MODULE_IS_NOT_COMPATIBLE = 99999;

	const CODE_MODULE_ALREADY_INSTALLED = 5;
	const CODE_MODULE_IS_NOT_INSTALLED = 6;

	const CODE_FAILED_TO_INSTALL_MODULE = 7;
	const CODE_FAILED_TO_UNINSTALL_MODULE = 8;

	const CODE_UNKNOWN_MODULE = 9;

	const CODE_ERROR_CREATING_MODULE_INSTANCE = 10;

	const CODE_DEPENDENCIES_ERROR = 11;
	const CODE_MODULES_LIST_NOT_FOUND = 12;
	const CODE_MODULES_LIST_CORRUPTED = 13;

	const CODE_INVALID_MODULE_CONFIG_CLASS = 1000;

	const CODE_UNKNOWN_ACL_ACTION = 5000;
}