<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Browser;

use Jet\Application_Module;
use Jet\MVC_Page_Interface;
use JetApplication\Content_Article_Localized;
use JetApplication\PageGenerator_URLProvider;

/**
 *
 */
class Main extends Application_Module implements PageGenerator_URLProvider
{
	
	public function getPageURLs( MVC_Page_Interface $page ): array
	{
		$articles = Content_Article_Localized::fetchInstances([
			'locale' => $page->getLocale()
		]);
		
		$URLs = [];
		
		$pg_count = ceil( count($articles) / 20);
		for( $p=2 ; $p<=$pg_count ; $p++) {
			$URLs[] = 'page:'.$p.'/';
		}
		
		
		foreach($articles as $article) {
			$URLs[] = $article->getURIFragment();
		}
		
		return $URLs;
	}
}