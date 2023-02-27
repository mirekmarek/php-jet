<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\UI\Admin;

use Jet\MVC_Controller_Default;


/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{
	/**
	 *
	 */
	public function default_Action(): void
	{
		$this->output( 'default' );
	}

	/**
	 *
	 */
	public function breadcrumb_navigation_Action(): void
	{
		$this->output( 'breadcrumb_navigation' );
	}

	/**
	 *
	 */
	public function messages_Action(): void
	{
		$this->output( 'messages' );
	}

	/**
	 *
	 */
	public function main_menu_Action(): void
	{
		$this->output( 'main_menu' );
	}
	
	public function select_locale_Action() : void
	{
		$this->output( 'select-locale' );
	}
	
}