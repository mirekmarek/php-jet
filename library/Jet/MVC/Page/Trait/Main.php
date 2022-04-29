<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait MVC_Page_Trait_Main
{
	/**
	 *
	 * @var ?string
	 */
	protected ?string $base_id = null;
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
	 * @var string
	 */
	protected string $_source_module_name = '';
	
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
	protected string $_data_file_path = '';

	/**
	 * @var bool
	 */
	protected static bool $_translate = true;

	/**
	 *
	 * @param string $page_id
	 * @param Locale $locale
	 * @param string $base_id
	 *
	 * @return static|null
	 */
	public static function _get( string $page_id, Locale $locale, string $base_id ): static|null
	{
		$key = $base_id . ':' . $locale . ':' . $page_id;

		if( isset( static::$pages[$key] ) ) {
			return static::$pages[$key];
		}

		$base_class_name = Factory_MVC::getBaseClassName();

		/**
		 * @var MVC_Base_Interface $base_class_name
		 */

		$base = $base_class_name::_get( $base_id );

		$maps = static::loadMaps( $base, $locale );

		if( !isset( $maps['pages_files_map'][$page_id] ) ) {
			return null;
		}

		$data_file_path = $maps['pages_files_map'][$page_id];

		if( !IO_File::isReadable( $data_file_path ) ) {
			throw new MVC_Page_Exception(
				'Page data file is not readable: ' . $data_file_path,
				MVC_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA
			);
		}

		$data = require $data_file_path;

		$data['id'] = $page_id;
		$data['children'] = $maps['children_map'][$page_id];
		$data['relative_path'] = array_search( $page_id, $maps['relative_path_map'] );
		$data['relative_path_fragment'] = basename( $data['relative_path'] );
		$data['parent_id'] = $maps['parent_map'][$page_id];

		if(
			static::$_translate &&
			isset($maps['translator_dictionary'][$page_id])
		) {

			$translator_dictionary = $maps['translator_dictionary'][$page_id];

			$translate_fields = [
				'name',
				'title',
				'menu_title',
				'breadcrumb_title',
			];
			foreach( $translate_fields as $tf ) {
				if( !empty( $data[$tf] ) ) {
					$data[$tf] = Tr::_( $data[$tf], [], $translator_dictionary, $locale );
				}
			}
		}
		
		$module_name = null;
		if(isset($maps['module'][$page_id])) {
			$module_name = $maps['module'][$page_id];

			if(isset($data['contents'])) {
				foreach( $data['contents'] as $i => $content ) {
					if( empty( $content['module_name'] ) ) {
						$data['contents'][$i]['module_name'] = $module_name;
					}
				}
			}
		}

		$page = static::_createByData( $base, $locale, $data );
		$page->setDataFilePath( $data_file_path );
		if($module_name) {
			$page->setSourceModuleName( $module_name );
		}

		static::$pages[$key] = $page;



		return static::$pages[$key];
	}
	

	/**
	 * @return string
	 */
	public function getBaseId(): string
	{
		return $this->base_id;
	}

	/**
	 * @param string $base_id
	 */
	public function setBaseId( string $base_id ): void
	{
		$this->base_id = $base_id;
	}

	/**
	 * @return MVC_Base_Interface
	 */
	public function getBase(): MVC_Base_Interface
	{
		$base_class_name = Factory_MVC::getBaseClassName();

		/**
		 * @var MVC_Base_Interface $base_class_name
		 */
		return $base_class_name::_get( $this->base_id );
	}

	/**
	 * @param MVC_Base_Interface $base
	 */
	public function setBase( MVC_Base_Interface $base ): void
	{
		$this->base_id = $base->getId();
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
	public function getSourceModuleName(): string
	{
		return $this->_source_module_name;
	}
	
	/**
	 * @param string $_source_module_name
	 */
	public function setSourceModuleName( string $_source_module_name ): void
	{
		$this->_source_module_name = $_source_module_name;
	}
	
	

	/**
	 * @return string
	 */
	public function getKey(): string
	{
		return $this->getBase()->getId() . ':' . $this->getLocale() . ':' . $this->getId();
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

		if( $this->getBase()->getLocalizedData( $this->getLocale() )->getSSLRequired() ) {
			return true;
		}

		return false;
	}


	/**
	 * @return bool
	 */
	public function isCurrent(): bool
	{
		$current_page = MVC::getPage();

		if(
			$current_page &&
			$current_page->getId() == $this->getId() &&
			$current_page->getBaseId() == $this->getBaseId() &&
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
		$current_page = MVC::getPage();

		if(
			!$current_page ||
			$current_page->getBaseId() != $this->getBaseId() ||
			$current_page->getLocale()->toString() != $this->getLocale()->toString()
		) {
			return false;
		}

		$c_path = $current_page->getPath();

		return in_array( $this->getId(), $c_path );

	}

	/**
	 * @param string $data_file_path
	 */
	public function setDataFilePath( string $data_file_path ): void
	{
		$this->_data_file_path = $data_file_path;
	}

	/**
	 * @param bool $actualized
	 * @return string
	 */
	public function getDataFilePath( bool $actualized = false ): string
	{
		if($actualized) {
			return $this->getDataDirPath(true).basename($this->_data_file_path);
		}

		return $this->_data_file_path;
	}

	/**
	 * @param bool $actualized
	 * @return string
	 */
	public function getDataDirPath( bool $actualized = false ): string
	{
		if($actualized && $this->id!=MVC::HOMEPAGE_ID) {
			return dirname($this->_data_file_path, 2).'/'.rawurldecode( $this->getRelativePathFragment() ) . '/';
		}

		return dirname($this->_data_file_path).'/';
	}

}

