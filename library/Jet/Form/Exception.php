<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Form_Exception extends Exception
{
	const CODE_INVALID_FIELD_CLASS = 1;

	const CODE_VIEW_PARSE_ERROR = 10;

	const CODE_UNKNOWN_FIELD = 20;
	const CODE_UNKNOWN_FIELD_TYPE = 21;


}