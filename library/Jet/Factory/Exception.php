<?php
/**
 *
 *
 *
 * Factory exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Factory
 * @subpackage Factory_Exception
 */

namespace Jet;


class Factory_Exception extends Exception {

	const CODE_INVALID_CALLBACK = 1;
	const CODE_TOO_MANY_ARGUMENTS = 2;
	const CODE_INVALID_CLASS_INSTANCE = 3;
	const CODE_MISSING_INSTANCEOF_CLASS_NAME = 4;

}