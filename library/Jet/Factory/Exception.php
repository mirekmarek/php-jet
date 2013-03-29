<?php
/**
 *
 *
 *
 * Factory exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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