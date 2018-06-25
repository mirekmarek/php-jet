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
use JetApplication\Application_Admin;
use JetApplication\Application_REST;

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
		self::ACTION_GET_ARTICLE    => 'Get article(s) data',
		self::ACTION_ADD_ARTICLE    => 'Add new article',
		self::ACTION_UPDATE_ARTICLE => 'Update article',
		self::ACTION_DELETE_ARTICLE => 'Delete article',
	];

}