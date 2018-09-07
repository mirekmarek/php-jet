<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Exception extends Exception
{

	const CODE_DEFINITION_NONSENSE = 200;
	const CODE_NOTHING_TO_DELETE = 300;

	const CODE_INVALID_CLASS = 1500;

	const CODE_UNKNOWN_PROPERTY = 2000;
}