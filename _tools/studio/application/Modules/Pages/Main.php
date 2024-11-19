<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Pages;

use Jet\Data_Tree;
use Jet\Factory_MVC;
use Jet\Http_Request;
use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Base_Interface;
use Jet\MVC_Page_Interface;
use Jet\SysConf_Jet_MVC;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Manifest;
use JetStudio\JetStudio_Module_Service_Pages;

class Main extends JetStudio_Module implements JetStudio_Module_Service_Pages
{
	protected static bool $plugin_mode = false;
	protected string $plugin_output = '';
	protected static MVC_Base_Interface|bool|null $current_base = null;
	protected static Locale|bool|null $current_locale = null;
	protected static Page|bool|null $current_page = null;
	
	public function __construct( JetStudio_Module_Manifest $manifest )
	{
		$this->manifest = $manifest;
		
		Factory_MVC::setPageClassName( Page::class );
		Factory_MVC::setPageContentClassName( Page_Content::class );
		SysConf_Jet_MVC::setUseNonActiveModulePages( true );
	}
	
	public function editPage( MVC_Page_Interface $page ): string
	{
		static::$plugin_mode = true;
		
		Main::setCurrentPage( $page->getId(), $page->getLocale(), $page->getBaseId() );
		
		$this->getView()->setVar('plugin_mode', true);
		
		$this->handle();
		
		return $this->plugin_output;
	}
	
	public function output( string $view_script ) : void
	{
		if(!static::$plugin_mode) {
			parent::output( $view_script );
			return;
		}
		
		$this->plugin_output = $this->getView()->render( $view_script );
		
	}
	
	
	public static function getActionUrl( string  $action,
	                                     array   $custom_get_params = [],
	                                     ?string $custom_page_id = null,
	                                     ?string $custom_locale = null,
	                                     ?string $custom_base_id = null ) : string
	{
		
		$get_params = [];
		
		if( static::getCurrentBaseId() ) {
			$get_params['base'] = static::getCurrentBaseId();
		}
		if( static::getCurrentLocale() ) {
			$get_params['locale'] = (string)static::getCurrentLocale();
		}
		if( static::getCurrentPageId() ) {
			$get_params['page'] = (string)static::getCurrentPageId();
			
			$get_params['what'] = static::whatToEdit();
		}
		
		if( $custom_base_id !== null ) {
			$get_params['base'] = $custom_base_id;
			if( !$custom_base_id ) {
				unset( $get_params['base'] );
			}
		}
		
		if( $custom_locale !== null ) {
			$get_params['locale'] = $custom_locale;
			if( !$custom_locale ) {
				unset( $get_params['locale'] );
			}
		}
		
		if( $custom_page_id !== null ) {
			$get_params['page'] = $custom_page_id;
			if( !$custom_page_id ) {
				unset( $get_params['page'] );
			}
		}
		
		if( $action ) {
			$get_params['pages_action'] = $action;
		}
		
		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}
		
		if(static::$plugin_mode) {
			return Http_Request::currentURL( set_GET_params: $get_params );
		}
		
		return JetStudio::getModuleManifest('Pages')->getURL().'?'.http_build_query( $get_params );
	}
	
	
	public static function getPage( string $page_id, Locale|null $locale = null, string $base_id = '' ): null|Page
	{
		if( !$base_id ) {
			$base_id = static::getCurrentBaseId();
		}
		
		if( !$locale ) {
			$locale = static::getCurrentLocale();
		}
		
		return Page::_get( $page_id, $locale, $base_id );
	}
	
	public static function getCurrentBaseId(): string|bool
	{
		if( static::getCurrentBase() ) {
			return static::getCurrentBase()->getId();
		}
		
		return false;
	}
	
	public static function getCurrentBase(): bool|MVC_Base_Interface
	{
		if( static::$current_base === null ) {
			$id = Http_Request::GET()->getString( 'base' );
			
			static::$current_base = false;
			
			if(
				$id &&
				($base = MVC::getBase( $id ))
			) {
				static::$current_base = $base;
			}
		}
		
		return static::$current_base;
	}
	
	public static function getCurrentLocale(): bool|Locale
	{
		if( static::$current_locale === null ) {
			$locale = Http_Request::GET()->getString( 'locale' );
			
			static::$current_locale = false;
			
			if(
				$locale &&
				($locale = new Locale( $locale )) &&
				static::getCurrentBase() &&
				static::getCurrentBase()->getHasLocale( $locale )
			) {
				static::$current_locale = $locale;
			}
		}
		
		return static::$current_locale;
	}
	
	public static function getCurrentPageId(): string|bool
	{
		if( static::getCurrentPage() ) {
			return static::getCurrentPage()->getId();
		}
		
		return false;
	}
	
	public static function getCurrentPage(): bool|Page
	{
		if( static::$current_page === null ) {
			$base_id = static::getCurrentBaseId();
			$locale = static::getCurrentLocale();
			
			$page_id = Http_Request::GET()->getString( 'page' );
			
			static::$current_page = false;
			
			if(
				$base_id &&
				$locale &&
				$page_id &&
				($page = static::getPage( $page_id, $locale, $base_id ))
			) {
				static::$current_page = $page;
			}
		}
		
		return static::$current_page;
	}
	
	public static function setCurrentPage( string $page_id, Locale $locale, string $base_id ) : void
	{
		$page = static::getPage( $page_id, $locale, $base_id );
		if(!$page) {
			$page = false;
		}
		
		static::$current_page = $page;
		static::$current_locale = $locale;
		static::$current_base = MVC::getBase( $base_id );
	}
	
	
	public static function getCurrentPageTree(): Data_Tree
	{
		
		$tree_data = [];
		
		
		$appendNode = function( Page $page ) use ( &$tree_data, &$appendNode ) {
			$parent = $page->getParent();
			if( $parent ) {
				$tree_data[] = [
					'id' => $page->getId(),
					'parent_id' => $parent->getId(),
					'name' => $page->getName(),
					'module_name' => $page->getSourceModuleName(),
				];
			}
			
			
			foreach( $page->getChildren() as $ch ) {
				/**
				 * @var Page $ch
				 */
				$appendNode( $ch );
			}
		};
		
		/**
		 * @var Page $homepage
		 */
		
		$homepage = static::getCurrentBase()->getHomepage( static::getCurrentLocale() );
		$appendNode( $homepage );
		
		$tree = new Data_Tree();
		$root = $tree->getRootNode();
		$root->setId( $homepage->getId() );
		$root->setLabel( $homepage->getName() );
		
		uasort( $tree_data, function( array $a, array $b ) {
			return strcmp( $a['name'], $b['name'] );
		} );
		
		$tree->setData( $tree_data );
		
		
		return $tree;
		
	}
	
	public static function exists( string $page_id, ?Locale $locale = null, string $base_id = '' ): bool
	{
		
		$page = static::getPage( $page_id, $locale, $base_id );
		if( $page ) {
			return true;
		}
		
		
		return false;
	}
	
	public static function whatToEdit(): string
	{
		if( !static::getCurrentPageId() ) {
			return '';
		}
		return Http_Request::GET()->getString( 'what', 'main', [
			'main',
			'content',
			'static_content',
			'callback'
		] );
	}
}
