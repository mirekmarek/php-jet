<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;

use Jet\Auth_Role;
use Jet\Locale;
use Jet\Mvc_Page as Jet_Mvc_Page;
use Jet\Application_Modules;
use Jet\Mvc;
use Jet\Mvc_Page_Exception;
use Jet\Mvc_Site_Interface;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Auth;
use Jet\Tr;

class Mvc_Page extends Jet_Mvc_Page {
	const CHANGE_PASSWORD_ID = '_change_password_';
	const ADMIN_HOMEPAGE_ID = 'admin';

	/**
	 * @var bool
	 */
	protected $is_dialog = false;

	/**
	 * @var bool
	 */
	protected $is_system_page = false;


	/**
	 * @var bool
	 */
	protected static $admin_sections_loaded = false;

	/**
	 * @param string $parent_id
	 */
	public function setParentId($parent_id ) {

		$this->parent_id = $parent_id;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @throws Mvc_Page_Exception
	 */
	public static function loadPages( Mvc_Site_Interface $site, Locale $locale ) {

		if(!Mvc::getIsAdminUIRequest()) {
			parent::loadPages($site, $locale);
		}

		if(static::$admin_sections_loaded) {
			return;
		}

		static::$admin_sections_loaded = true;

		$modules = Application_Modules::getActivatedModulesList();

		$admin_home_page_id = static::ADMIN_HOMEPAGE_ID;

		foreach( $modules as $manifest ) {
			/**
			 * @var Application_Modules_Module_Manifest $manifest
			 */


			foreach($manifest->getAdminSections() as $page_id=>$page_data ) {
				if(!isset($page_data['layout_script_name'])) {
					$page_data['layout_script_name'] = 'default';
				}

				static::addAdminPage( $admin_home_page_id, $page_id, $page_data, $manifest );
			}

			foreach($manifest->getAdminDialogs() as $page_id=>$page_data ) {
				$page_id='dialog_'.$page_id;
				$page_data['is_dialog'] = true;
				if(!isset($page_data['layout_script_name'])) {
					$page_data['layout_script_name'] = 'dialog';
				}

				$page_data['URL_fragment'] = 'dialog-'.$page_data['URL_fragment'];

				static::addAdminPage( $admin_home_page_id, $page_id, $page_data, $manifest );
			}
		}
	}

	/**
	 * @param string $admin_home_page_id
	 * @param string $page_id
	 * @param array $page_data
	 * @param Application_Modules_Module_Manifest $module_manifest
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminPage( $admin_home_page_id, $page_id, array $page_data, Application_Modules_Module_Manifest $module_manifest ) {

		foreach( Mvc::getCurrentSite()->getLocales() as $locale ) {

			/**
			 * @var Mvc_Page $admin_home_page
			 */
			$admin_home_page = Mvc_Page::get($admin_home_page_id, $locale);
			if(!$admin_home_page) {
				continue;
			}

			$admin_home_page->setIsSystemPage(true);


			$title = empty($page_data['title']) ? $module_manifest->getLabel() : $page_data['title'];
			$title = Tr::_($title, [], $module_manifest->getName(), $locale);

			$layout_script_name = $page_data['layout_script_name'];
			$is_dialog = !empty($page_data['is_dialog']);
			if($is_dialog) {
				$is_system_page = false;
			} else {
				$is_system_page = !empty($page_data['is_system_page']);
			}

			$URL_fragment = $page_data['URL_fragment'];
			if($is_dialog) {
				$action = substr($page_id, 7);
			} else {
				$action = empty($page_data['action']) ? 'default' : $page_data['action'];
			}



			/**
			 * @var Mvc_Page $page
			 */
			$page = Mvc_Factory::getPageInstance();
			$page->setSiteId( Mvc::getCurrentSite()->getSiteId() );
			$page->setLocale( $locale );
			$page->setPageId( $page_id );
			$page->setParentId( $admin_home_page->getPageId() );

			$page->_parent = $admin_home_page;
			$admin_home_page->_children[] = $page;

			$page->setLayoutScriptName( $layout_script_name );
			$page->setCustomLayoutsPath( $admin_home_page->getCustomLayoutsPath() );

			$page->setIsSystemPage($is_system_page);
			$page->setIsDialog($is_dialog);

			$page->setIsAdminUI(true);

			$page->setTitle( $title );
			$page->setUrlFragment( $URL_fragment );

			$content = Mvc_Factory::getPageContentInstance();
			$content->setContentId( $page_id.'_'.$action );
			$content->setModuleName( $module_manifest->getName() );
			$content->setControllerAction( $action );
			$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );

			if($is_dialog) {
				$content->setCustomController('Dialogs');
			}
			if($is_system_page) {
				$content->setCustomController('SystemPages');
			}


			$page->setContent([$content]);


			$page_key = $page->getPageKey();

			if(isset(static::$loaded_pages[$page_key])) {
				throw new Mvc_Page_Exception( 'Duplicates page key: \''.$page_key.'\' ', Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID  );
			}

			static::$loaded_pages[$page_key] = $page;
			static::$site_pages_loaded_flag[$page_key] = true;

			$page->setUrlFragment( rawurldecode($page->getUrlFragment()) );

			static::$relative_URIs_map[$page->getRelativeUrl()] = $page_key;

		}
	}


	/**
	 * @return boolean
	 */
	public function getIsDialog()
	{
		return $this->is_dialog;
	}

	/**
	 * @param boolean $is_dialog
	 */
	public function setIsDialog($is_dialog)
	{
		$this->is_dialog = $is_dialog;
	}

	/**
	 * @return boolean
	 */
	public function getIsSystemPage()
	{
		return $this->is_system_page;
	}

	/**
	 * @param boolean $is_system_page
	 */
	public function setIsSystemPage($is_system_page)
	{
		$this->is_system_page = $is_system_page;
	}



	/**
	 * @return bool
	 */
	public function getAccessAllowed() {

		if(!Mvc::getIsAdminUIRequest()) {
			return parent::getAccessAllowed();
		}

		if($this->getIsDialog() || $this->getIsSystemPage()) {
			return true;
		}


		if( Auth::getCurrentUserHasPrivilege( Auth_Role::PRIVILEGE_VISIT_PAGE, $this->getPageId() ) ) {
			return true;
		}

		return false;

	}

}
