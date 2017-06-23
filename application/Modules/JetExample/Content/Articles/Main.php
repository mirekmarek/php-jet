<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Articles;

use Jet\Application_Module;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc;

use JetApplication\Application;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'articles';

	const ACTION_GET_ARTICLE = 'get_article';
	const ACTION_ADD_ARTICLE = 'add_article';
	const ACTION_UPDATE_ARTICLE = 'update_article';
	const ACTION_DELETE_ARTICLE = 'delete_article';

	/**
	 * @var array
	 */
	protected $ACL_actions = [
		self::ACTION_GET_ARTICLE    => 'Get article(s) data', self::ACTION_ADD_ARTICLE => 'Add new article',
		self::ACTION_UPDATE_ARTICLE => 'Update article', self::ACTION_DELETE_ARTICLE => 'Delete article',
	];

	/**
	 * @var Controller_Admin_Main_Router
	 */
	protected $admin_controller_router;


	/**
	 * Returns module views directory
	 *
	 * @return string
	 */
	public function getViewsDir()
	{
		$dir = parent::getViewsDir();

		if( Mvc::getCurrentSite()->getId()==Application::getAdminSiteId() ) {
			return $dir.'admin/';
		} else {
			return $dir.'web/';
		}
	}

	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 *
	 * @return string
	 */
	public function getControllerClassName( Mvc_Page_Content_Interface $content )
	{
		$controller_name = 'Main';

		if( $content->getCustomController() ) {
			$controller_name = $content->getCustomController();
		}

		switch( Mvc::getCurrentSite()->getId() ) {
			case Application::getAdminSiteId():
				$controller_suffix = 'Controller_Admin_'.$controller_name;
				break;
			case Application::getRESTSiteId():
				$controller_suffix = 'Controller_REST_'.$controller_name;
				break;
			default:
				$controller_suffix = 'Controller_Web_'.$controller_name;
				break;
		}


		$controller_class_name = $this->module_manifest->getNamespace().$controller_suffix;

		return $controller_class_name;
	}
}