<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Jet\Articles
 */
namespace JetApplicationModule\Jet\Articles;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	protected $ACL_actions = array(
		"get_article" => "Get article(s) data",
		"add_article" => "Add new article",
		"update_article" => "Update article",
		"delete_article" => "Delete article",
	);
}