<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Mvc_Router_Exception extends Exception
{
	const CODE_URL_NOT_DEFINED = 20;
	const CODE_UNABLE_TO_PARSE_URL = 33;
	const CODE_UNKNOWN_SCHEME = 40;
}