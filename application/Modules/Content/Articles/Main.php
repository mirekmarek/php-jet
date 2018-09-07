<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Articles;

use Jet\Application_Module;

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

}