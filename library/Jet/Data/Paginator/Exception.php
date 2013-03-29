<?php
/**
 *
 *
 *
 * Tree exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Data
 * @subpackage Data_Paginator
 */
namespace Jet;

class Data_Paginator_Exception extends Exception {
	const CODE_DATA_SOURCE_IS_NOT_SET = 1;
	const CODE_INCORRECT_URL_TEMPLATE_STRING = 100;
}