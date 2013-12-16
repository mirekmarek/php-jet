<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Jet\Articles
 */
namespace JetApplicationModule\Jet\Images;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	protected $ACL_actions = array(
		"get_gallery" => "Get article(s) data",
		"add_gallery" => "Add new gallery",
		"update_gallery" => "Update gallery",
		"delete_gallery" => "Delete gallery",
		"get_image" => "Get image(s) data",
		"add_image" => "Add new image",
		"update_image" => "Update image",
		"delete_image" => "Delete image",
	);
}