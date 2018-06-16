<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application as Jet_Application;
use Jet\Application_Log;
use Jet\Application_Module_Manifest_AdminDialog;
use Jet\Application_Module_Manifest_AdminSection;
use Jet\Application_Modules;
use Jet\Application_Module_Manifest;

use Jet\Mvc_Factory;
use Jet\Mvc_Site;
use Jet\Mvc_Page;
use Jet\Mvc_View;
use Jet\Mvc_Router;
use Jet\Mvc_Page_Exception;
use Jet\Mvc_Layout;

use Jet\Locale;

use Jet\Tr;

use Jet\Auth;

/**
 *
 */
class Application_Admin
{


	/**
	 * @return string
	 */
	public static function getSiteId() {
		return 'admin';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getSite() {
		return Mvc_Site::get( static::getSiteId() );
	}

	/**
	 * @param Mvc_Router $router
	 */
	public static function init( Mvc_Router $router )
	{
		Application::initErrorPages( $router );
		Application_Log::setLogger( new Application_Log_Logger_Admin() );
		Auth::setController( new Auth_Controller_Admin() );

		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */
			foreach( $manifest->getAdminSections() as $section ) {
				static::addAdminPage( $router->getLocale(), $section, $manifest );
			}

			foreach( $manifest->getAdminDialogs() as $dialog ) {
				static::addAdminDialog( $router->getLocale(), $dialog, $manifest );
			}

		}

	}


	/**
	 * @param Locale $locale
	 * @param Application_Module_Manifest_AdminSection $section
	 * @param Application_Module_Manifest $module_manifest
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminPage( Locale $locale, Application_Module_Manifest_AdminSection $section, Application_Module_Manifest $module_manifest )
	{

		$translate = function( $str, $default_value='' ) use ($module_manifest, $locale) {
			if(!$str) {
				return $default_value;
			}

			return Tr::_( $str, [], $module_manifest->getName(), $locale );
		};

		$title = $translate( $section->getTitle() ? : $module_manifest->getLabel() );

		$page_data = [
			'id' => $section->getPageId(),
			'name' => $translate( $section->getName(), $title ),
			'title' => $title,
			'menu_title' => $translate( $section->getMenuTitle(), $title ),
			'breadcrumb_title' => $translate( $section->getBreadcrumbTitle(), $title ),
			'layout_script_name' => $section->getLayoutScriptName() ? : 'default',
			'relative_path_fragment' => $section->getRelativePathFragment(),
		];


		$parent_page = static::getSite()->getHomepage( $locale );
		$page = Mvc_Page::createByData( static::getSite(), $locale, $page_data, $parent_page );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setModuleName( $module_manifest->getName() );
		$content->setControllerAction( $section->getAction() );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );


		$page->setContent( [ $content ] );

		Mvc_Page::appendPage( $page );

	}

	/**
	 * @param Locale $locale
	 * @param Application_Module_Manifest_AdminDialog $dialog
	 * @param Application_Module_Manifest $module_manifest
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminDialog( Locale $locale, Application_Module_Manifest_AdminDialog $dialog, Application_Module_Manifest $module_manifest )
	{

		$translate = function( $str, $default_value='' ) use ($module_manifest, $locale) {
			if(!$str) {
				return $default_value;
			}

			return Tr::_( $str, [], $module_manifest->getName(), $locale );
		};

		$title = $translate( $dialog->getTitle() ? : $module_manifest->getLabel() );

		$page_data = [
			'id' => 'dialog-'.$dialog->getDialogId(),
			'name' => $translate( $dialog->getName(), $title ),
			'title' => $title,
			'layout_script_name' => $dialog->getLayoutScriptName() ? : 'dialog',
			'relative_path_fragment' => 'dialog-'.$dialog->getRelativePathFragment(),
		];



		$parent_page = static::getSite()->getHomepage( $locale );

		$page = Mvc_Page::createByData( static::getSite(), $locale, $page_data, $parent_page );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setModuleName( $module_manifest->getName() );
		$content->setCustomController('Dialogs');
		$content->setControllerAction( $dialog->getAction() );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );


		$page->setContent( [ $content ] );

		Mvc_Page::appendPage( $page );

	}




	/**
	 * @param string $dialog_id
	 * @param array  $options
	 *
	 * @return null|string
	 */
	public static function requireDialog( $dialog_id, array $options=[] ) {

		$page = Mvc_Page::get('dialog-'.$dialog_id);

		if(
			!$page ||
			!$page->getContent()
		) {
			return null;
		}

		$content = $page->getContent()[0];

		$module = $content->getModuleInstance();

		if(!$module) {
			return null;
		}

		$view = new Mvc_View( $module->getViewsDir().'dialog-hooks/' );
		foreach( $options as $k=>$v ) {
			$view->setVar( $k, $v );
		}

		return $view->render( $dialog_id );

	}

}