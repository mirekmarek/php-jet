<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class Mvc_Page extends BaseObject implements Mvc_Page_Interface {
	const HOMEPAGE_ID = '_homepage_';

	const PAGE_DATA_FILE_NAME = 'page_data.php';

	const DEFAULT_DIRECT_INDEX_FILE_NAME = 'index.php';

	use Mvc_Page_Trait_Initialization;
	use Mvc_Page_Trait_Tree;
	use Mvc_Page_Trait_URL;
	use Mvc_Page_Trait_BreadcrumbNavigation;
	use Mvc_Page_Trait_Content;
	use Mvc_Page_Trait_MetaTags;
	use Mvc_Page_Trait_Layout;
	use Mvc_Page_Trait_Auth;
	use Mvc_Page_Trait_Handlers;

	/**
	 * @var array
	 */
	protected static $php_file_extensions = [ 'php', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7'];


	/**
	 *
	 * @var Mvc_Site
	 */
	protected $site;

	/**
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 *
	 * @var bool
	 */
	protected $is_active = true;

	/**
	 *
	 * @var bool
	 */
	protected $SSL_required = false;

	/**
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @var string
	 */
	protected $menu_title = '';

	/**
	 *
	 * @var string
	 */
	protected $headers_suffix = '';

	/**
	 *
	 * @var string
	 */
	protected $body_prefix = '';

	/**
	 *
	 * @var string
	 */
	protected $body_suffix = '';


	/**
	 * @return string
	 */
	public function getKey() {
		return $this->getSite()->getId().':'.$this->getLocale().':'.$this->getId();
	}

	/**
	 * @param string $id
	 */
	public function setId($id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( Mvc_Site_Interface $site ) {
		$this->site = $site;
	}

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite() {
		return $this->site;
	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getSiteLocalizedData() {
		return $this->getSite()->getLocalizedData($this->getLocale());
	}

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ) {
		$this->locale = $locale;
	}

	/**
	 *
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive($is_active)
	{
		$this->is_active = $is_active;
	}







	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle() {
		return $this->menu_title;
	}

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle($menu_title) {
		$this->menu_title = $menu_title;
	}


	/**
	 * @param bool $get_default
	 *
	 * @return string
	 */
	public function getHeadersSuffix( $get_default=false ) {

		if( $get_default && !$this->headers_suffix) {
			return $this->getSiteLocalizedData()->getDefaultHeadersSuffix();
		}

		return $this->headers_suffix;
	}

	/**
	 * @param string $headers_suffix
	 */
	public function setHeadersSuffix( $headers_suffix ) {
		$this->headers_suffix = $headers_suffix;
	}

	/**
	 * @param bool $get_default
	 *
	 * @return string
	 */
	public function getBodyPrefix( $get_default=false ) {
		if( $get_default && !$this->body_prefix) {
			return $this->getSiteLocalizedData()->getDefaultBodyPrefix();
		}

		return $this->body_prefix;
	}

	/**
	 * @param string $body_prefix
	 */
	public function setBodyPrefix( $body_prefix ) {
		$this->body_prefix = $body_prefix;
	}

	/**
	 * @param bool $get_default
	 * @return string
	 */
	public function getBodySuffix( $get_default=false ) {

		if( $get_default && !$this->body_suffix ) {
			return $this->getSiteLocalizedData()->getDefaultBodySuffix();
		}

		return $this->body_suffix;
	}

	/**
	 * @param string $body_suffix
	 */
	public function setBodySuffix( $body_suffix ) {
		$this->body_suffix = $body_suffix;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired() {
		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired($SSL_required) {
		$this->SSL_required = (bool)$SSL_required;
	}






	/**
	 *
	 * @param string $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null $site_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function get( $page_id=null, $locale=null, $site_id=null  ) {
		if(!$page_id && !$locale && !$site_id) {
			return Mvc::getCurrentPage();
		}

		if(!$page_id) {
			$page_id = Mvc::getCurrentPage()->getId();
		}

		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}
		if(!$site_id) {
			$site_id = Mvc::getCurrentSite()->getId();
		}

		return static::_load( $site_id, $locale, $page_id );
	}


	/**
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function getList($site_id, Locale $locale ) {
		$site_class_name = JET_MVC_SITE_CLASS;

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 */
		$site = $site_class_name::get($site_id);

		if(is_string($locale)) {
			$locale = new Locale($locale);
		}

		/**
		 * @var Mvc_Page $homepage
		 */
		$homepage = $site->getHomepage( $locale );

		$result = [];

		$homepage->_getList( $result);

		return $result;
	}


	/**
	 * @param array $result
	 */
	protected function _getList( array &$result ) {
		$result[] = $this;
		foreach( $this->getChildren() as $child ) {
			/**
			 * @var Mvc_Page $child
			 */
			$child->_getList( $result );
		}
	}

}