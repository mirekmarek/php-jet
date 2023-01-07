<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Base/Interface.php';
require_once 'Base/LocalizedData.php';

/**
 *
 */
class MVC_Base extends BaseObject implements MVC_Base_Interface
{

	/**
	 * @var MVC_Base[]
	 */
	protected static array $bases = [];

	/**
	 * @var ?array
	 */
	protected static array|null $maps = null;

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
	 * @var string|null
	 */
	protected string|null $views_path = null;

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
	 * @var MVC_Base_LocalizedData[]
	 */
	protected array $localized_data = [];

	/**
	 * @var callable|null
	 */
	protected $initializer;

	/**
	 * @return array
	 */
	protected static function getMaps(): array
	{
		if( static::$maps !== null ) {
			return static::$maps;
		}

		$map = MVC_Cache::loadBaseMaps();

		if( is_array( $map ) ) {
			static::$maps = $map;

			return static::$maps;
		}

		static::$maps = [
			'files' => [],
			'URL'   => []
		];

		Debug_Profiler::blockStart( 'Load bases - maps' );

		$dirs = IO_Dir::getSubdirectoriesList( SysConf_Path::getBases() );

		foreach( $dirs as $id ) {

			$data_file_path = static::getBaseDataFilePath( $id );

			if( !IO_File::exists( $data_file_path ) ) {
				continue;
			}

			if( isset( static::$maps['files'][$id] ) ) {
				throw new MVC_Page_Exception(
					'Duplicate base: \'' . $id . '\' ',
					MVC_Page_Exception::CODE_DUPLICATES_PAGE_ID
				);

			}


			static::$maps['files'][$id] = $data_file_path;

			$data = require $data_file_path;

			$base = static::_createByData( $data );

			foreach( $base->getLocales() as $locale ) {
				$l_data = $base->getLocalizedData( $locale );

				foreach( $l_data->getURLs() as $URL ) {
					static::$maps['URL'][$URL] = [
						$base->getId(),
						$locale->toString()
					];
				}
			}
		}


		uksort(
			static::$maps['URL'],
			function( $a, $b ) {
				return strlen( $b ) - strlen( $a );
			}
		);


		MVC_Cache::saveBaseMaps( static::$maps );

		Debug_Profiler::blockEnd( 'Load bases - maps' );

		return static::$maps;
	}

	/**
	 * @return array
	 */
	public static function _getUrlMap(): array
	{
		return static::getMaps()['URL'];
	}


	/**
	 * @return MVC_Base[]
	 *
	 * @throws MVC_Page_Exception
	 */
	public static function _getBases(): array
	{
		$map = static::getMaps()['files'];

		foreach( $map as $id => $path ) {
			if( !isset( static::$bases[$id] ) ) {
				static::_get( $id );
			}
		}

		return static::$bases;
	}


	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function _createByData( array $data ): static
	{

		/**
		 * @var MVC_Base $base
		 */
		$base = Factory_MVC::getBaseInstance();
		$base->id = $data['id'];
		unset( $data['id'] );

		$base->setData( $data );

		return $base;
	}

	/**
	 * @param array $data
	 */
	protected function setData( array $data ): void
	{
		foreach( $data['localized_data'] as $locale_str => $localized_data ) {
			$locale = new Locale( $locale_str );

			/**
			 * @var MVC_Base_LocalizedData_Interface $ld_class_name
			 */
			$ld_class_name = Factory_MVC::getBaseLocalizedClassName();

			$this->localized_data[$locale_str] = $ld_class_name::_createByData( $this, $locale, $localized_data );

		}
		unset( $data['localized_data'] );

		$data['is_active'] = !empty( $data['is_active'] );
		$data['is_default'] = !empty( $data['is_default'] );

		foreach( $data as $key => $val ) {
			$this->{$key} = $val;
		}

	}

	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function _get( string $id ): static|null
	{
		if( isset( static::$bases[$id] ) ) {
			return static::$bases[$id];
		}

		$map = static::getMaps()['files'];
		if( !isset( $map[$id] ) ) {
			return null;
		}

		$data = require $map[$id];

		static::$bases[$id] = static::_createByData( $data );

		return static::$bases[$id];
	}
	

	/**
	 *
	 * @param callable $initializer
	 */
	public function setInitializer( callable $initializer ): void
	{
		$this->initializer = $initializer;
	}

	/**
	 *
	 * @return callable|null
	 */
	public function getInitializer(): callable|null
	{
		return $this->initializer;
	}


	/**
	 * @param string $id
	 *
	 * @return string
	 */
	protected static function getBaseDataFilePath( string $id ): string
	{
		return SysConf_Path::getBases() . $id . '/' . SysConf_Jet_MVC::getBaseDataFileName();
	}


	/**
	 *
	 * @param Locale $locale
	 *
	 * @return MVC_Base_LocalizedData_Interface
	 */
	public function addLocale( Locale $locale ): MVC_Base_LocalizedData_Interface
	{
		if( isset( $this->localized_data[(string)$locale] ) ) {
			return $this->localized_data[(string)$locale];
		}

		$new_ld = Factory_MVC::getBaseLocalizedInstance( $locale );
		$new_ld->setLocale( $locale );
		$new_ld->setBase( $this );

		$this->localized_data[(string)$locale] = $new_ld;


		return $new_ld;
	}

	/**
	 *
	 * @param bool $get_as_string (optional), default: false
	 *
	 * @return Locale[]
	 */
	public function getLocales( bool $get_as_string = false ): array
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
	 *
	 * @param bool $get_as_string (optional), default: false
	 *
	 * @return Locale[]
	 */
	public function getActiveLocales( bool $get_as_string = false ): array
	{

		$result = [];

		foreach( $this->localized_data as $ld ) {
			if(!$ld->getIsActive()) {
				continue;
			}
			$locale = $ld->getLocale();

			$lc_str = (string)$locale;
			$result[$lc_str] = $get_as_string ? $lc_str : $locale;
		}


		return $result;
	}

	/**
	 * @param array $order
	 */
	public function sortLocales( array $order ): void
	{
		$e_locales = $this->getLocales( true );

		foreach( $order as $i => $l ) {
			if( !in_array( $l, $e_locales ) ) {
				unset( $order[$i] );
			}
		}

		$order = array_values( $order );

		foreach( $e_locales as $l ) {
			if( !in_array( $l, $order ) ) {
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
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 */
	public function setId( string $id ): void
	{
		$this->id = $id;
	}

	/**
	 *
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
	 * @param string $path
	 */
	public function setLayoutsPath( string $path ): void
	{
		$this->layouts_path = $path;
	}

	/**
	 * @return string
	 */
	public function getLayoutsPath(): string
	{
		if( $this->layouts_path !== null ) {
			return $this->layouts_path;
		}

		return $this->getBasePath() . SysConf_Jet_MVC::getBaseLayoutsDir() . '/';
	}


	/**
	 * @param string $path
	 */
	public function setViewsPath( string $path ): void
	{
		$this->views_path = $path;
	}

	/**
	 * @return string
	 */
	public function getViewsPath(): string
	{
		if( $this->views_path !== null ) {
			return $this->views_path;
		}

		return $this->getBasePath() . SysConf_Jet_MVC::getBaseViewsDir() . '/';
	}


	/**
	 * @param string $path
	 */
	public function setBasePath( string $path ): void
	{
		$this->base_path = $path;
	}

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath(): string
	{
		if( $this->base_path !== null ) {
			return $this->base_path;
		}

		return SysConf_Path::getBases() . $this->id . '/';
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
	public function getIsSecret(): bool
	{
		return $this->is_secret;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool
	{
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
	 *
	 * @return Locale|null
	 */
	public function getDefaultLocale(): Locale|null
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
	public function getHasLocale( Locale $locale ): bool
	{
		return isset( $this->localized_data[$locale->toString()] );
	}

	/**
	 * @param Locale $locale
	 *
	 * @return MVC_Base_LocalizedData_Interface
	 */
	public function getLocalizedData( Locale $locale ): MVC_Base_LocalizedData_Interface
	{
		return $this->localized_data[$locale->toString()];
	}

	/**
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale ): void
	{
		if( !isset( $this->localized_data[(string)$locale] ) ) {
			return;
		}

		foreach( $this->localized_data as $ld ) {
			$o_locale = $ld->getLocale();

			if( (string)$o_locale == (string)$locale ) {
				unset( $this->localized_data[(string)$locale] );
			}

		}
	}

	/**
	 * @return bool
	 */
	public function getIsActive(): bool
	{
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
	 * @param Locale|null $locale (optional)
	 *
	 * @return string
	 */
	public function getPagesDataPath( Locale|null $locale = null ): string
	{
		$path = $this->getBasePath() . SysConf_Jet_MVC::getBasePagesDir() . '/';

		if( !$locale ) {
			return $path;
		}

		return $path . $locale . '/';

	}


	/**
	 * @return bool
	 */
	public function getIsDefault(): bool
	{
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault( bool $is_default ): void
	{
		$this->is_default = $is_default;
	}

	/**
	 * @param Locale|null $locale (optional)
	 *
	 * @return MVC_Page_Interface
	 */
	public function getHomepage( Locale|null $locale = null ): MVC_Page_Interface
	{
		if( !$locale ) {
			$locale = MVC::getLocale();
		}

		/**
		 * @var MVC_Page $class_name
		 */
		$class_name = Factory_MVC::getPageClassName();

		return $class_name::_get( MVC::HOMEPAGE_ID, $locale, $this->getId() );
	}

	/**
	 * @throws IO_File_Exception
	 */
	public function saveDataFile(): void
	{
		$data = $this->toArray();

		IO_File::writeDataAsPhp( $this->getBasePath() . SysConf_Jet_MVC::getBaseDataFileName(), $data );

		MVC_Cache::reset();
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{

		$data = get_object_vars( $this );

		foreach( $data as $k => $v ) {
			if(
				$k[0] == '_' ||
				$v===null
			) {
				unset( $data[$k] );
			}
		}

		$data['localized_data'] = [];

		foreach( $this->localized_data as $locale_str => $ld ) {
			$data['localized_data'][$locale_str] = $ld->toArray();
			unset( $data['localized_data'][$locale_str]['base_id'] );
			unset( $data['localized_data'][$locale_str]['locale'] );
		}


		return $data;
	}

	/**
	 *
	 */
	public function __wakeup()
	{
		foreach( $this->localized_data as $locale_str => $ld ) {
			$locale = new Locale( $locale_str );
			$ld->setBase( $this );
			$ld->setLocale( $locale );
		}
	}
}