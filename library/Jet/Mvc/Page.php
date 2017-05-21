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

	use Mvc_Page_Trait_Initialization;
	use Mvc_Page_Trait_Tree;
	use Mvc_Page_Trait_URL;
	use Mvc_Page_Trait_Auth;
	use Mvc_Page_Trait_Handlers;

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
	 * @var string
	 */
	protected $relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected $relative_path = '';


	/**
	 *
	 * @var bool
	 */
	protected $is_admin_UI = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_secret_page = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_sub_app = false;

	/**
	 *
	 * @var string
	 */
	protected $sub_app_index_file_name = 'index.php';

	/**
	 * @var array
	 */
	protected $sub_app_php_file_extensions = [ 'php', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7' ];

	/**
	 *
	 * @var array
	 */
	protected $http_headers = [];

	/**
	 *
	 * @var string
	 */
	protected $output;

	/**
	 *
	 * @var string
	 */
	protected $custom_layouts_path = '';

	/**
	 *
	 * @var string
	 */
	protected $layout_script_name = '';

	/**
	 *
	 * @var Mvc_Page_Content_Interface[]
	 */
	protected $content;

	/**
	 *
	 * @var Mvc_Page_MetaTag[]
	 */
	protected $meta_tags = [];


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
		if(
			!$page_id &&
			!$locale &&
			!$site_id
		) {
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



	/**
	 * @return bool
	 */
	public function getIsSubApp()
	{
		return $this->is_sub_app;
	}

	/**
	 * @param bool $is_sub_app
	 */
	public function setIsSubApp( $is_sub_app )
	{
		$this->is_sub_app = $is_sub_app;
	}


	/**
	 * @return string
	 */
	public function getSubAppIndexFileName()
	{
		return $this->sub_app_index_file_name;
	}

	/**
	 * @param string $index_file_name
	 */
	public function setSubAppIndexFileName( $index_file_name )
	{
		$this->sub_app_index_file_name = $index_file_name;
	}


	/**
	 * @return array
	 */
	public function getHttpHeaders()
	{
		if(
			!$this->http_headers &&
			$this->getParent()
		) {
			return $this->getParent()->getHttpHeaders();
		}
		return $this->http_headers;
	}

	/**
	 * @param array $http_headers
	 */
	public function setHttpHeaders( array $http_headers )
	{
		$this->http_headers = $http_headers;
	}

	/**
	 * @param string $output
	 */
	public function setOutput( $output )
	{
		$this->output = $output;
	}

	/**
	 * @return string|null
	 */
	public function getOutput()
	{
		return $this->output;
	}


	/**
	 * @return array
	 */
	public function getSubAppPhpFileExtensions()
	{
		return $this->sub_app_php_file_extensions;
	}

	/**
	 * @param array $php_file_extensions
	 */
	public function setSybAppPhpFileExtensions( array $php_file_extensions )
	{
		$this->sub_app_php_file_extensions = $php_file_extensions;
	}

	/**
	 * @return bool
	 */
	public function getIsSecretPage()
	{
		return $this->is_secret_page;
	}

	/**
	 * @param bool $is_secret_page
	 */
	public function setIsSecretPage( $is_secret_page )
	{
		$this->is_secret_page = (bool)$is_secret_page;
	}

	/**
	 * @return bool
	 */
	public function getIsAdminUI()
	{
		return $this->is_admin_UI;
	}

	/**
	 * @param bool $is_admin_UI
	 */
	public function setIsAdminUI( $is_admin_UI )
	{
		$this->is_admin_UI = (bool)$is_admin_UI;
	}

	/**
	 * @return string
	 */
	public function getCustomLayoutsPath()
	{
		if(
			!$this->custom_layouts_path &&
			$this->getParent()
		) {
			return $this->getParent()->getCustomLayoutsPath();
		}

		return $this->custom_layouts_path;
	}

	/**
	 * @param string $layouts_dir
	 */
	public function setCustomLayoutsPath( $layouts_dir )
	{
		$this->custom_layouts_path = $layouts_dir;
	}

	/**
	 * @return string
	 */
	public function getLayoutScriptName()
	{

		if(
			!$this->layout_script_name &&
			$this->getParent()
		) {
			return $this->getParent()->getLayoutScriptName();
		}

		return $this->layout_script_name;
	}

	/**
	 * @param string $layout_script_name
	 */
	public function setLayoutScriptName( $layout_script_name )
	{
		$this->layout_script_name = $layout_script_name;
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath()
	{
		/**
		 * @var Mvc_Page $this
		 */
		if( $this->getCustomLayoutsPath() ) {
			return $this->getCustomLayoutsPath();
		}

		return $this->getSite()->getLayoutsPath();
	}

	/**
	 * @throws Exception
	 *
	 */
	public function initializeLayout()
	{
		/**
		 * @var Mvc_Page $this
		 */
		if( Mvc_Layout::getCurrentLayout() ) {
			return;
		}

		Mvc_Layout::setCurrentLayout(
			Mvc_Factory::getLayoutInstance( $this->getLayoutsPath(), $this->getLayoutScriptName() )
		);

	}


	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param Mvc_Page_Content_Interface[] $content
	 */
	public function setContent( $content )
	{
		$this->content = [];

		foreach( $content as $c ) {
			$this->addContent( $c );
		}
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content )
	{
		if( !$content->getId() ) {
			$content->setId( count( $this->content ) );
		}
		$content->setPage( $this );

		$this->content[] = $content;
	}

	/**
	 * @param int $index
	 */
	public function removeContent( $index )
	{
		unset( $this->content[$index] );
	}


	/**
	 *
	 * @return Mvc_Page_MetaTag_Interface[]
	 */
	public function getMetaTags()
	{
		$meta_tags = [];

		foreach( $this->getSite()->getLocalizedData( $this->getLocale() )->getDefaultMetaTags() as $mt ) {
			$key = $mt->getAttribute().':'.$mt->getAttributeValue();
			if( $key==':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}


		foreach( $this->meta_tags as $mt ) {
			$key = $mt->getAttribute().':'.$mt->getAttributeValue();
			if( $key==':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}

		return $this->meta_tags;
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( $meta_tags )
	{
		$this->meta_tags = [];

		foreach( $meta_tags as $meta_tag ) {
			$this->addMetaTag( $meta_tag );
		}
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag )
	{

		$meta_tag->setPage( $this );
		$this->meta_tags[] = $meta_tag;
	}


}