<?php
namespace Jet;
error_reporting(E_ALL | E_STRICT);

define("JET_APPLICATION_ENVIRONMENT", "devel");
define("JET_DEBUG_MODE", true);


define("JET_BASE_PATH", dirname(__DIR__)."/");
define("JET_DATA_PATH", JET_BASE_PATH."data/");
define("JET_LIBRARY_PATH", JET_BASE_PATH."library/");
define("JET_LOGS_PATH", JET_BASE_PATH."logs/");
define("JET_TMP_PATH", JET_BASE_PATH."tmp/");

define("JET_APPLICATION_PATH", __DIR__."/");
define("JET_APPLICATION_ERROR_PAGES_PATH", JET_APPLICATION_PATH."error_pages/");
define("JET_APPLICATION_CONFIG_PATH", JET_APPLICATION_PATH."config/");
define("JET_APPLICATION_MODULES_PATH", JET_APPLICATION_PATH."modules/");
define("JET_APPLICATION_SITES_PATH", JET_APPLICATION_PATH."sites/");

define("JET_TEMPLATES_PATH", JET_BASE_PATH."templates/");
define("JET_TEMPLATES_SITES_PATH", JET_TEMPLATES_PATH."sites/");
define("JET_TEMPLATES_MODULES_PATH", JET_TEMPLATES_PATH."modules/");

define("JET_PUBLIC_PATH", JET_BASE_PATH."public/");
define("JET_PUBLIC_IMAGES_PATH", JET_BASE_PATH."public/images/");
define("JET_PUBLIC_SCRIPTS_PATH", JET_BASE_PATH."public/scripts/");
define("JET_PUBLIC_STYLES_PATH", JET_BASE_PATH."public/styles/");
define("JET_PUBLIC_LIBS_PATH", JET_BASE_PATH."public/libs/");


if(php_sapi_name()!="cli") {
	$base_URI = null;

	$request_URI = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "/";
	list($request_URI) = explode("?", $request_URI);

	$base_URI = "/";
	$URI_path_parts = explode( "/", ltrim( $request_URI, "/" ) );
	$got_base_URI = false;

	while($URI_path_parts){

		$bootstrap_path = $_SERVER["DOCUMENT_ROOT"] . $base_URI . "application/bootstrap.php";
		if( file_exists($bootstrap_path) ){
			$got_base_URI = true;
			break;
		}
		$base_URI .= array_shift($URI_path_parts) . "/";
	}

	if(!$got_base_URI){
		die("Unable to determine base URI...");
	}

	define("JET_BASE_URI", $base_URI);

	define("JET_MODULES_URI", JET_BASE_URI . "application/modules/");
	define("JET_SITES_URI", JET_BASE_URI . "application/sites/");

	define("JET_PUBLIC_URI", JET_BASE_URI . "public/");
	define("JET_PUBLIC_FILES_URI", JET_PUBLIC_URI . "files/");
	define("JET_PUBLIC_DATA_URI", JET_PUBLIC_URI . "data/");
	define("JET_PUBLIC_IMAGES_URI", JET_PUBLIC_URI . "images/");
	define("JET_PUBLIC_SCRIPTS_URI", JET_PUBLIC_URI . "scripts/");
	define("JET_PUBLIC_STYLES_URI", JET_PUBLIC_URI . "styles/");
}

if(!ini_get("date.timezone")){
	date_default_timezone_set("Europe/Prague");
}
set_include_path(
	JET_LIBRARY_PATH
		.PATH_SEPARATOR
		.get_include_path()
);
