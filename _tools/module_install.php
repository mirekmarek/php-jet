<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;
$module_name = require "includes/modules_main.php";

echo "Installing module '{$module_name}' ... ".PHP_EOL;

try {
    Application_Modules::installModule( $module_name );
} catch(Exception $e) {
	handleException($e);
}

ok();
