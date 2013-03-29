<?php
/**
 *
 *
 *
 * General object exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 * @subpackage Object_Exception
 */
namespace Jet;

class Object_Exception extends Exception {

	const CODE_UNDEFINED_PROPERTY = 1;
	const CODE_ACCESS_PROTECTED_PROPERTY = 2;


}