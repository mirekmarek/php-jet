<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface Mvc_Controller_Router_Interface
{

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function addAction( string $controller_action_name, string $module_action_name = '' ): Mvc_Controller_Router_Action;

	/**
	 * @param string $controller_action_name
	 * @param string $module_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function setDefaultAction( string $controller_action_name, string $module_action_name = '' ): Mvc_Controller_Router_Action;


	/**
	 * @return Mvc_Controller
	 */
	public function getController(): Mvc_Controller;


	/**
	 * @return Mvc_Controller_Router_Action[]
	 */
	public function getActions(): array;

	/**
	 * @param string $action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function action( string $action_name ): Mvc_Controller_Router_Action;

	/**
	 * @param string $controller_action_name
	 *
	 * @return Mvc_Controller_Router_Action
	 */
	public function getAction( string $controller_action_name ): Mvc_Controller_Router_Action;
	/**
	 * @return Mvc_Controller_Router_Action
	 */
	public function getDefaultAction(): Mvc_Controller_Router_Action;

	/**
	 *
	 * @return bool|string
	 */
	public function resolve(): bool|string;
}