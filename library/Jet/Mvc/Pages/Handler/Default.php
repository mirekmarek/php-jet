<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

class Mvc_Pages_Handler_Default extends Mvc_Pages_Handler_Abstract {
	const PAGES_SUB_DIR = "pages/";
	const PAGES_STRUCTURE_DATA_FILE = "__structure__.php";
	const PAGES_DATA_FILE_SUFFIX = "php";

	/**
	 * Create new page
	 *
	 * @param Mvc_Pages_Page_Abstract $page_data
	 *
	 */
	public function createPage(Mvc_Pages_Page_Abstract $page_data) {
		$page_data->save();
	}

	/**
	 * Drop page
	 *
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 *
	 */
	public function dropPage( Mvc_Pages_Page_ID_Abstract $page_ID ) {
		$page = Mvc_Pages::getPage($page_ID);
		$page->delete();
		Mvc::truncateRouterCache();
	}

	/**
	 * Drop pages
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 */
	public function dropPages( $site_ID, Locale $locale ) {

		$page = Mvc_Factory::getPageInstance();

		foreach( $page->getIDs($site_ID, $locale) as $ID) {
			if( ($page_i = $page->load( $ID )) ) {
				$page_i->delete();
			}
		}
		Mvc::truncateRouterCache();
	}


	/**
	 * Actualize pages (example: actualize pages by project definition)
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @throws Mvc_Pages_Handler_Exception
	 *
	 */
	public function actualizePages( $site_ID, Locale $locale ) {
		$site_data = Mvc_Sites::getSite( Mvc_Factory::getSiteIDInstance()->createID( $site_ID ) );
		if(!$site_data) {
			throw new Mvc_Pages_Handler_Exception(
				"Unknown site '{$site_ID}'",
				Mvc_Pages_Handler_Exception::CODE_UNKNOWN_SITE
			);
		}

		echo "[{$site_ID}:{$locale}] Deleting pages ...";
		$this->dropPages( $site_ID, $locale );
		echo "DONE\n";

		$locale_dir = $site_data->getBasePath()
				.static::PAGES_SUB_DIR
				.(string)$locale."/";

		$default_dir = $site_data->getBasePath()
				.static::PAGES_SUB_DIR
				."_default_/";

		if(!is_dir($locale_dir)) {
			$data_dir = $default_dir;
		} else {
			$data_dir = $locale_dir;
		}

		if(!is_dir($data_dir)) {
			throw new Mvc_Pages_Handler_Exception(
				"Directory '$data_dir' and '$locale_dir' does not exist.",
				Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
			);
		}

		/** @noinspection PhpIncludeInspection */
		$structure = require $data_dir . static::PAGES_STRUCTURE_DATA_FILE;

		echo "[{$site_ID}:{$locale}] Creating pages ...\n";
		$this->_dataToDataModel( $data_dir, $site_data, $locale, null, Mvc_Pages::HOMEPAGE_ID );
		$this->_readStructure( $data_dir, $site_data, $locale, $structure );
		echo "DONE\n";
		Mvc::truncateRouterCache();
	}

	/**
	 * @param string $data_dir
	 * @param Mvc_Sites_Site_Abstract $site_data
	 * @param Locale $locale
	 * @param array $structure
	 * @param string $parent_ID
	 */
	protected function _readStructure(
					$data_dir,
					Mvc_Sites_Site_Abstract $site_data,
					Locale $locale,
					$structure,
					$parent_ID = Mvc_Pages::HOMEPAGE_ID
	) {
		foreach( $structure as $key=>$val ) {
			if(is_array($val)) {
				$this->_dataToDataModel( $data_dir, $site_data, $locale, $parent_ID, $key );
				$this->_readStructure( $data_dir, $site_data, $locale, $val, $key);
			} else {
				$this->_dataToDataModel( $data_dir, $site_data, $locale, $parent_ID, $val );
			}
		}
	}

	/**
	 * @param string $data_dir
	 * @param Mvc_Sites_Site_Abstract $site_data
	 * @param Locale $locale
	 * @param string $parent_ID
	 * @param string $ID
	 *
	 * @return Mvc_Pages_Page_Abstract
	 * @throws Mvc_Pages_Handler_Exception
	 */
	protected function _dataToDataModel(
			$data_dir,
			Mvc_Sites_Site_Abstract $site_data,
			Locale $locale,
			$parent_ID,
			$ID
	) {
		echo "[".$site_data->getID().":".$locale."] ";
		if( $site_data->getID()==Mvc_Pages::HOMEPAGE_ID ) {
			echo "{$ID}\n";
		} else {
			echo "{$parent_ID} => {$ID}\n";
		}

		$data_file_path = $data_dir . $ID. '.' .self::PAGES_DATA_FILE_SUFFIX;

		if(!IO_File::exists($data_file_path)) {
			throw new Mvc_Pages_Handler_Exception(
					"Page data file '{$data_file_path}' does not exist!",
					Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
				);
		}

		if(!IO_File::isReadable($data_file_path)) {
			throw new Mvc_Pages_Handler_Exception(
				"Page data file '{$data_file_path}' exists, but is not readable!",
				Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
			);
		}

		/** @noinspection PhpIncludeInspection */
		$dat = require $data_file_path;

		//TODO: validation, check is unique and so on ...

		$name = !empty($data["name"]) ? $data["name"] : $ID;
		$site_ID = $site_data->getID();

		$page = Mvc_Pages::getPage( Mvc_Factory::getPageIDInstance()->createID($site_ID, $locale, $ID) );
		if(!$page) {
			$page = Mvc_Pages::getNewPage($site_ID, $locale, $name, $parent_ID, $ID);
		}

		$page->setName($name);

		$contents = array();
		foreach( $dat["contents"]  as $c_dat) {
			$cnt = Mvc_Factory::getPageContentInstance();
			$cnt->initNewObject();

			if(!isset($c_dat["controller_action_parameters"])) {
				$c_dat["controller_action_parameters"] = array();
			}

			$cnt->setModuleName( $c_dat["module_name"] );
			if( isset($c_dat["controller_class_suffix"]) ) {
				$cnt->setControllerClassSuffix( $c_dat["controller_class_suffix"] );
			}
			$cnt->setControllerAction( $c_dat["controller_action"] );
			$cnt->setControllerActionParameters( $c_dat["controller_action_parameters"] );
			$cnt->setOutputPosition( $c_dat["output_position"] );
			$cnt->setOutputPositionOrder( $c_dat["output_position_order"] );
			$cnt->setOutputPositionRequired( $c_dat["output_position_required"] );

			$contents[] = $cnt;
		}
		$meta_tags = array();

		foreach( $dat["meta_tags"]  as $m_dat) {
			$mtg = Mvc_Factory::getPageMetaTagInstance();

			$mtg->initNewObject();
			
			$mtg->setAttribute( $m_dat["attribute"] );
			$mtg->setAttributeValue( $m_dat["attribute_value"] );
			$mtg->setContent( $m_dat["content"] );
			
			$meta_tags[] = $mtg;
		}

		if($ID == Mvc_Pages::HOMEPAGE_ID) {
			$dat["path_fragment"] = "";
		}

		if(!isset($dat["breadcrumb_title"])) $dat["breadcrumb_title"] = $dat["title"];
		if(!isset($dat["menu_title"])) $dat["menu_title"] = $dat["title"];

		$page->setTitle($dat["title"]);
		$page->setBreadcrumbTitle($dat["breadcrumb_title"]);
		$page->setMenuTitle($dat["menu_title"]);
		$page->setURLFragment($dat["URL_fragment"]);
		$page->setLayout($dat["layout"]);
		if(isset($dat["headers_suffix"])) {
			$page->setHeadersSuffix($dat["headers_suffix"]);
		}

		if(isset($dat["body_prefix"])) {
			$page->setBodyPrefix($dat["body_prefix"]);
		}

		if(isset($dat["body_suffix"])) {
			$page->setBodySuffix($dat["body_suffix"]);
		}

		if(isset($dat["is_admin_UI"])) {
			$page->setIsAdminUI($dat["is_admin_UI"]);
		}
		if(isset($dat["force_UI_manager_module_name"])) {
			$page->setForceUIManagerModuleName($dat["force_UI_manager_module_name"]);
		}
		$page->setContents($contents);
		$page->setMetaTags($meta_tags);

		$errors = array();
		if(!$page->validateData($errors)) {

			foreach($page->getValidationErrors() as $error) {
				$errors[] = (string)$error;
			}

			$errors = implode("\n", $errors);

			throw new Mvc_Pages_Handler_Exception(
					"Page data ID={$ID}, locale={$locale}, site_ID={$site_ID} is invalid!\n\nValidation errors:\n" .implode("\n", $errors),
					Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
				);
		}


		$page->save();

		return $page;
	}

}
