<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Mvc_Page_Content_Interface;
use Jet\Application_Module;
use Jet\Mvc;

/**
 *
 */
class Main extends Application_Module
{
	//TODO: bezna vycejazycnost
	//TODO: hledani


	const ADMIN_MAIN_PAGE = 'admin/images';

	const ACTION_GET_GALLERY = 'get_gallery';
	const ACTION_ADD_GALLERY = 'add_gallery';
	const ACTION_UPDATE_GALLERY = 'update_gallery';
	const ACTION_DELETE_GALLERY = 'delete_gallery';

	const ACTION_GET_IMAGE = 'get_image';
	const ACTION_ADD_IMAGE = 'add_image';
	const ACTION_UPDATE_IMAGE = 'update_image';
	const ACTION_DELETE_IMAGE = 'delete_image';

	/**
	 * @var array
	 */
	protected $ACL_actions = [
		self::ACTION_GET_GALLERY    => 'Get article(s) data', self::ACTION_ADD_GALLERY => 'Add new gallery',
		self::ACTION_UPDATE_GALLERY => 'Update gallery', self::ACTION_DELETE_GALLERY => 'Delete gallery',

		self::ACTION_GET_IMAGE    => 'Get image(s) data', self::ACTION_ADD_IMAGE => 'Add new image',
		self::ACTION_UPDATE_IMAGE => 'Update image', self::ACTION_DELETE_IMAGE => 'Delete image',
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

		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $dir.'admin/';
		} else {
			return $dir.'site/';
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

		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			$controller_suffix = 'Controller_Admin_'.$controller_name;

		} else {
			$controller_suffix = 'Controller_Site_'.$controller_name;
		}

		$controller_class_name = $this->module_manifest->getNamespace().$controller_suffix;

		return $controller_class_name;
	}

}