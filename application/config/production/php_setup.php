<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */

error_reporting(E_ALL | E_STRICT);

set_include_path(
	JET_LIBRARY_PATH
	.PATH_SEPARATOR
	.get_include_path()
);

if(!ini_get('date.timezone')){
	date_default_timezone_set('UTC');
}

ini_set( 'default_charset', JET_CHARSET );