<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Page/Interface.php';
require_once 'Page/Trait/Initialization.php';
require_once 'Page/Trait/Tree.php';
require_once 'Page/Trait/URL.php';
require_once 'Page/Trait/Auth.php';
require_once 'Page/Trait/Handlers.php';
require_once 'Page/MetaTag.php';
require_once 'Page/Content.php';


/**
 *
 */
class Mvc_Page extends BaseObject implements Mvc_Page_Interface
{

	const HOMEPAGE_ID = '_homepage_';

	use Mvc_Page_Trait_Initialization;
	use Mvc_Page_Trait_Tree;
	use Mvc_Page_Trait_URL;
	use Mvc_Page_Trait_Auth;
	use Mvc_Page_Trait_Handlers;
	use Mvc_Page_Trait_Save;

	/**
	 *
	 * @var ?string
	 */
	protected ?string $site_id = null;
	/**
	 *
	 * @var ?Locale
	 */
	protected ?Locale $locale = null;
	/**
	 *
	 * @var string
	 */
	protected string $id = '';
	/**
	 *
	 * @var string
	 */
	protected string $name = '';
	/**
	 *
	 * @var bool
	 */
	protected bool $is_active = true;

	/**
	 * @var bool
	 */
	protected bool $SSL_required = false;

	/**
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 *
	 * @var string
	 */
	protected string $menu_title = '';

	/**
	 *
	 * @var string
	 */
	protected string $breadcrumb_title = '';

	/**
	 * @var string
	 */
	protected string $relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected string $original_relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected string $relative_path = '';

	/**
	 *
	 * @var bool
	 */
	protected bool $is_secret = false;

	/**
	 *
	 * @var array
	 */
	protected array $http_headers = [];

	/**
	 *
	 * @var string|callable
	 */
	protected $output;

	/**
	 *
	 * @var string
	 */
	protected string $layout_script_name = '';

	/**
	 *
	 * @var Mvc_Page_Content_Interface[]
	 */
	protected array $content = [];

	/**
	 *
	 * @var Mvc_Page_MetaTag[]
	 */
	protected array $meta_tags = [];

	/**
	 *
	 * @param string|null $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null $site_id (optional, null = current)
	 *
	 * @return static|null
	 */
	public static function get( string|null $page_id, string|Locale|null $locale = null, string|null $site_id = null ): static|null
	{

		if( !$page_id ) {
			if( !Mvc::getCurrentPage() ) {
				return null;
			}
			$page_id = Mvc::getCurrentPage()->getId();
		}

		if( !$locale ) {
			$locale = Mvc::getCurrentLocale();
			if( !$locale ) {
				return null;
			}
		}

		if( is_string( $locale ) ) {
			$locale = new Locale( $locale );
		}


		if( !$site_id ) {
			if( !Mvc::getCurrentSite() ) {
				return null;
			}

			$site_id = Mvc::getCurrentSite()->getId();
		}

		$key = $site_id . ':' . $locale . ':' . $page_id;

		if( isset( static::$pages[$key] ) ) {
			return static::$pages[$key];
		}

		$site = Mvc_Factory::getSiteInstance()::get( $site_id );

		$maps = static::loadMaps( $site, $locale );

		if( !isset( $maps['pages_files_map'][$page_id] ) ) {
			return null;
		}

		$data_file_path = $maps['pages_files_map'][$page_id];

		if( $data_file_path[0] == '@' ) {
			$module_name = substr( $data_file_path, 1 );
			$module = Application_Modules::moduleManifest( $module_name );

			if( !$module ) {
				return null;
			}

			$pages = $module->getPages( $site, $locale );

			if( !isset( $pages[$page_id] ) ) {
				return null;
			}

			$page = $pages[$page_id];

			$page->children = $maps['children_map'][$page_id];
			$page->relative_path = array_search( $page_id, $maps['relative_path_map'] );
			$page->parent_id = $maps['parent_map'][$page_id];

			static::$pages[$key] = $page;

		} else {
			if( !IO_File::isReadable( $data_file_path ) ) {
				throw new Mvc_Page_Exception(
					'Page data file is not readable: ' . $data_file_path,
					Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA
				);
			}

			/** @noinspection PhpIncludeInspection */
			$data = require $data_file_path;

			$data['id'] = $page_id;
			$data['children'] = $maps['children_map'][$page_id];
			$data['relative_path'] = array_search( $page_id, $maps['relative_path_map'] );
			$data['relative_path_fragment'] = basename( $data['relative_path'] );
			$data['parent_id'] = $maps['parent_map'][$page_id];

			$page = static::createByData( $site, $locale, $data );

			static::$pages[$key] = $page;

		}


		return static::$pages[$key];
	}


	/**
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return static[]
	 */
	public static function getList( string $site_id, Locale $locale ): array
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
	public function getKey(): string
	{
		return $this->getSite()->getId() . ':' . $this->getLocale() . ':' . $this->getId();
	}

	/**
	 * @return bool
	 */
	public function isCurrent(): bool
	{
		$current_page = Mvc::getCurrentPage();

		if(
			$current_page &&
			$current_page->getId() == $this->getId() &&
			$current_page->getSiteId() == $this->getSiteId() &&
			$current_page->getLocale()->toString() == $this->getLocale()->toString()
		) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isInCurrentPath(): bool
	{
		$current_page = Mvc::getCurrentPage();

		if(
			!$current_page ||
			$current_page->getSiteId() != $this->getSiteId() ||
			$current_page->getLocale()->toString() != $this->getLocale()->toString()
		) {
			return false;
		}

		$c_path = $current_page->getPath();

		return in_array( $this->getId(), $c_path );

	}


	/**
	 * @return string
	 */
	public function getSiteId(): string
	{
		return $this->site_id;
	}

	/**
	 * @param string $site_id
	 */
	public function setSiteId( string $site_id ): void
	{
		$this->site_id = $site_id;
	}

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite(): Mvc_Site_Interface
	{
		$site_class_name = Mvc_Factory::getSiteClassName();

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 */
		return $site_class_name::get( $this->site_id );
	}

	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( Mvc_Site_Interface $site ): void
	{
		$this->site_id = $site->getId();
	}

	/**
	 *
	 * @return Locale
	 */
	public function getLocale(): Locale
	{
		return $this->locale;
	}

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ): void
	{
		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id ): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function getIsDeactivatedByDefault(): bool
	{
		if(
			$this->getParent() &&
			!$this->getParent()->getIsActive()
		) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function getIsActive(): bool
	{
		if( $this->getIsDeactivatedByDefault() ) {
			return false;
		}

		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void
	{
		$this->is_active = $is_active;
	}

	/**
	 * @param string $relative_path_fragment
	 */
	public function setRelativePathFragment( string $relative_path_fragment ): void
	{
		$this->relative_path_fragment = $relative_path_fragment;


		$parent = $this->getParent();
		if(
			$parent &&
			$parent->getRelativePath()
		) {
			$this->relative_path = $parent->getRelativePath() . '/' . $this->relative_path_fragment;
		} else {
			$this->relative_path = $this->relative_path_fragment;

		}

		foreach( $this->getChildren() as $ch ) {
			$ch->setRelativePathFragment( $ch->getRelativePathFragment() );

		}
	}


	/**
	 * @return string
	 */
	public function getRelativePathFragment(): string
	{
		return $this->relative_path_fragment;
	}


	/**
	 * @return string
	 */
	public function getRelativePath(): string
	{
		return $this->relative_path;
	}


	/**
	 * @param string $relative_path
	 */
	public function setRelativePath( string $relative_path ): void
	{
		$this->relative_path = $relative_path;
	}

	/**
	 * @return bool
	 */
	public function isSSLRequiredByDefault(): bool
	{
		if(
			$this->getParent() &&
			$this->getParent()->getSSLRequired()
		) {
			return true;
		}

		if( $this->getSite()->getLocalizedData( $this->getLocale() )->getSSLRequired() ) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool
	{
		if( $this->isSSLRequiredByDefault() ) {
			return true;
		}

		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ): void
	{
		$this->SSL_required = $SSL_required;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( string $icon ): void
	{
		$this->icon = $icon;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle(): string
	{
		return $this->menu_title;
	}

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle( string $menu_title ): void
	{
		$this->menu_title = $menu_title;
	}


	/**
	 * @return string
	 */
	public function getBreadcrumbTitle(): string
	{
		return $this->breadcrumb_title;
	}

	/**
	 * @param string $breadcrumb_title
	 */
	public function setBreadcrumbTitle( string $breadcrumb_title ): void
	{
		$this->breadcrumb_title = $breadcrumb_title;
	}


	/**
	 * @return array
	 */
	public function getHttpHeaders(): array
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
	public function setHttpHeaders( array $http_headers ): void
	{
		$this->http_headers = $http_headers;
	}

	/**
	 * @param string|callable $output
	 */
	public function setOutput( string|callable $output ): void
	{
		$this->output = $output;
		$this->content = [];
	}

	/**
	 * @return string|callable|null
	 */
	public function getOutput(): string|callable|null
	{
		return $this->output;
	}

	/**
	 * @param bool $is_secret
	 */
	public function setIsSecret( bool $is_secret ): void
	{
		$this->is_secret = $is_secret;
	}

	/**
	 * @return bool
	 */
	public function isSecretByDefault(): bool
	{
		if( $this->getSite()->getIsSecret() ) {
			return true;
		}

		if( ($parent = $this->getParent()) ) {
			if( $parent->getIsSecret() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function getIsSecret(): bool
	{
		if( $this->isSecretByDefault() ) {
			return true;
		}

		return $this->is_secret;
	}

	/**
	 * @return string
	 */
	public function getLayoutScriptName(): string
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
	public function setLayoutScriptName( string $layout_script_name ): void
	{
		$this->layout_script_name = $layout_script_name;
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath(): string
	{
		return $this->getSite()->getLayoutsPath();
	}

	/**
	 *
	 */
	public function initializeLayout(): void
	{
		Mvc_Layout::setCurrentLayout(
			Mvc_Factory::getLayoutInstance(
				$this->getLayoutsPath(),
				$this->getLayoutScriptName()
			)
		);

	}


	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent(): array
	{
		return $this->content;
	}

	/**
	 * @param Mvc_Page_Content_Interface[] $contents
	 */
	public function setContent( array $contents ): void
	{
		$this->content = [];

		foreach( $contents as $c ) {
			$this->addContent( $c );
		}
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content ): void
	{
		$this->output = '';

		$content->setPage( $this );

		$this->content[] = $content;
	}

	/**
	 * @param int $index
	 */
	public function removeContent( int $index ): void
	{
		unset( $this->content[$index] );

		$this->content = array_values( $this->content );
	}


	/**
	 *
	 * @return Mvc_Page_MetaTag_Interface[]
	 */
	public function getMetaTags(): array
	{
		$meta_tags = [];

		foreach( $this->getSite()->getLocalizedData( $this->getLocale() )->getDefaultMetaTags() as $mt ) {
			$key = $mt->getAttribute() . ':' . $mt->getAttributeValue();
			if( $key == ':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}

		foreach( $this->meta_tags as $mt ) {
			$key = $mt->getAttribute() . ':' . $mt->getAttributeValue();
			if( $key == ':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}

		return $meta_tags;
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( array $meta_tags ): void
	{
		$this->meta_tags = [];

		foreach( $meta_tags as $meta_tag ) {
			$this->addMetaTag( $meta_tag );
		}
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag ): void
	{

		$meta_tag->setPage( $this );
		$this->meta_tags[] = $meta_tag;
	}

	/**
	 *
	 */
	public function __wakeup()
	{
		foreach( $this->content as $cnt ) {
			$cnt->setPage( $this );
		}

		foreach( $this->meta_tags as $mt ) {
			$mt->setPage( $this );
		}
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{

		$data = get_object_vars( $this );

		foreach( $data as $k => $v ) {
			if(
				$k == 'content' ||
				$k == 'meta_tags' ||
				$k[0] == '_'
			) {
				unset( $data[$k] );
			}
		}

		unset( $data['relative_path'] );
		unset( $data['parent_id'] );
		unset( $data['children'] );
		unset( $data['site_id'] );
		unset( $data['locale'] );
		unset( $data['relative_path_fragment'] );
		unset( $data['original_relative_path_fragment'] );


		$data['meta_tags'] = [];
		foreach( $this->meta_tags as $meta_tag ) {
			$data['meta_tags'][] = $meta_tag->toArray();
		}

		if(
		!$this->getOutput()
		) {
			unset( $data['output'] );

			$data['contents'] = [];
			foreach( $this->content as $content ) {
				$data['contents'][] = $content->toArray();
			}
		} else {
			unset( $data['layout_script_name'] );
		}


		return $data;
	}

}