<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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