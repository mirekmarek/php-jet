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
trait Mvc_Page_Trait_Main
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
	 *
	 * @param string|null $page_id (optional, null = current)
	 * @param Locale|null $locale (optional, null = current)
	 * @param string|null $base_id (optional, null = current)
	 *
	 * @return static|null
	 */
	public static function get( string|null $page_id=null, Locale|null $locale = null, string|null $base_id = null ): static|null
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


		if( !$base_id ) {
			if( !Mvc::getCurrentBase() ) {
				return null;
			}

			$base_id = Mvc::getCurrentBase()->getId();
		}

		$key = $base_id . ':' . $locale . ':' . $page_id;

		if( isset( static::$pages[$key] ) ) {
			return static::$pages[$key];
		}

		$base = Factory_Mvc::getBaseInstance()::get( $base_id );

		$maps = static::loadMaps( $base, $locale );

		if( !isset( $maps['pages_files_map'][$page_id] ) ) {
			return null;
		}

		$data_file_path = $maps['pages_files_map'][$page_id];

		if( !IO_File::isReadable( $data_file_path ) ) {
			throw new Mvc_Page_Exception(
				'Page data file is not readable: ' . $data_file_path,
				Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA
			);
		}

		$data = require $data_file_path;

		$data['id'] = $page_id;
		$data['children'] = $maps['children_map'][$page_id];
		$data['relative_path'] = array_search( $page_id, $maps['relative_path_map'] );
		$data['relative_path_fragment'] = basename( $data['relative_path'] );
		$data['parent_id'] = $maps['parent_map'][$page_id];

		if(isset($maps['translator_namespace'][$page_id])) {

			$translator_namespace = $maps['translator_namespace'][$page_id];

			$translate_fields = [
				'name',
				'title',
				'menu_title',
				'breadcrumb_title',
			];
			foreach( $translate_fields as $tf ) {
				if( !empty( $data[$tf] ) ) {
					$data[$tf] = Tr::_( $data[$tf], [], $translator_namespace, $locale );
				}
			}
		}

		if(isset($maps['module'][$page_id])) {
			$module_name = $maps['module'][$page_id];

			foreach( $data['contents'] as $i => $content ) {
				if( empty( $content['module_name'] ) ) {
					$data['contents'][$i]['module_name'] = $module_name;
				}
			}
		}

		$page = static::createByData( $base, $locale, $data );
		$page->setDataFilePath( $data_file_path );

		static::$pages[$key] = $page;



		return static::$pages[$key];
	}


	/**
	 *
	 * @param string $base_id
	 * @param Locale $locale
	 *
	 * @return static[]
	 */
	public static function getList( string $base_id, Locale $locale ): array
	{
		$base_class_name = Factory_Mvc::getBaseClassName();

		/**
		 * @var Mvc_Base_Interface $base_class_name
		 */
		$base = $base_class_name::get( $base_id );

		/**
		 * @var Mvc_Page $homepage
		 */
		$homepage = $base->getHomepage( $locale );

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
	 * @return Mvc_Base_Interface
	 */
	public function getBase(): Mvc_Base_Interface
	{
		$base_class_name = Factory_Mvc::getBaseClassName();

		/**
		 * @var Mvc_Base_Interface $base_class_name
		 */
		return $base_class_name::get( $this->base_id );
	}

	/**
	 * @param Mvc_Base_Interface $base
	 */
	public function setBase( Mvc_Base_Interface $base ): void
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
		$current_page = Mvc::getCurrentPage();

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
		$current_page = Mvc::getCurrentPage();

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
		if($actualized) {
			return dirname($this->_data_file_path, 2).'/'.rawurldecode( $this->getRelativePathFragment() ) . '/';
		}

		return dirname($this->_data_file_path).'/';
	}

}