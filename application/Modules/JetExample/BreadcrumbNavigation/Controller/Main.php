<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\BreadcrumbNavigation;

use Jet\Mvc_Controller_Standard;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard
{
	protected static $ACL_actions_check_map = [
		'default' => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 *
	 */
	public function initialize()
	{
	}


	public function default_Action()
	{

		$view = $this->getActionParameterValue( 'view', 'default' );

		$this->view->setVar( 'data', Navigation_Breadcrumb::getItems() );

		$this->render( $view );
	}
}