<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Application_Signals_Exception
 * @package Jet
 */
class Application_Signals_Exception extends Exception {

	const CODE_LOOP = 1;
	const CODE_INVALID_SIGNAL_CALLBACK_ID = 2;

	const CODE_UNKNOWN_SIGNAL = 100;
	const INVALID_SIGNAL_OBJECT_CLASS = 101;

}