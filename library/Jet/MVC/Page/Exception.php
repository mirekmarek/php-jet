<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class MVC_Page_Exception extends Exception
{
	public const CODE_UNABLE_TO_READ_PAGE_DATA = 1;
	public const CODE_DUPLICATES_PAGE_ID = 10;
	public const CODE_DEFINITION_ERROR = 99;
}

