<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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
use Jet\Auth;

class Mvc_Page extends Jet_Mvc_Page {

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
		/**
		 * @var Mvc_Page $admin_home_page
		 */
		$admin_home_page = Mvc_Page::get('admin');
		$admin_home_page->setIsSystemPage(true);

		foreach( $modules as $manifest ) {
			/**
			 * @var Application_Modules_Module_Manifest $manifest
			 */

			foreach($manifest->getAdminSections() as $page ) {
				static::addAdminPage( $admin_home_page, $page );
			}

			foreach($manifest->getAdminDialogs() as $page ) {
				static::addAdminPage( $admin_home_page, $page );
			}
		}
	}

	/**
	 * @param Mvc_Page $admin_home_page
	 * @param Mvc_Page $page
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminPage( Mvc_Page $admin_home_page, Mvc_Page $page ) {

		$page_key = $page->getPageKey();

		if(isset(static::$loaded_pages[$page_key])) {
			throw new Mvc_Page_Exception( 'Duplicates page key: \''.$page_key.'\' module:'.$manifest->getName(), Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID  );
		}

		$page->setCustomLayoutsPath( $admin_home_page->getCustomLayoutsPath() );

		$page->setParentId( $admin_home_page->getPageId() );

		$page->_parent = $admin_home_page;
		$admin_home_page->_children[] = $page;

		static::$loaded_pages[$page_key] = $page;
		static::$site_pages_loaded_flag[$page_key] = true;

		$page->setUrlFragment( rawurldecode($page->getUrlFragment()) );

		static::$relative_URIs_map[$page->getRelativeUrl()] = $page_key;
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
