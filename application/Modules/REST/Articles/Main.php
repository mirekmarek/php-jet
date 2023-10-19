<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\REST\Articles;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	public const ACTION_GET_ARTICLE = 'get_article';
	public const ACTION_ADD_ARTICLE = 'add_article';
	public const ACTION_UPDATE_ARTICLE = 'update_article';
	public const ACTION_DELETE_ARTICLE = 'delete_article';

}