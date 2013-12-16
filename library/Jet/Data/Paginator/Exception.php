<?php
/**
 *
 *
 *
 * Tree exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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