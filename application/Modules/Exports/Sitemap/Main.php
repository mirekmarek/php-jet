<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\Exports\Sitemap;

use Jet\Application_Module;
use Jet\MVC_Base_LocalizedData_Interface;
use Jet\MVC_Page_Interface;
use JetApplication\Exports_Definition;
use JetApplication\Exports_Generator_XML;
use JetApplication\Exports_Provider_Interface;
use JetApplication\Exports_SitemapUrlPrivoder;

/**
 *
 */
class Main extends Application_Module implements Exports_Provider_Interface
{
	protected Exports_Definition $sitemap;

	public function getExportsDefinitions(): array
	{
		$this->sitemap = new Exports_Definition(
			module: $this,
			name: 'Sitemap',
			description: '',
			export_code: 'sitemap',
			export: function( MVC_Base_LocalizedData_Interface $base ) {
				$this->generateSitemap( $base );
			}
		);
		
		$this->sitemap->setRequiresBaseDesignation( true );
		
		return [
			$this->sitemap
		];
	}
	
	public function generateSitemap( MVC_Base_LocalizedData_Interface $base ) : void
	{
		$xml = new Exports_Generator_XML( $this->sitemap, $base );
		
		$xml->start();
		$xml->tagStart('urlset', [
			'xmlns'       => 'http://www.sitemaps.org/schemas/sitemap/0.9',
			'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1',
			'xmlns:video' => 'http://www.google.com/schemas/sitemap-video/1.1'
		]);
		
		$this->generateSitemap_page( $xml, $base->getBase()->getHomepage($base->getLocale()) );
		
		$xml->tagEnd('urlset');
		$xml->done();
		
	}
	
	protected function generateSitemap_page( Exports_Generator_XML $xml, MVC_Page_Interface $page ) : void
	{
		if($page->getIsSecret()) {
			return;
		}
		
		$xml->tagStart('url');
		$xml->tagPair('loc', $page->getURL());
		$xml->tagPair('changefreq', 'weekly');
		$xml->tagEnd('url');
		
		foreach($page->getContent() as $content) {
			$c_module = $content->getModuleInstance();
			
			if($c_module instanceof Exports_SitemapUrlPrivoder) {
				$URLs = $c_module->provideSitemapURLs( $page );
				
				foreach($URLs as $URL) {
					$xml->tagStart('url');
					$xml->tagPair('loc', $URL );
					$xml->tagPair('changefreq', 'weekly');
					$xml->tagEnd('url');
				}
			}
		}
		
		foreach($page->getChildren() as $s_page) {
			$this->generateSitemap_page( $xml, $s_page );
		}
	}
}