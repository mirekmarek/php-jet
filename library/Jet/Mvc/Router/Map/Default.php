<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 */
namespace Jet;

/**
 */
class Mvc_Router_Map_Default extends Mvc_Router_Map_Abstract {

	/**
	 * @var Mvc_Router_Map_URL_Abstract[]
	 */
	protected $pages_to_URLs = array();

	/**
	 * @var Mvc_Router_Map_URL_Abstract[]
	 */
	protected $URLs_to_pages = array();

	/**
	 * @var Mvc_Router_Map_URL_Abstract
	 */
	protected $default_URL;




	/**
	 *
	 */
	public function generate() {
		$pages_to_URLs_map = [];
		$URLs_to_pages_map = [];
		$system_default_URL = null;


		$sites = Mvc_Sites::getAllSitesList();

		foreach( $sites as $site ) {
			$site_ID = $site->getID();

			foreach( $site->getLocales() as $locale ) {
				$site_URLs = [];

				foreach( $site->getURLs( $locale ) as $site_URL ) {
					$URL = Mvc_Factory::getRouterMapUrlInstance();
					$URL->setURL( $site_URL->toString() );
					$URL->setIsDefault( $site_URL->getIsDefault() );

					if(
						!$system_default_URL &&
						$site->getIsDefault() &&
						$URL->getIsDefault()
					) {
						$system_default_URL = $URL;
					}

					$site_URLs[] = $URL;
				}


				$tree = $this->getPagesTree( $site_ID, $locale );

				/**
				 * @var Mvc_Pages_Page_Abstract $current_parent_page
				 */
				$current_parent_page = null;
				$current_parent_URLs = [];

				foreach( $tree as $node ) {
					/**
					 * @var Data_Tree_Node $node
					 */
					$data = $node->getData();
					$parent_page_ID = $data['parent_ID'];
					$page_ID = $data['ID'];


					if(
						(!$current_parent_page &&  $parent_page_ID) ||
						($current_parent_page && $current_parent_page->getID()['ID']!=$parent_page_ID )
					) {
						$current_parent_page = $this->loadPage( $site_ID, $locale, $parent_page_ID );
						$current_parent_key = $current_parent_page->getID()->getAsMapKey();
						$current_parent_URLs = $pages_to_URLs_map[$current_parent_key];
					}

					$page = $this->loadPage( $site_ID, $locale, $page_ID );
					$page_key = $page->getID()->getAsMapKey();

					$URLs = $page->generateMapURLs( $site_URLs, $current_parent_URLs );

					$pages_to_URLs_map[$page_key] = $URLs;

					foreach( $URLs as $URL ) {
						/**
						 * @var Mvc_Router_Map_URL_Abstract $URL
						 */
						$URLs_to_pages_map[$URL->toString()] = $URL;
					}

				}
			}
		}

		/*
		foreach( $pages_to_URLs_map as $page_key=>$URLs ) {
			var_dump($page_key);

			foreach( $URLs as $URL ) {
				if($URL->getIsMain()) echo 'M:';
				if($URL->getIsDefault()) echo 'D:';

				echo $URL->toString();
				echo JET_EOL;
			}
		}
		*/

		$this->URLs_to_pages = $URLs_to_pages_map;
		$this->pages_to_URLs = $pages_to_URLs_map;
		$this->default_URL = $system_default_URL;

	}

	/**
	 * @param string $site_ID
	 * @param Locale $locale
	 * @param string $page_ID
	 *
	 * @return Mvc_Pages_Page_Abstract|null
	 */
	protected function loadPage( $site_ID, $locale, $page_ID ) {
		$page_i = Mvc_Factory::getPageInstance();
		$page_ID_i = $page_i->getEmptyIDInstance();


		return $page_i->load($page_ID_i->createID($site_ID, $locale, $page_ID));
	}

	/**
	 * @param string $site_ID
	 * @param Locale $locale
	 * @return Data_Tree
	 */
	protected function getPagesTree( $site_ID, $locale ) {
		return Mvc_Factory::getPageInstance()->getTree( $site_ID, $locale );
	}

	/**
	 * @return Mvc_Router_Map_URL_Abstract
	 */
	public function getDefaultURL() {
		return $this->default_URL;
	}


	/**
	 * @param array $URLs
	 *
	 * @return Mvc_Router_Map_URL_Abstract|null
	 *
	 */
	public function findPage( array $URLs ) {
		foreach( $URLs as $URL ) {
			if(isset($this->URLs_to_pages[$URL])) {
				return $this->URLs_to_pages[$URL];
			}
		}

		return null;
	}

	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 * @param bool $only_default (optional, default: false)
	 *
	 * @return Mvc_Router_Map_URL_Abstract[]|null
	 */
	public function findURLs( Mvc_Pages_Page_ID_Abstract $page_ID, $only_default=false ) {
		$key = $page_ID->getAsMapKey();


		if(!isset($this->pages_to_URLs[ $key ])) {
			return null;
		}

		if($only_default) {
			$result = [];
			foreach( $this->pages_to_URLs[ $key ] as $URL ) {
				/**
				 * @var Mvc_Router_Map_URL_Abstract $URL
				 */
				if($URL->getIsDefault()) {
					$result[] = $URL;
				}
			}

			return $result;
		}

		return $this->pages_to_URLs[ $key ];
	}

	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 *
	 * @return Mvc_Router_Map_URL_Abstract|null
	 */
	public function findMainURL( Mvc_Pages_Page_ID_Abstract $page_ID ) {
		$key = $page_ID->getAsMapKey();

		if(!isset($this->pages_to_URLs[ $key ])) {
			return null;
		}

		foreach( $this->pages_to_URLs[ $key ] as $URL ) {
			/**
			 * @var Mvc_Router_Map_URL_Abstract $URL
			 */
			if($URL->getIsMain()) {
				return $URL;
			}
		}

		return null;

	}


}