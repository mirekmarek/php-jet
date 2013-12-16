<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Signals
 */

namespace Jet;

class Application_Signals_Exception extends Exception {

	const CODE_LOOP = 1;
	const CODE_INVALID_SIGNAL_CALLBACK_ID = 2;

	const CODE_UNKNOWN_SIGNAL = 100;
	const INVALID_SIGNAL_OBJECT_CLASS = 101;

}