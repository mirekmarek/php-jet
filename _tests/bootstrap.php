<?php
if(!ini_get("date.timezone")){
	date_default_timezone_set("Europe/Prague");
}

set_include_path(
	getcwd()
		.PATH_SEPARATOR
		.get_include_path()
);

$lib_dir = dirname(__DIR__)."/library/";

spl_autoload_register( function( $class_name ) {
	global $lib_dir;

	if(
		substr($class_name, 0, 4)!="Jet\\"
	) {
		return false;
	}

	$class_name = str_replace( "\\", DIRECTORY_SEPARATOR, $class_name );
	$class_name = str_replace( "_", DIRECTORY_SEPARATOR, $class_name );

	require $lib_dir.$class_name.".php";

	return true;
}, true, true );

require "_mock/Jet/Object.php";
