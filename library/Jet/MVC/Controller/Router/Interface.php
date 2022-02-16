<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface MVC_Controller_Router_Interface
{

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function addAction( string $controller_action_name, string $module_action_name = '' ): MVC_Controller_Router_Action;

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function setDefaultAction( string $controller_action_name, string $module_action_name = '' ): MVC_Controller_Router_Action;


	/**
	 * @return MVC_Controller
	 */
	public function getController(): MVC_Controller;


	/**
	 * @return MVC_Controller_Router_Action[]
	 */
	public function getActions(): array;

	/**
	 * @param string $action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function action( string $action_name ): MVC_Controller_Router_Action;

	/**
	 * @param string $controller_action_name
	 *
	 * @return MVC_Controller_Router_Action
	 */
	public function getAction( string $controller_action_name ): MVC_Controller_Router_Action;
	/**
	 * @return MVC_Controller_Router_Action
	 */
	public function getDefaultAction(): MVC_Controller_Router_Action;

	/**
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string;
}