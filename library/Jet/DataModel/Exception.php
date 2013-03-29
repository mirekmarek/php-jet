<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Exception
 */
namespace Jet;

class DataModel_Exception extends Exception {


	const CODE_INVALID_PROPERTY_TYPE = 101;

	const CODE_SAVE_ERROR_VALIDATE_DATA_FIRST = 1000;

	const CODE_DEFINITION_NONSENSE = 200;
	const CODE_NOTHING_TO_DELETE = 300;

	const CODE_UNDEFINED_ID_PART = 400;

	const CODE_INVALID_CLASS = 1500;

	const CODE_PERMISSION_DENIED = 9000;

	const CODE_UNKNOWN_PROPERTY = 2000;
}