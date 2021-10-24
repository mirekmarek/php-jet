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
class Mvc_Page_Exception extends Exception
{
	const CODE_UNABLE_TO_READ_PAGE_DATA = 1;
	const CODE_DUPLICATES_PAGE_ID = 10;
	const CODE_DEFINITION_ERROR = 99;
}

