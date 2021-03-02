<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Application_Module_Manifest extends BaseObject
{

	/**
	 * @var string
	 */
	protected static string $manifest_file_name = 'manifest.php';

	/**
	 *
	 * @var string
	 */
	protected string $_name = '';

	//--------------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected string $vendor = '';

	/**
	 * @var string
	 */
	protected string $version = '';


	/**
	 *
	 * @var string
	 */
	protected string $label = '';

	/**
	 *
	 * @var string
	 */
	protected string $description = '';

	/**
	 * @var array
	 */
	protected array $ACL_actions = [];

	/**
	 * @var bool
	 */
	protected bool $is_mandatory = false;

	//--------------------------------------------------------------------------


	/**
	 * @var array
	 */
	protected array $pages = [];

	/**
	 * @var array
	 */
	protected array $menu_items = [];

	//--------------------------------------------------------------------------

	/**
	 * @var callable
	 */
	protected static $compatibility_checker;

	/**
	 * @return callable
	 */
	public static function getCompatibilityChecker(): callable
	{
		return static::$compatibility_checker;
	}

	/**
	 * @param callable $compatibility_checker
	 */
	public static function setCompatibilityChecker( callable $compatibility_checker )
	{
		static::$compatibility_checker = $compatibility_checker;
	}


	/**
	 * @return string
	 */
	public static function getManifestFileName(): string
	{
		return static::$manifest_file_name;
	}

	/**
	 * @param string $manifest_file_name
	 */
	public static function setManifestFileName( string $manifest_file_name )
	{
		static::$manifest_file_name = $manifest_file_name;
	}


	/**
	 * @param ?string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function __construct( ?string $module_name = null )
	{
		if( !$module_name ) {
			return;
		}

		$this->_name = $module_name;

		$manifest_data = $this->readManifestData();
		$this->checkManifestData( $manifest_data );
		$this->setupProperties( $manifest_data );


	}

	/**
	 * @return array
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function readManifestData(): array
	{
		$module_dir = $this->getModuleDir();

		if( !IO_Dir::exists( $module_dir ) ) {
			throw new Application_Modules_Exception(
				'Directory \'' . $module_dir . '\' does not exist',
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}


		$manifest_file = $module_dir . static::$manifest_file_name;

		if( !IO_File::isReadable( $manifest_file ) ) {
			throw new Application_Modules_Exception(
				'Module manifest file \'' . $manifest_file . '\' does not exist or is not readable. ',
				Application_Modules_Exception::CODE_MANIFEST_IS_NOT_READABLE
			);
		}

		/** @noinspection PhpIncludeInspection */
		$manifest_data = require $manifest_file;

		return $manifest_data;
	}

	/**
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function checkManifestData( array $manifest_data )
	{
		if( !is_array( $manifest_data ) ) {
			throw new Application_Modules_Exception(
				'Manifest data must be array (Module: \'' . $this->_name . '\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if( empty( $manifest_data['label'] ) ) {
			throw new Application_Modules_Exception(
				'Module label not set! (\'label\' array key does not exist, or is empty) (Module: \'' . $this->_name . '\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}
	}

	/**
	 *
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function setupProperties( array $manifest_data )
	{

		foreach( $manifest_data as $key => $val ) {
			if( !$this->objectHasProperty( $key ) ) {
				throw new Application_Modules_Exception(
					'Unknown manifest property \'' . $key . '\' (Module: \'' . $this->_name . '\') ',
					Application_Modules_Exception::CODE_MANIFEST_NONSENSE
				);
			}

			$this->{$key} = $val;

		}
	}


	/**
	 *
	 * @return string
	 */
	public function getModuleDir(): string
	{
		return Application_Modules::getModuleDir( $this->_name );
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return Application_Modules::getModuleRootNamespace() . '\\' . str_replace( '.', '\\', $this->_name ) . '\\';
	}

	/**
	 * @return string
	 */
	public function getVendor(): string
	{
		return $this->vendor;
	}

	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param bool $translate_description
	 * @param ?Locale $translate_locale
	 *
	 * @return array
	 */
	public function getACLActions( bool $translate_description = true, ?Locale $translate_locale = null ): array
	{
		if( !$translate_description ) {
			return $this->ACL_actions;
		}

		$res = [];

		foreach( $this->ACL_actions as $action => $description ) {
			$res[$action] = Tr::_( $description, [], $this->getName(), $translate_locale );
		}

		return $res;
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	public function hasACLAction( string $action ): bool
	{
		return array_key_exists( $action, $this->ACL_actions );
	}

	/**
	 * @return bool
	 */
	public function isCompatible(): bool
	{
		if( !static::$compatibility_checker ) {
			return true;
		}

		$checker = static::$compatibility_checker;

		return $checker( $this );
	}

	/**
	 * @return bool
	 */
	public function isMandatory(): bool
	{
		return $this->is_mandatory;
	}


	/**
	 *
	 * @return array
	 */
	public function getPagesRaw(): array
	{
		return $this->pages;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param null|string|bool $translator_namespace
	 *
	 * @return Mvc_Page[]
	 */
	public function getPages( Mvc_Site_Interface $site, Locale $locale, null|string|bool $translator_namespace = null ): array
	{

		if( $translator_namespace === null ) {
			$translator_namespace = $this->getName();
		}

		if(
			!isset( $this->pages[$site->getId()] ) ||
			!is_array( $this->pages[$site->getId()] )
		) {
			return [];
		}

		$pages = [];

		$translate_fields = [
			'name',
			'title',
			'menu_title',
			'breadcrumb_title',
		];

		foreach( $this->pages[$site->getId()] as $page_id => $page_data ) {

			$page_data['id'] = $page_id;

			if( isset( $page_data['contents'] ) ) {
				foreach( $page_data['contents'] as $i => $content ) {
					if( empty( $content['module_name'] ) ) {
						$page_data['contents'][$i]['module_name'] = $this->getName();
					}
				}
			}

			if( $translator_namespace !== false ) {
				foreach( $translate_fields as $tf ) {
					if( !empty( $page_data[$tf] ) ) {
						$page_data[$tf] = Tr::_( $page_data[$tf], [], $translator_namespace, $locale );
					}
				}
			}

			$page = Mvc_Factory::getPageInstance()::createByData( $site, $locale, $page_data );

			$pages[$page_id] = $page;

		}

		return $pages;
	}

	/**
	 * @return array
	 */
	public function getMenuItemsRaw(): array
	{
		return $this->menu_items;
	}

	/**
	 *
	 * @param string $menu_set_name
	 * @param ?string $translator_namespace
	 *
	 * @return Navigation_Menu_Item[]
	 */
	public function getMenuItems( string $menu_set_name, ?string $translator_namespace = null ): array
	{

		if( !isset( $this->menu_items[$menu_set_name] ) ) {
			return [];
		}

		if( $translator_namespace === null ) {
			$translator_namespace = $this->getName();
		}

		$res = [];
		foreach( $this->menu_items[$menu_set_name] as $menu_id => $menu_items_data ) {
			foreach( $menu_items_data as $item_id => $menu_item_data ) {
				$label = '';

				if( !empty( $menu_item_data['label'] ) ) {
					if( $translator_namespace !== false ) {
						$label = Tr::_( $menu_item_data['label'], [], $translator_namespace );
					} else {
						$label = $menu_item_data['label'];
					}
				}

				$menu_item = new Navigation_Menu_Item( $item_id, $label );
				$menu_item->setMenuId( $menu_id );
				$menu_item->setData( $menu_item_data );

				$res[] = $menu_item;
			}
		}

		return $res;
	}


	/**
	 * @return bool
	 */
	public function isInstalled(): bool
	{
		return Application_Modules::moduleIsInstalled( $this->_name );
	}

	/**
	 * @return bool
	 */
	public function isActivated(): bool
	{
		return Application_Modules::moduleIsActivated( $this->_name );
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$res = [
			'vendor'       => $this->getVendor(),
			'version'      => $this->getVersion(),
			'label'        => $this->getLabel(),
			'description'  => $this->getDescription(),
			'is_mandatory' => $this->isMandatory()
		];

		foreach( $this->getACLActions( false ) as $action => $description ) {
			if( !isset( $res['ACL_actions'] ) ) {
				$res['ACL_actions'] = [];
			}

			$res['ACL_actions'][$action] = $description;
		}

		$cleanupArray = function( $data ) use ( &$cleanupArray ) {
			foreach( $data as $k => $v ) {
				if( !$v ) {
					unset( $data[$k] );
					continue;
				}

				if( is_array( $v ) ) {
					$data[$k] = $cleanupArray( $v );
					if( !$data[$k] ) {
						unset( $data[$k] );
					}
				}
			}

			return $data;
		};

		foreach( $this->pages as $site_id => $pages ) {
			if( !isset( $res['pages'] ) ) {
				$res['pages'] = [];
			}

			if( !isset( $res['pages'][$site_id] ) ) {
				$res['pages'][$site_id] = [];
			}

			foreach( $pages as $page_id => $page ) {
				$page_data = $page->toArray();
				unset( $page_data['id'] );
				$page_data['relative_path_fragment'] = $page->getRelativePathFragment();

				$page_data = $cleanupArray( $page_data );

				$res['pages'][$site_id][$page_id] = $page_data;
			}

		}

		foreach( $this->menu_items as $namespace_id => $menus ) {
			$namespace = Navigation_MenuSet::get( $namespace_id );
			if( !$namespace ) {
				continue;
			}

			if( !isset( $res['menu_items'] ) ) {
				$res['menu_items'] = [];
			}

			$namespace = $namespace->getName();

			if( !isset( $res['menu_items'][$namespace] ) ) {
				$res['menu_items'][$namespace] = [];
			}

			foreach( $menus as $menu_id => $items ) {

				if( !isset( $res['menu_items'][$namespace][$menu_id] ) ) {
					$res['menu_items'][$namespace][$menu_id] = [];
				}

				foreach( $items as $item_id => $item ) {

					$item = $item->toArray();
					$item = $cleanupArray( $item );

					$res['menu_items'][$namespace][$menu_id][$item_id] = $item;

				}

			}
		}

		return $res;
	}

	/**
	 *
	 */
	public function saveDatafile(): void
	{
		$module_dir = $this->getModuleDir();

		$data = new Data_Array( $this->toArray() );

		IO_File::write( $module_dir . static::getManifestFileName(), '<?php return ' . $data->export() );

		Cache::resetOPCache();
	}

}