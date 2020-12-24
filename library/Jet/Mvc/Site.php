<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Site/Interface.php';
require_once 'Site/LocalizedData.php';

/**
 *
 */
class Mvc_Site extends BaseObject implements Mvc_Site_Interface, BaseObject_Cacheable_Interface
{

	use BaseObject_Cacheable_Trait;

	/**
	 * @var string
	 */
	protected static string $site_data_file_name = 'site_data.php';

	/**
	 * @var string
	 */
	protected static string $pages_dir = 'pages';

	/**
	 * @var string
	 */
	protected static string $layouts_dir = 'layouts';

	/**
	 * @var Mvc_Site[]|null
	 */
	protected static array|null $sites = null;

	/**
	 * @var Mvc_Site_LocalizedData_Interface[]
	 */
	protected static array|null $URL_map = null;

	/**
	 *
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
	 * @var string|null
	 */
	protected string|null $base_path = null;

	/**
	 * @var string|null
	 */
	protected string|null $layouts_path = null;

	/**
	 * @var bool
	 */
	protected bool $is_secret = false;

	/**
	 *
	 * @var bool
	 */
	protected bool $is_default = false;
	/**
	 *
	 * @var bool
	 */
	protected bool $is_active = false;

	/**
	 * @var bool
	 */
	protected bool $SSL_required = false;

	/**
	 *
	 * @var Mvc_Site_LocalizedData[]
	 */
	protected array $localized_data = [];

	/**
	 * @var callable|null
	 */
	protected $initializer;


	/**
	 * @return array
	 */
	public static function loadSitesData() : array
	{

		Debug_Profiler::blockStart('Load sites data');
		$sites = [];

		$dirs = IO_Dir::getSubdirectoriesList( SysConf_PATH::SITES() );

		foreach( $dirs as $id ) {

			$data_file_path = static::getSiteDataFilePath( $id );

			if( !IO_File::exists( $data_file_path ) ) {
				continue;
			}


			/** @noinspection PhpIncludeInspection */
			$site_data = require $data_file_path;
			$site_data['id'] = $id;
			$sites[$id] = $site_data;
		}
		Debug_Profiler::blockEnd('Load sites data');

		return $sites;
	}


	/**
	 * @return Mvc_Site[]
	 *
	 * @throws Mvc_Page_Exception
	 */
	public static function loadSites() : array
	{

		if(static::$sites===null) {
			if( static::getCacheLoadEnabled() ) {

				$loader = static::$cache_loader;
				$sites = $loader();
				if($sites) {
					Debug_Profiler::message('cache hit');

					static::$sites = $sites;
					return $sites;
				}
			}


			$sites_data = static::loadSitesData();

			Debug_Profiler::blockStart('Create site instances');
			static::$sites = [];

			foreach( $sites_data as $data ) {
				$site = Mvc_Site::createByData( $data );

				if(isset(static::$sites[$site->getId()])) {
					throw new Mvc_Page_Exception(
						'Duplicate site: \''.$site->getId().'\' ',
						Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID
					);

				}

				static::$sites[$site->getId()] = $site;
			}

			if(
				static::$sites &&
				static::getCacheSaveEnabled()
			) {

				$saver = static::$cache_saver;
				$saver( static::$sites );
			}

			Debug_Profiler::blockEnd('Create site instances');
		}

		return static::$sites;
	}

	/**
	 *
	 * @param Mvc_Site[] $sites
	 */
	public static function setSites( array $sites ) : void
	{
		static::$sites = [];

		foreach($sites as $site) {
			static::$sites[$site->getId()] = $site;
		}
	}


	/**
	 * @param array  $data
	 *
	 * @return static
	 */
	public static function createByData( array $data ) : static
	{

		/**
		 * @var Mvc_Site $site
		 */
		$site = Mvc_Factory::getSiteInstance();
		$site->id = $data['id'];
		unset($data['id']);

		$site->setData( $data );

		return $site;
	}

	/**
	 * @param array $data
	 */
	protected function setData( array $data ) : void
	{
		foreach( $data['localized_data'] as $locale_str => $localized_data ) {
			$locale = new Locale( $locale_str );

			$this->localized_data[$locale_str] = Mvc_Site_LocalizedData::createByData( $this, $locale, $localized_data );

		}
		unset($data['localized_data']);

		$data['is_active'] = !empty($data['is_active']);
		$data['is_default'] = !empty($data['is_default']);

		foreach( $data as $key=>$val ) {
			$this->{$key} = $val;
		}

	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface[]
	 */
	public static function getUrlMap() : array
	{
		if(static::$URL_map!==null) {
			return static::$URL_map;
		}

		static::loadSites();
		static::$URL_map = [];

		foreach( static::$sites as $site ) {
			foreach( $site->getLocales() as $locale ) {
				$l_data = $site->getLocalizedData($locale);

				foreach($l_data->getURLs() as $URL) {
					static::$URL_map[$URL] = $l_data;
				}
			}
		}


		return static::$URL_map;
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ) : static|null
	{
		static::loadSites();

		if( !isset( static::$sites[$id] ) ) {
			return null;
		}

		return static::$sites[$id];
	}


	/**
	 *
	 * @param callable $initializer
	 */
	public function setInitializer( callable $initializer ) : void
	{
		$this->initializer = $initializer;
	}

	/**
	 *
	 * @return callable|null
	 */
	public function getInitializer() : callable|null
	{
		return $this->initializer;
	}


	/**
	 * @param string $id
	 *
	 * @return string
	 */
	protected static function getSiteDataFilePath( string $id ) : string
	{
		return SysConf_PATH::SITES().$id.'/'.static::$site_data_file_name;
	}


	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function addLocale( Locale $locale ) : Mvc_Site_LocalizedData_Interface
	{
		if( isset( $this->localized_data[(string)$locale] ) ) {
			return $this->localized_data[(string)$locale];
		}

		$new_ld = Mvc_Factory::getSiteLocalizedInstance( $locale );
		$new_ld->setLocale( $locale );
		$new_ld->setSite( $this );

		$this->localized_data[(string)$locale] = $new_ld;


		return $new_ld;
	}

	/**
	 *
	 * @param bool $get_as_string (optional), default: false
	 *
	 * @return Locale[]
	 */
	public function getLocales( bool $get_as_string = false ) : array
	{

		$result = [];

		foreach( $this->localized_data as $ld ) {
			$locale = $ld->getLocale();

			$lc_str = (string)$locale;
			$result[$lc_str] = $get_as_string ? $lc_str : $locale;
		}


		return $result;
	}

	/**
	 * @param array $order
	 */
	public function sortLocales( array $order ) : void
	{
		$e_locales = $this->getLocales( true );

		foreach( $order as $i=>$l ) {
			if(!in_array( $l, $e_locales )) {
				unset( $order[$i] );
			}
		}

		$order = array_values($order);

		foreach( $e_locales as $l ) {
			if(!in_array( $l, $order )) {
				$order[] = $l;
			}
		}

		$o_ld = $this->localized_data;
		$this->localized_data = [];

		foreach( $order as $l ) {
			$this->localized_data[$l] = $o_ld[$l];
		}

	}


	/**
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 */
	public function setId( string $id ) : void
	{
		$this->id = $id;
	}

	/**
	 *
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) : void
	{
		$this->name = $name;
	}


	/**
	 * @param string $path
	 */
	public function setLayoutsPath( string $path ) : void
	{
		$this->layouts_path = $path;
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath() : string
	{
		if($this->layouts_path!==null) {
			return $this->layouts_path;
		}

		return $this->getBasePath().static::$layouts_dir.'/';
	}

	/**
	 * @param string $path
	 */
	public function setBasePath( string $path ) : void
	{
		$this->base_path = $path;
	}

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath() : string
	{
		if($this->base_path!==null) {
			return $this->base_path;
		}

		return SysConf_PATH::SITES().$this->id.'/';
	}


	/**
	 * @param bool $is_secret
	 */
	public function setIsSecret( bool $is_secret ) : void
	{
		$this->is_secret  = $is_secret;
	}

	/**
	 * @return bool
	 */
	public function getIsSecret() : bool
	{
		return $this->is_secret;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired() : bool
	{
		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ) : void
	{
		$this->SSL_required = $SSL_required;
	}


	/**
	 *
	 * @return Locale|null
	 */
	public function getDefaultLocale() : Locale|null
	{
		foreach( $this->localized_data as $ld ) {
			return $ld->getLocale();
		}

		return null;
	}

	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	public function getHasLocale( Locale $locale ) : bool
	{
		return isset( $this->localized_data[$locale->toString()] );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getLocalizedData( Locale $locale ) : Mvc_Site_LocalizedData_Interface
	{
		return $this->localized_data[$locale->toString()];
	}

	/**
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale ) : void
	{
		if( !isset( $this->localized_data[(string)$locale] ) ) {
			return;
		}

		foreach( $this->localized_data as $ld ) {
			$o_locale = $ld->getLocale();

			if( (string)$o_locale==(string)$locale ) {
				unset( $this->localized_data[(string)$locale] );
				continue;
			}

		}
	}

	/**
	 * @return bool
	 */
	public function getIsActive() : bool
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ) : void
	{
		$this->is_active = (bool)$is_active;
	}

	/**
	 * @param Locale|null $locale (optional)
	 *
	 * @return string
	 */
	public function getPagesDataPath( Locale|null $locale = null ) : string
	{
		$path = $this->getBasePath().static::$pages_dir.'/';

		if( !$locale ) {
			return $path;
		}

		return $path.$locale.'/';

	}

	/**
	 *
	 * @return static|null
	 */
	public static function getDefaultSite() : static|null
	{
		$sites = static::loadSites();

		foreach( $sites as $site ) {
			if( $site->getIsDefault() ) {
				return $site;
			}
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function getIsDefault() : bool
	{
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault( bool $is_default ) : void
	{
		$this->is_default = (bool)$is_default;
	}

	/**
	 * @param Locale|null $locale (optional)
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getHomepage( Locale|null $locale=null ) : Mvc_Page_Interface
	{
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}

		/**
		 * @var Mvc_Page $class_name
		 */
		$class_name = Mvc_Factory::getPageClassName();

		return $class_name::get( Mvc_Page::HOMEPAGE_ID, $locale, $this->getId() );
	}

	/**
	 * @throws IO_File_Exception
	 */
	public function saveDataFile() : void
	{
		$data = $this->toArray();

		IO_File::write(
			$this->getBasePath().static::$site_data_file_name,
			'<?php'.PHP_EOL.'return '.(new Data_Array( $data ))->export()
		);


		if(function_exists('opcache_reset')) {
			opcache_reset();
		}
	}

	/**
	 * @return array
	 */
	public function toArray() : array
	{

		$data = get_object_vars( $this );

		foreach( $data as $k => $v ) {
			if( $k[0]=='_' ) {
				unset( $data[$k] );
			}
		}

		$data['localized_data'] = [];

		foreach( $this->localized_data as $locale_str => $ld ) {
			$data['localized_data'][$locale_str] = $ld->toArray();
			unset( $data['localized_data'][$locale_str]['site_id'] );
			unset( $data['localized_data'][$locale_str]['locale'] );
		}


		return $data;
	}

	/**
	 *
	 */
	public function __wakeup()
	{
		foreach( $this->localized_data as $locale_str=>$ld ) {
			$locale = new Locale($locale_str);
			$ld->setSite( $this );
			$ld->setLocale( $locale );
		}
	}
}