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
class Navigation_Menu_Item extends BaseObject
{


	/**
	 * @var ?Navigation_Menu
	 */
	protected ?Navigation_Menu $menu = null;

	/**
	 * @var string
	 */
	protected string $menu_id = '';

	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var string
	 */
	protected string $label = '';

	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 * @var int
	 */
	protected int $index = 0;

	/**
	 * @var bool
	 */
	protected bool $separator_before = false;

	/**
	 * @var bool
	 */
	protected bool $separator_after = false;

	/**
	 * @var string|null
	 */
	protected string|null $URL = null;

	/**
	 * @var string
	 */
	protected string $page_id = '';

	/**
	 * @var string
	 */
	protected string $base_id = '';

	/**
	 * @var ?Locale
	 */
	protected ?Locale $locale = null;

	/**
	 * @var array
	 */
	protected array $url_parts = [];

	/**
	 * @var array
	 */
	protected array $get_params = [];

	/**
	 *
	 *
	 * @param string $id
	 * @param string $label
	 */
	public function __construct( string $id, string $label )
	{
		$this->id = $id;
		$this->label = $label;
	}

	/**
	 * @param array $data
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function setData( array $data ): void
	{
		foreach( $data as $key => $val ) {
			if(
				$key == 'id' ||
				$key == 'label'
			) {
				continue;
			}

			if( !property_exists( $this, $key ) ) {
				throw new Navigation_Menu_Exception( 'Unknown menu property: ' . $key );
			}

			$setter = $this->objectSetterMethodName( $key );

			$this->$setter( $val );
		}

	}

	/**
	 * @return Navigation_Menu
	 */
	public function getMenu(): Navigation_Menu
	{
		return $this->menu;
	}

	/**
	 * @param Navigation_Menu $menu
	 */
	public function setMenu( Navigation_Menu $menu ): void
	{
		$this->menu = $menu;
		$this->menu_id = $menu->getId();
	}

	/**
	 * @return string
	 */
	public function getMenuId(): string
	{
		return $this->menu_id;
	}

	/**
	 * @param string $menu_id
	 */
	public function setMenuId( string $menu_id ): void
	{
		$this->menu_id = $menu_id;
	}


	/**
	 * @param bool $absolute (optional)
	 *
	 * @return string
	 */
	public function getId( bool $absolute = true ): string
	{
		if( $absolute ) {
			return $this->getMenu()->getId() . '/' . $this->id;
		}

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
	public function getLabel(): string
	{
		if( $this->label ) {
			return $this->label;
		}

		$page = $this->getTargetPage();

		if( !$page ) {
			return '';
		}

		return $page->getMenuTitle();

	}

	/**
	 * @param string $label
	 */
	public function setLabel( string $label ): void
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		if( $this->icon ) {
			return $this->icon;
		}

		$page = $this->getTargetPage();

		if( !$page ) {
			return '';
		}

		return $page->getIcon();
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( string $icon ): void
	{
		$this->icon = $icon;
	}

	/**
	 * @return int
	 */
	public function getIndex(): int
	{
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function setIndex( int $index ): void
	{
		$this->index = $index;
	}

	/**
	 * @return bool
	 */
	public function getSeparatorBefore(): bool
	{
		return $this->separator_before;
	}

	/**
	 * @param bool $separator_before
	 */
	public function setSeparatorBefore( bool $separator_before ): void
	{
		$this->separator_before = $separator_before;
	}

	/**
	 * @return bool
	 */
	public function getSeparatorAfter(): bool
	{
		return $this->separator_after;
	}

	/**
	 * @param bool $separator_after
	 */
	public function setSeparatorAfter( bool $separator_after ): void
	{
		$this->separator_after = $separator_after;
	}

	/**
	 * @return string
	 */
	public function getPageId(): string
	{
		return $this->page_id;
	}

	/**
	 * @param string $page_id
	 */
	public function setPageId( string $page_id ): void
	{
		$this->page_id = $page_id;
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
	 * @return ?Locale
	 */
	public function getLocale(): ?Locale
	{
		return $this->locale;
	}

	/**
	 * @param Locale|string $locale
	 */
	public function setLocale( Locale|string $locale ): void
	{
		if(!$locale instanceof Locale) {
			$locale = new Locale($locale);
		}

		$this->locale = $locale;
	}

	/**
	 * @return array
	 */
	public function getUrlParts(): array
	{
		return $this->url_parts;
	}

	/**
	 * @param array $url_parts
	 */
	public function setUrlParts( array $url_parts ): void
	{
		$this->url_parts = $url_parts;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		if( $this->URL ) {
			return $this->URL;
		}

		$page = $this->getTargetPage();

		if( !$page ) {
			return '';
		}

		return $page->getURL( $this->url_parts, $this->getGetParams() );
	}

	/**
	 * @param string $URL
	 */
	public function setURL( string $URL ): void
	{
		$this->URL = $URL;
	}

	/**
	 * @return array
	 */
	public function getGetParams(): array
	{
		return $this->get_params;
	}

	/**
	 * @param array $get_params
	 */
	public function setGetParams( array $get_params ): void
	{
		$this->get_params = $get_params;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed(): bool
	{
		if( $this->URL ) {
			return true;
		}

		$page = $this->getTargetPage();

		if( !$page ) {
			return false;
		}

		return $page->accessAllowed();
	}

	/**
	 * @return MVC_Page_Interface|null
	 */
	public function getTargetPage(): MVC_Page_Interface|null
	{
		return MVC::getPage( $this->page_id, $this->locale, $this->base_id );
	}

}