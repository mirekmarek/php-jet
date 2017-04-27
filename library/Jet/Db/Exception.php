<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Db_Exception
 * @package Jet
 */
class Db_Exception extends Exception {

	const CODE_UNKNOWN_CONNECTION = 1;
	const CODE_CONFIG_ERROR = 3;

}