<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Exception
 * @package Jet
 */
class DataModel_Exception extends Exception {


	const CODE_INVALID_PROPERTY_TYPE = 101;

	const CODE_DEFINITION_NONSENSE = 200;
	const CODE_NOTHING_TO_DELETE = 300;

	const CODE_UNDEFINED_ID_PART = 400;

	const CODE_INVALID_CLASS = 1500;

	const CODE_PERMISSION_DENIED = 9000;

	const CODE_UNKNOWN_PROPERTY = 2000;
}