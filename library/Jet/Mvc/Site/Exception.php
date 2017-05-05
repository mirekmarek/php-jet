<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Site_Exception
 * @package Jet
 */
class Mvc_Site_Exception extends Exception
{

	const CODE_DATA_CHECK_FATAL_ERROR = 20;

	const CODE_URL_NOT_DEFINED = 100;
	const CODE_URL_INVALID_FORMAT = 101;
	const CODE_URL_ALREADY_ADDED = 102;

}