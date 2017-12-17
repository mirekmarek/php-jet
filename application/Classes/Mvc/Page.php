<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Locale;
use Jet\Mvc_Page as Jet_Mvc_Page;
use Jet\Application_Modules;
use Jet\Mvc_Page_Exception;
use Jet\Mvc_Site_Interface;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Tr;


/**
 *
 */
class Mvc_Page extends Jet_Mvc_Page
{


	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 * @throws Mvc_Page_Exception
	 */
	public static function loadCustomPages( Mvc_Site_Interface $site, Locale $locale )
	{

		parent::loadCustomPages( $site, $locale );

		if($site->getId()==Application::getAdminSiteId()) {

			foreach( Application_Modules::activatedModulesList() as $manifest ) {
				/**
				 * @var Application_Module_Manifest $manifest
				 */
				foreach( $manifest->getAdminSections() as $page_id => $page_data ) {
					static::addAdminPage( $locale, $page_id, $page_data, $manifest );
				}

				foreach( $manifest->getAdminDialogs() as $page_id => $page_data ) {
					static::addAdminDialog( $locale, $page_id, $page_data, $manifest );
				}

			}
		}


		if($site->getId()==Application::getRESTSiteId()) {
			foreach( Application_Modules::activatedModulesList() as $manifest ) {
				/**
				 * @var Application_Module_Manifest $manifest
				 */
				if($manifest->hasRestAPI()) {
					static::addRestHook( $locale, $manifest );
				}
			}
		}

	}

	/**
	 * @param Locale                      $locale
	 * @param string                      $page_id
	 * @param array                       $page_data
	 * @param Application_Module_Manifest $module_manifest
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminPage( Locale $locale, $page_id, array $page_data, Application_Module_Manifest $module_manifest )
	{

		/**
		 * @var Mvc_Page $parent_page
		 */
		$parent_page = Application::getAdminSite()->getHomepage( $locale );

		if( !isset( $page_data['layout_script_name'] ) ) {
			$page_data['layout_script_name'] = 'default';
		}

		$page_data['id'] = $page_id;

		$page_data['title'] = empty( $page_data['title'] ) ? $module_manifest->getLabel() : $page_data['title'];
		$page_data['title'] = Tr::_( $page_data['title'], [], $module_manifest->getName(), $locale );

		if(!isset($page_data['name'])) {
			$page_data['name'] = $page_data['title'];
		} else {
			$page_data['name'] = Tr::_( $page_data['name'], [], $module_manifest->getName(), $locale );
		}


		if( empty( $page_data['menu_title'] ) ) {
			$page_data['menu_title'] = $page_data['title'];
		} else {
			$page_data['menu_title'] = Tr::_( $page_data['menu_title'], [], $module_manifest->getName(), $locale );
		}

		if( empty( $page_data['breadcrumb_title'] ) ) {
			$page_data['breadcrumb_title'] = $page_data['title'];
		} else {
			$page_data['breadcrumb_title'] = Tr::_(
				$page_data['breadcrumb_title'], [], $module_manifest->getName(), $locale
			);
		}

		if( empty( $page_data['icon'] ) ) {
			$page_data['icon'] = '';
		}


		if( !isset( $page_data['action'] ) ) {
			$page_data['action'] = 'default';
		}

		$action = $page_data['action'];
		unset( $page_data['action'] );



		$page = static::createByData( Application::getAdminSite(), $locale, $page_data, $parent_page );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setModuleName( $module_manifest->getName() );
		$content->setControllerAction( $action );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );


		$page->setContent( [ $content ] );

		static::appendPage( $page );

	}

	/**
	 * @param Locale                      $locale
	 * @param string                      $page_id
	 * @param array                       $page_data
	 * @param Application_Module_Manifest $module_manifest
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminDialog( Locale $locale, $page_id, array $page_data, Application_Module_Manifest $module_manifest )
	{

		/**
		 * @var Mvc_Page $parent_page
		 */
		$parent_page = Application::getAdminSite()->getHomepage( $locale );

		if( !isset( $page_data['layout_script_name'] ) ) {
			$page_data['layout_script_name'] = 'dialog';
		}

		$page_data['id'] = 'dialog-'.$page_id;

		$page_data['title'] = empty( $page_data['title'] ) ? $module_manifest->getLabel() : $page_data['title'];
		$page_data['title'] = Tr::_( $page_data['title'], [], $module_manifest->getName(), $locale );

		if(!isset($page_data['name'])) {
			$page_data['name'] = $page_data['title'];
		} else {
			$page_data['name'] = Tr::_( $page_data['name'], [], $module_manifest->getName(), $locale );
		}

		if( empty( $page_data['icon'] ) ) {
			$page_data['icon'] = '';
		}


		if( !isset( $page_data['action'] ) ) {
			$page_data['action'] = str_replace('-', '_', $page_id);
		}

		$page_data['relative_path_fragment'] = 'dialog-'.$page_data['relative_path_fragment'];

		$action = $page_data['action'];
		unset( $page_data['action'] );



		$page = static::createByData( Application::getAdminSite(), $locale, $page_data, $parent_page );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setModuleName( $module_manifest->getName() );
		$content->setCustomController('Dialogs');
		$content->setControllerAction( $action );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );


		$page->setContent( [ $content ] );

		static::appendPage( $page );

	}



	/**
	 * @param Locale                      $locale
	 * @param Application_Module_Manifest $module_manifest
	 *
	 */
	protected static function addRestHook( Locale $locale, Application_Module_Manifest $module_manifest )
	{

		/**
		 * @var Mvc_Page $parent_page
		 */
		$parent_page = Application::getRESTSite()->getHomepage( $locale );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setModuleName( $module_manifest->getName() );
		$content->setControllerAction( false );

		$parent_page->addContent( $content );


	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( $icon )
	{
		$this->icon = $icon;
	}

}
