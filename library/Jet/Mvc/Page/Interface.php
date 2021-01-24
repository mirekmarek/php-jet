<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
interface Mvc_Page_Interface
{
	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @return array
	 */
	public static function getRelativePathMap( Mvc_Site_Interface $site, Locale $locale ): array;


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param array $data
	 *
	 * @return static
	 */
	public static function createByData( Mvc_Site_Interface $site, Locale $locale, array $data ): static;

	/**
	 *
	 * @param string|null $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null $site_id (optional, null = current)
	 *
	 * @return static|null
	 */
	public static function get( string|null $page_id, string|Locale|null $locale = null, string|null $site_id = null ): static|null;

	/**
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return static[]
	 */
	public static function getList( string $site_id, Locale $locale ): array;

	/**
	 * @return string
	 */
	public function getSiteId(): string;

	/**
	 * @param string $site_id
	 */
	public function setSiteId( string $site_id ): void;

	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( Mvc_Site_Interface $site ): void;

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite(): Mvc_Site_Interface;

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ): void;

	/**
	 *
	 * @return Locale
	 */
	public function getLocale(): Locale;

	/**
	 * @param string $id
	 */
	public function setId( string $id );

	/**
	 * @return string
	 */
	public function getId(): string;

	/**
	 * @return string
	 */
	public function getKey(): string;

	/**
	 * @return bool
	 */
	public function isCurrent(): bool;

	/**
	 * @return bool
	 */
	public function isInCurrentPath(): bool;

	/**
	 *
	 * @return static|null
	 */
	public function getParent(): static|null;

	/**
	 * @return array
	 */
	public function getPath(): array;

	/**
	 * @return array
	 */
	public function getChildrenIds(): array;

	/**
	 * @return array
	 */
	public function getChildrenKeys(): array;

	/**
	 * @return static[]
	 */
	public function getChildren(): array;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void;

	/**
	 * @return bool
	 */
	public function getIsDeactivatedByDefault(): bool;

	/**
	 * @return bool
	 */
	public function getIsActive(): bool;

	/**
	 * @return bool
	 */
	public function isSSLRequiredByDefault(): bool;


	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool;

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ): void;


	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void;


	/**
	 * @param bool $is_secret
	 */
	public function setIsSecret( bool $is_secret ): void;

	/**
	 * @return bool
	 */
	public function isSecretByDefault(): bool;

	/**
	 * @return bool
	 */
	public function getIsSecret(): bool;

	/**
	 * @return int
	 */
	public function getOrder(): int;

	/**
	 * @param int $order
	 *
	 */
	public function setOrder( int $order ): void;

	/**
	 * @param string $relative_path_fragment
	 */
	public function setRelativePathFragment( string $relative_path_fragment ): void;

	/**
	 * @return string
	 */
	public function getRelativePathFragment(): string;


	/**
	 * @return string
	 */
	public function getRelativePath(): string;


	/**
	 * @param string $relative_path
	 */
	public function setRelativePath( string $relative_path ): void;

	/**
	 * @return string
	 */
	public function getTitle(): string;

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void;

	/**
	 * @return string
	 */
	public function getIcon(): string;

	/**
	 * @param string $icon
	 */
	public function setIcon( string $icon ): void;

	/**
	 * @return string
	 */
	public function getMenuTitle(): string;

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle( string $menu_title ): void;

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle(): string;

	/**
	 * @param string $breadcrumb_title
	 */
	public function setBreadcrumbTitle( string $breadcrumb_title ): void;

	/**
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURL( array $path_fragments = [], array $GET_params = [] ): string;

	/**
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURLPath( array $path_fragments = [], array $GET_params = [] ): string;

	/**
	 *
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSchemaURL( array $path_fragments = [], array $GET_params = [] ): string;

	/**
	 *
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSslURL( array $path_fragments = [], array $GET_params = [] ): string;

	/**
	 *
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getSslURL( array $path_fragments = [], array $GET_params = [] ): string;

	/**
	 * @return string
	 */
	public function getLayoutsPath(): string;

	/**
	 * @return string
	 */
	public function getLayoutScriptName(): string;

	/**
	 * @param string $layout_script_name
	 */
	public function setLayoutScriptName( string $layout_script_name ): void;

	/**
	 *
	 * @throws Exception
	 *
	 */
	public function initializeLayout(): void;

	/**
	 * @return array
	 */
	public function getHttpHeaders(): array;

	/**
	 * @param array $http_headers
	 */
	public function setHttpHeaders( array $http_headers ): void;

	/**
	 *
	 * @return Mvc_Page_MetaTag_Interface[]
	 */
	public function getMetaTags(): array;

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag ): void;

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( array $meta_tags ): void;

	/**
	 * @param string|callable $output
	 */
	public function setOutput( string|callable $output ): void;

	/**
	 * @return string|callable|null
	 */
	public function getOutput(): string|callable|null;

	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent(): array;

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content ): void;

	/**
	 * @param int $index
	 */
	public function removeContent( int $index ): void;

	/**
	 * @param Mvc_Page_Content_Interface[] $contents
	 */
	public function setContent( array $contents ): void;

	/**
	 *
	 * @return bool
	 */
	public function resolve(): bool;

	/**
	 *
	 */
	public function handleHttpHeaders(): void;

	/**
	 * @return bool
	 */
	public function authorize(): bool;

	/**
	 * @return bool
	 */
	public function accessAllowed(): bool;


	/**
	 * @return string
	 */
	public function render(): string;

	/**
	 *
	 */
	public function saveDataFile(): void;
}