<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

class Application_UI {
	public static function search( string $id, string $placeholder, string $search_action, string $search_reset_action ) : string
	{
		$view = Application::getGeneralView();
		$view->setVar('id', $id);
		$view->setVar('placeholder', $placeholder);
		$view->setVar('search_action', $search_action);
		$view->setVar('search_reset_action', $search_reset_action);
		
		return $view->render('ui/search');
	}
}