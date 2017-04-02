<?php
/**
 *
 *
 *
 * Class that contains basic information about the module
 *
 * @see Application_Modules_Module_Abstract
 *
 * Each module has manifest file (~/application/modules/Module/manifest.php), that contains these specifications:
 *  - label (required)
 *  - API_version (required)
 *  - type (required)
 *  - description (optional)
 *  - require (optional)
 *  - signals_callbacks (optional)
 *
 * See class variables description for more details
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace JetExampleApp;

use Jet\Application_Modules_Exception;
use Jet\Application_Modules_Module_Manifest as Jet_Application_Modules_Module_Manifest;

use JetUI\menu_item;

use Jet\Mvc;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Tr;

class Application_Modules_Module_Manifest extends Jet_Application_Modules_Module_Manifest
{

	/**
	 * @var array
	 */
	protected $admin_sections = [];

	/**
	 * @var array
	 */
	protected $admin_dialogs = [];

	/**
	 * @var array
	 */
	protected $admin_menu_items = [];

	/**
	 * @var Mvc_Page[]
	 */
	protected $_admin_dialogs;

	/**
	 * @var Mvc_Page[]
	 */
	protected $_admin_sections;


	/**
	 * @return Mvc_Page[]
	 *
	 * @throws Application_Modules_Exception
	 */
	public function getAdminSections()
	{
		if($this->_admin_sections===null) {
			$this->_admin_sections = [];

			foreach( $this->admin_sections as $id=>$d ) {
				$page = $this->_getPageInstance($id, $d, '', 'default');

				if(isset($pages[$id])) {
					throw new Application_Modules_Exception('Sections conflict: '.$id);
				}

				$this->_admin_sections[$id] = $page;
			}
		}

		return $this->_admin_sections;

	}

	/**
	 * @return Mvc_Page[]
	 *
	 * @throws Application_Modules_Exception
	 */
	public function getAdminDialogs()
	{

		if($this->_admin_dialogs===null) {
			$this->_admin_dialogs = [];

			foreach( $this->admin_dialogs as $id=>$d ) {
				$id='dialog_'.$id;
				$d['is_dialog'] = true;

				$page = $this->_getPageInstance($id, $d, 'dialog-', 'dialog');

				if(isset($pages[$id])) {
					throw new Application_Modules_Exception('Dialog conflict: '.$id);
				}


				$this->_admin_dialogs[$id] = $page;
			}
		}

		return $this->_admin_dialogs;
	}


	/**
	 * @param string $id
	 * @param array $d
	 * @param string $URL_fragment_prefix
	 * @param string $default_layout_script_name
	 *
	 * @return Mvc_Page
	 */
	protected function _getPageInstance( $id, $d, $URL_fragment_prefix, $default_layout_script_name )
	{


		$title = empty($d['title']) ? $this->getLabel() : $d['title'];
		$title = Tr::_($title, [], $this->getName());
		$layout_script_name = empty($d['layout_script_name']) ? $default_layout_script_name : $d['layout_script_name'];
		$is_dialog = !empty($d['is_dialog']);
		if($is_dialog) {
			$is_system_page = false;
		} else {
			$is_system_page = !empty($d['is_system_page']);
		}
		$URL_fragment = $d['URL_fragment'];
		if($is_dialog) {
			$action = substr($id, 7);
		} else {
			$action = empty($d['action']) ? 'default' : $d['action'];
		}



		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc_Factory::getPageInstance();
		$page->setSiteId( Mvc::getCurrentSite()->getSiteId() );
		$page->setLocale( Mvc::getCurrentLocale() );
		$page->setPageId($id);
		if($id!=Mvc_Page::HOMEPAGE_ID) {
			$page->setParentId(Mvc_Page::HOMEPAGE_ID);
		}
		$page->setLayoutScriptName( $layout_script_name );


		$page->setIsSystemPage($is_system_page);
		$page->setIsDialog($is_dialog);

		$page->setIsAdminUI(true);

		$page->setTitle( $title );
		$page->setUrlFragment( $URL_fragment_prefix.$URL_fragment );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setContentId( $id.'_'.$action );
		$content->setModuleName( $this->getName() );
		$content->setControllerAction( $action );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );

		if($is_dialog) {
			$content->setCustomController('Dialogs');
		}
		if($is_system_page) {
			$content->setCustomController('SystemPages');
		}


		$content->setIsDynamic(true);

		$page->setContents([$content]);

		return $page;
	}

	/**
	 * @return menu_item[]
	 */
	public function getMenuItems()
	{
		$menu_items = [];

		foreach( $this->admin_menu_items as $id=>$menu_data ) {
			$menu_data['id'] = $id;

			/** @noinspection PhpParamsInspection */
			$menu_item = new menu_item(
				$menu_data['parent_menu_id'],
				$menu_data['id'],
				$menu_data['label']
			);

			if(isset($menu_data['index'])) {
				$menu_item->setIndex($menu_data['index']);
			}
			if(isset($menu_data['icon'])) {
				$menu_item->setIcon($menu_data['icon']);
			}
			if(isset($menu_data['page_id'])) {
				$menu_item->setPageId($menu_data['page_id']);
			}
			if(isset($menu_data['url_parts'])) {
				$menu_item->setUrlParts($menu_data['url_parts']);
			}
			if(isset($menu_data['URL'])) {
				$menu_item->setURL($menu_data['URL']);
			}

			$menu_items[$menu_item->getId()] = $menu_item;
		}

		return $menu_items;
	}

}