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
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet\Application_Modules_Module_Abstract;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc;

class Main extends Application_Modules_Module_Abstract {
	protected $ACL_actions = [
		'get_article' => 'Get article(s) data',
		'add_article' => 'Add new article',
		'update_article' => 'Update article',
		'delete_article' => 'Delete article',
	];


	/**
	 * Returns module views directory
	 *
     * @return string
     */
	public function getViewsDir() {
		$dir = parent::getViewsDir();

		if(Mvc::getIsAdminUIRequest()) {
			return $dir.'admin/';
		} else {
			return $dir.'public/';
		}
	}

	/**
	 *
	 * @param Mvc_Page_Content_Interface $content
	 * @return string
	 */
	protected function getControllerClassName(  Mvc_Page_Content_Interface $content  ) {
		$controller_name = 'Main';

		if($content->getCustomController()) {
			$controller_name = $content->getCustomController();
		}

		if( Mvc::getIsAdminUIRequest() ) {
			$controller_suffix = 'Controller_Admin_'.$controller_name;

		} else {
			$controller_suffix = 'Controller_Public_'.$controller_name;
		}

		$controller_class_name = $this->module_manifest->getNamespace().$controller_suffix;

		return $controller_class_name;
	}

}