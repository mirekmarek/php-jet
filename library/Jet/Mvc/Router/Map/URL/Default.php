<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

/**
 */
class Mvc_Router_Map_URL_Default extends Mvc_Router_Map_URL_Abstract {


	/**
	 * @param Mvc_Pages_Page_Abstract $page
	 * @return void
	 */
	public function takePageData( Mvc_Pages_Page_Abstract $page ) {
		$this->page_ID = $page->getID();
		$this->page_parent_ID = $page->getParentID();
		$this->page_title = $page->getTitle();
		$this->page_menu_title = $page->getMenuTitle();
		$this->page_breadcrumb_title = $page->getBreadcrumbTitle();
		$this->page_authentication_required = $page->getAuthenticationRequired();
		$this->page_SSL_required = $page->getSSLRequired();
	}


}
