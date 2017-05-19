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
class Mvc_Page extends BaseObject implements Mvc_Page_Interface, BaseObject_Cacheable_Interface
{
	use BaseObject_Cacheable_Trait;

	const HOMEPAGE_ID = '_homepage_';

	use Mvc_Page_Trait;

	/**
	 *
	 * @var Mvc_Site
	 */
	protected $site_id;
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
	protected $breadcrumb_title = '';

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
	 * @var string
	 */
	protected $relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected $relative_path = '';

	/**
	 *
	 * @param string             $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null        $site_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function get( $page_id = null, $locale = null, $site_id = null )
	{
		if( !$page_id && !$locale && !$site_id ) {
			return Mvc::getCurrentPage();
		}

		if( !$page_id ) {
			$page_id = Mvc::getCurrentPage()->getId();
		}

		if( !$locale ) {
			$locale = Mvc::getCurrentLocale();
		}

		if( !is_object( $locale ) ) {
			$locale_str = (string)$locale;

			$locale = new Locale( $locale_str );
		} else {
			$locale_str = (string)$locale;
		}


		if( !$site_id ) {
			$site_id = Mvc::getCurrentSite()->getId();
		}


		if( isset( static::$pages[$site_id][$locale_str][$page_id] ) ) {
			return static::$pages[$site_id][$locale_str][$page_id];
		}


		$site_class_name = Mvc_Factory::getSiteClassName();
		$page_class_name = Mvc_Factory::getPageClassName();

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 * @var Mvc_Page_Interface $page_class_name
		 */
		$site = $site_class_name::get( $site_id );

		$page_class_name::loadPages( $site, $locale );

		if( isset( static::$pages[$site_id][$locale_str][$page_id] ) ) {
			return static::$pages[$site_id][$locale_str][$page_id];
		}

		return null;

	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 * @param string             $relative_path
	 *
	 * @return Mvc_Page_Interface|null
	 */
	static public function getByRelativePath( Mvc_Site_Interface $site, Locale $locale, $relative_path )
	{

		static::loadPages( $site, $locale );

		$str_locale = (string)$locale;

		if( !isset( static::$path_map[$site->getId()][$str_locale][$relative_path] ) ) {
			return null;
		}

		return static::$path_map[$site->getId()][$str_locale][$relative_path];
	}

	/**
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function getList( $site_id, Locale $locale )
	{
		$site_class_name = Mvc_Factory::getSiteClassName();

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 */
		$site = $site_class_name::get( $site_id );

		if( is_string( $locale ) ) {
			$locale = new Locale( $locale );
		}

		/**
		 * @var Mvc_Page $homepage
		 */
		$homepage = $site->getHomepage( $locale );

		$result = [];

		$homepage->_getList( $result );

		return $result;
	}

	/**
	 * @param array $result
	 */
	protected function _getList( array &$result )
	{
		$result[] = $this;
		foreach( $this->getChildren() as $child ) {
			/**
			 * @var Mvc_Page $child
			 */
			$child->_getList( $result );
		}
	}


	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->getSite()->getId().':'.$this->getLocale().':'.$this->getId();
	}

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite()
	{
		$site_class_name = Mvc_Factory::getSiteClassName();

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 */
		return $site_class_name::get( $this->site_id );
	}

	/**
	 * @param Mvc_Site_Interface $site_id
	 */
	public function setSite( Mvc_Site_Interface $site_id )
	{
		$this->site_id = $site_id->getId();
	}

	/**
	 *
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale )
	{
		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		if(
			$this->getParent() &&
			!$this->getParent()->getIsActive()
		) {
			return false;
		}
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active )
	{
		$this->is_active = $is_active;
	}

	/**
	 * @param string $relative_path_fragment
	 */
	public function setPathFragment( $relative_path_fragment )
	{
		$this->relative_path_fragment = $relative_path_fragment;
	}

	/**
	 * @return string
	 */
	public function getRelativePathFragment()
	{
		return $this->relative_path_fragment;
	}


	/**
	 * @return string
	 */
	public function getRelativePath()
	{
		return $this->relative_path;
	}


	/**
	 * @param string $relative_path
	 */
	public function setRelativePath( $relative_path )
	{
		$this->relative_path = $relative_path;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired()
	{
		if(
			$this->getParent() &&
			$this->getParent()->getSSLRequired()
		) {
			return true;
		}

		if($this->getSite()->getLocalizedData( $this->getLocale() )->getSSLRequired()) {
			return true;
		}

		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required )
	{
		$this->SSL_required = $SSL_required;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle()
	{
		return $this->menu_title;
	}

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle( $menu_title )
	{
		$this->menu_title = $menu_title;
	}


	/**
	 * @return string
	 */
	public function getBreadcrumbTitle()
	{
		return $this->breadcrumb_title;
	}

	/**
	 * @param string $breadcrumb_title
	 */
	public function setBreadcrumbTitle( $breadcrumb_title )
	{
		$this->breadcrumb_title = $breadcrumb_title;
	}


}