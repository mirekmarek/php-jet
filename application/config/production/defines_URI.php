<?php
/**
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
namespace Jet;

//!!!! It is better to set static value on the production system !!!
$base_URI = null;

$request_URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
list($request_URI) = explode('?', $request_URI);

$base_URI = '/';
$URI_path_parts = explode( '/', ltrim( $request_URI, '/' ) );
$got_base_URI = false;

while($URI_path_parts){

	$bootstrap_path = $_SERVER['DOCUMENT_ROOT'] . $base_URI . 'application/bootstrap.php';
	if( file_exists($bootstrap_path) ){
		$got_base_URI = true;
		break;
	}
	$base_URI .= array_shift($URI_path_parts) . '/';
}

if(!$got_base_URI){
	trigger_error('Unable to determine base URI...', E_USER_ERROR);
}
//----------------------------------------------------------------

//!!!! It is better to set static value on the production system !!!
define('JET_BASE_URI', $base_URI);

define('JET_MODULES_URI', JET_BASE_URI . 'application/modules/');
define('JET_SITES_URI', JET_BASE_URI . 'application/sites/');

define('JET_PUBLIC_URI', JET_BASE_URI . 'public/');
define('JET_PUBLIC_FILES_URI', JET_PUBLIC_URI . 'files/');
define('JET_PUBLIC_DATA_URI', JET_PUBLIC_URI . 'data/');
define('JET_PUBLIC_IMAGES_URI', JET_PUBLIC_URI . 'images/');
define('JET_PUBLIC_SCRIPTS_URI', JET_PUBLIC_URI . 'scripts/');
define('JET_PUBLIC_STYLES_URI', JET_PUBLIC_URI . 'styles/');
