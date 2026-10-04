<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Web;

use Jet\Application_Module;
use Jet\MVC_Page_Interface;
use JetApplication\Exports_SitemapUrlPrivoder;
use JetApplicationModule\Content\Articles\Entity\Article;

/**
 *
 */
class Main extends Application_Module implements Exports_SitemapUrlPrivoder
{
	
	/**
	 * @param MVC_Page_Interface $page
	 * @return array<string>
	 */
	public function provideSitemapURLs( MVC_Page_Interface $page ) : array
	{
		
		$articles = Article::getListForLocale( $page->getLocale() );
		
		$URLs = [];
		foreach($articles as $a) {
			/**
			 * @var Article $a
			 */
			$URLs[] = $page->getURL(
				path_fragments: [$a->getLocalized($page->getLocale())->getURIFragment()]
			);
		}

		return $URLs;
	}
}