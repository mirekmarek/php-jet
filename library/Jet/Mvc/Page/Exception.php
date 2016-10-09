<?php
/**
 *
 *
 *
 * Router handle exception
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Page_Exception extends Exception {
    const CODE_UNABLE_TO_READ_PAGE_DATA = 1;
	const CODE_DUPLICATES_PAGE_ID = 10;
}	const CODE_PAGE_DATA_ERROR = 100;
