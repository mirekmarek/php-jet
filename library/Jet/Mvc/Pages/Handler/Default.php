<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

class Mvc_Pages_Handler_Default extends Mvc_Pages_Handler_Abstract {
	const PAGES_SUB_DIR = 'pages/';
	const PAGES_STRUCTURE_DATA_FILE = '__structure__.php';
	const PAGES_DATA_FILE_SUFFIX = 'php';


	/**
	 * @var Mvc_Sites_Site_Abstract
	 */
	protected $current_site;

	/**
	 * @var Locale
	 */
	protected $current_locale;

	/**
	 * @var string
	 */
	protected $current_pages_data_dir = '';

	/**
	 * @var array
	 */
	protected $current_pages_tree = array();

	/**
	 * @var Mvc_Pages_Page_Abstract[]
	 */
	protected $current_page_instsances = array();

	/**
	 * @var array
	 */
	protected $current_pages_tree_branch = array();

	/**
	 * @var array
	 */
	protected $current_page_IDs = array();

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
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @throws Mvc_Pages_Handler_Exception
	 * @return string
	 */
	protected function initCurrentSiteAndLocale( $site_ID, Locale $locale ) {
		$this->current_site = null;
		$this->current_locale = null;
		$this->current_pages_data_dir = '';
		$this->current_page_IDs = array();
		$this->current_page_instsances = array();

		$site = Mvc_Sites::getSite( Mvc_Factory::getSiteIDInstance()->createID( $site_ID ) );
		if(!$site) {
			throw new Mvc_Pages_Handler_Exception(
				'Unknown site \''.$site_ID.'\'',
				Mvc_Pages_Handler_Exception::CODE_UNKNOWN_SITE
			);
		}

		if(!$site->getHasLocale( $locale )) {
			throw new Mvc_Pages_Handler_Exception(
				'Unknown site locale \''.$site_ID.':'.$locale.'\'',
				Mvc_Pages_Handler_Exception::CODE_UNKNOWN_SITE
			);
		}

		$locale_dir = $site->getBasePath()
			.static::PAGES_SUB_DIR
			.(string)$locale.'/';

		$default_dir = $site->getBasePath()
			.static::PAGES_SUB_DIR
			.'_default_/';

		if(!is_dir($locale_dir)) {
			$data_dir = $default_dir;
		} else {
			$data_dir = $locale_dir;
		}

		if(!is_dir($data_dir)) {
			throw new Mvc_Pages_Handler_Exception(
				'Directory \''.$default_dir.'\' as well as \''.$locale_dir.'\' does not exist.',
				Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
			);
		}

		$this->current_pages_data_dir = $data_dir;

		$structure_tree_file_path = $this->current_pages_data_dir . static::PAGES_STRUCTURE_DATA_FILE;

		if( !file_exists( $structure_tree_file_path ) ) {
			throw new Mvc_Pages_Handler_Exception(
				'File \''.$structure_tree_file_path.'\' does not exist. ',
				Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
			);
		}

		if( !is_readable( $structure_tree_file_path ) ) {
			throw new Mvc_Pages_Handler_Exception(
				'File \''.$structure_tree_file_path.'\' is not readable. ',
				Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
			);
		}

		/** @noinspection PhpIncludeInspection */
		$tree = require $structure_tree_file_path ;

		$this->current_site = $site;
		$this->current_locale = $locale;
		$this->current_pages_data_dir = $data_dir;
		$this->current_pages_tree = $tree;
		$this->current_pages_tree_branch = $tree;

	}


	/**
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @internal param \Jet\site_ID $string
	 *
	 */
	public function checkPagesData( $site_ID, Locale $locale ) {

		$this->initCurrentSiteAndLocale( $site_ID, $locale );

		echo '['.$site_ID.':'.$locale.'] Checking pages ...'.JET_EOL;

		$this->_readAndCheckPageData( null, Mvc_Pages::HOMEPAGE_ID );

		$this->_traversePagesTree( Mvc_Pages::HOMEPAGE_ID, false );

		echo 'OK'.JET_EOL.JET_EOL;
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

		$this->initCurrentSiteAndLocale( $site_ID, $locale );

		echo '['.$site_ID.':'.$locale.'] Deleting pages ...';
		$this->dropPages( $site_ID, $locale );
		echo 'DONE'.JET_EOL;


		echo '['.$site_ID.':'.$locale.'] Creating pages ...'.JET_EOL;

		$page = $this->_readAndCheckPageData( null, Mvc_Pages::HOMEPAGE_ID );
		$page->save();

		$this->_traversePagesTree( Mvc_Pages::HOMEPAGE_ID, true );

		echo 'DONE'.JET_EOL;
		Mvc::truncateRouterCache();
	}


	/**
	 * @param string $parent_ID
	 * @param bool $save
	 */
	protected function _traversePagesTree( $parent_ID, $save ) {
		$branch = $this->current_pages_tree_branch;

		foreach( $branch as $key=>$val ) {
			if(is_array($val)) {
				$this->current_pages_tree_branch = $val;

				$page = $this->_readAndCheckPageData( $parent_ID, $key );
				if($save) {
					$page->save();
				}

				$this->_traversePagesTree( $key, $save);
			} else {
				$page = $this->_readAndCheckPageData( $parent_ID, $val );

				if($save) {
					$page->save();
				}
			}
		}

	}

	/**
	 * @param string $parent_ID
	 * @param string $ID
	 *
	 * @return Mvc_Pages_Page_Abstract
	 *
	 * @throws Mvc_Pages_Handler_Exception
	 */
	protected function _readAndCheckPageData(
			$parent_ID,
			$ID
	) {
		echo '['.$this->current_site->getID().':'.$this->current_locale.'] ';
		if( $this->current_site->getID()==Mvc_Pages::HOMEPAGE_ID ) {
			echo $ID.JET_EOL;
		} else {
			echo $parent_ID.' => '.$ID.JET_EOL;
		}

		if(in_array($ID, $this->current_page_IDs)) {
			throw new Mvc_Pages_Handler_Exception(
				'Page ID \''.$ID.'\' is not unique!',
				Mvc_Pages_Handler_Exception::CODE_PAGE_ID_NOT_UNIQUE
			);
		}

		$this->current_page_IDs[] = $ID;

		$data_file_path = $this->current_pages_data_dir . $ID. '.' .self::PAGES_DATA_FILE_SUFFIX;

		if(!IO_File::exists($data_file_path)) {
			throw new Mvc_Pages_Handler_Exception(
					'Page data file \''.$data_file_path.'\' does not exist!',
					Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
				);
		}

		if(!IO_File::isReadable($data_file_path)) {
			throw new Mvc_Pages_Handler_Exception(
				'Page data file \''.$data_file_path.'\' exists, but is not readable!',
				Mvc_Pages_Handler_Exception::CODE_HANDLER_ERROR
			);
		}

		/** @noinspection PhpIncludeInspection */
		$dat = require $data_file_path;


		$site_ID = $this->current_site->getID();
		$locale = $this->current_locale;

		$name = !empty($data['name']) ? $data['name'] : $ID;
		$dat['name'] = $name;

		$page = Mvc_Pages::getNewPage($site_ID, $locale, $name, $parent_ID, $ID);
		$page->setPageDataCheckingMode( true );
		$page->setPageDataCheckingMap( $this->current_page_instsances );

		/**
		 * @var Mvc_Pages_Page_ID_Default $ID
		 */
		$ID = $page->getID();
		$s_ID = $ID->getPageID().':'.$ID->getSiteID().':'.$ID->getLocale();

		$this->current_page_instsances[$s_ID] = $page;

		$page_form = $page->getCommonForm();

		//$page->setName($name);

		if(!isset($dat['breadcrumb_title'])) {
			$dat['breadcrumb_title'] = $dat['title'];
		}
		if(!isset($dat['menu_title'])) {
			$dat['menu_title'] = $dat['title'];
		}


		$dat['ID'] = $ID;
		$dat['site_ID'] = $site_ID;
		$dat['locale'] = $locale;

		foreach( $page_form->getFields() as $field_name=>$field ) {
			if(!isset($dat[$field_name])) {
				$dat[$field_name] = '';
			}
		}

		if(!$page->catchForm( $page_form, $dat, true )) {
			$this->_handleValidationErrors( $ID, $page_form->getAllErrors() );
		}



		$content_form = Mvc_Factory::getPageContentInstance()->getCommonForm();

		$contents = array();
		foreach( $dat['contents']  as $c_dat) {

			foreach( $content_form->getFields() as $field_name=>$field ) {
				if(!isset($c_dat[$field_name])) {
					$c_dat[$field_name] = '';
				}
			}

			if(!isset($c_dat['controller_action_parameters'])) {
				$c_dat['controller_action_parameters'] = array();
			}

			if(!is_array($c_dat['controller_action_parameters']) && $c_dat['controller_action_parameters']) {
				$c_dat['controller_action_parameters'] = array( $c_dat['controller_action_parameters'] );
			} else {
				$c_dat['controller_action_parameters'] = array();
			}

			$content_form->getField('controller_action_parameters')->setSelectOptions( array_combine($c_dat['controller_action_parameters'], $c_dat['controller_action_parameters']) );


			$cnt = Mvc_Factory::getPageContentInstance();
			$cnt->initNewObject();

			if(!$cnt->catchForm( $content_form, $c_dat, true )) {
				$this->_handleValidationErrors( $ID, $content_form->getAllErrors() );
			}

			$contents[] = $cnt;
		}

		$meta_tags = array();
		$meta_tag_form = Mvc_Factory::getPageMetaTagInstance()->getCommonForm('');

		foreach( $dat['meta_tags']  as $m_dat) {
			foreach( $meta_tag_form->getFields() as $field_name=>$field ) {
				if(!isset($m_dat[$field_name])) {
					$m_dat[$field_name] = '';
				}
			}


			$mtg = Mvc_Factory::getPageMetaTagInstance();
			$mtg->initNewObject();
			if(!$mtg->catchForm( $meta_tag_form, $m_dat, true )) {
				$this->_handleValidationErrors( $ID, $meta_tag_form->getAllErrors() );
			}

			$meta_tags[] = $mtg;
		}

		$page->setContents($contents);
		$page->setMetaTags($meta_tags);

		$errors = array();
		if(!$page->validateProperties($errors)) {
			$this->_handleValidationErrors( $ID, $page->getValidationErrors() );
		}



		return $page;
	}

	/**
	 * @param string $page_ID
	 * @param array $errors
	 *
	 * @throws Mvc_Pages_Handler_Exception
	 */
	protected function _handleValidationErrors( $page_ID, $errors ) {

		$_errors = array();
		foreach($errors as $key=>$error) {
			if(is_string($key)) {
				$_errors[] = $key.': '.(string)$error;

			} else {
				$_errors[] = (string)$error;

			}
		}

		$_errors = implode(JET_EOL, $_errors);

		$site_ID = $this->current_site->getID();

		throw new Mvc_Pages_Handler_Exception(
			'Page data ID='.$page_ID.', locale='.$this->current_locale.', site_ID='.$site_ID.' is invalid!'.JET_EOL.JET_EOL.'Validation errors:'.JET_EOL.$_errors,
			Mvc_Pages_Handler_Exception::CODE_PAGE_DATA_VALIDATION_ERROR
		);
	}

}
