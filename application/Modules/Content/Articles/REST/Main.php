<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\REST;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ACTION_GET_ARTICLE = 'get_article';
	const ACTION_ADD_ARTICLE = 'add_article';
	const ACTION_UPDATE_ARTICLE = 'update_article';
	const ACTION_DELETE_ARTICLE = 'delete_article';

}