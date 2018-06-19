<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Images;

use Jet\Mvc_Page_Content_Interface;
use Jet\Application_Module;
use Jet\Mvc;

use JetApplication\Application_REST;
use JetApplication\Application_Admin;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'images';

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
		self::ACTION_GET_GALLERY    => 'Get galley data',
		self::ACTION_ADD_GALLERY    => 'Add new gallery',
		self::ACTION_UPDATE_GALLERY => 'Update gallery',
		self::ACTION_DELETE_GALLERY => 'Delete gallery',

		self::ACTION_ADD_IMAGE    => 'Add new image',
		self::ACTION_UPDATE_IMAGE => 'Update image',
		self::ACTION_DELETE_IMAGE => 'Delete image',
	];

	/**
	 *
	 * @return string
	 */
	public function getViewsDir()
	{
		$dir = parent::getViewsDir();

		if( Mvc::getCurrentSite()->getId()==Application_Admin::getSiteId() ) {
			return $dir.'admin/';
		} else {
			return $dir.'web/';
		}
	}

}