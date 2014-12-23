<?php
/**
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace Jet;


interface Application_Modules_Module_PreDispatch_Interface {
	/**
	 * @param Mvc_Router_Abstract $router
	 */
	public function resolvePreDispatch( Mvc_Router_Abstract $router );
}
