<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\MVC_Page_Interface;

/**
 * @param MVC_Page_Interface $page
 * @return array<string>
 */
interface Exports_SitemapUrlPrivoder
{
	/**
	 * @param MVC_Page_Interface $page
	 * @return array<string>
	 */
	public function provideSitemapURLs( MVC_Page_Interface $page ) : array;
}