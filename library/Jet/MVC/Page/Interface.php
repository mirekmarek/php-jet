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
interface MVC_Page_Interface
{
	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array
	 */
	public static function _getRelativePathMap( MVC_Base_Interface $base, Locale $locale ): array;


	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 * @param array $data
	 *
	 * @return static
	 */
	public static function _createByData( MVC_Base_Interface $base, Locale $locale, array $data ): static;

	/**
	 *
	 * @param string $page_id 
	 * @param Locale $locale
	 * @param string $base_id
	 *
	 * @return static|null
	 */
	public static function _get( string $page_id, Locale $locale, string $base_id ): static|null;

	/**
	 * @return string
	 */
	public function getBaseId(): string;

	/**
	 * @param string $base_id
	 */
	public function setBaseId( string $base_id ): void;

	/**
	 * @param MVC_Base_Interface $base
	 */
	public function setBase( MVC_Base_Interface $base ): void;

	/**
	 * @return MVC_Base_Interface
	 */
	public function getBase(): MVC_Base_Interface;

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
	public function getSourceModuleName(): string;
	
	/**
	 * @param string $source_module_name
	 */
	public function setSourceModuleName( string $source_module_name ): void;
	
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
	 * @param string $page_id
	 */
	public function setParentId( string $page_id ) : void;

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
	 * @return MVC_Page_MetaTag_Interface[]
	 */
	public function getMetaTags(): array;

	/**
	 * @param MVC_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( MVC_Page_MetaTag_Interface $meta_tag ): void;

	/**
	 * @param string $attribute
	 * @param string $attribute_value
	 * @param string $content
	 */
	public function setMetaTag( string $attribute, string $attribute_value, string $content ) : void;

	/**
	 * @param MVC_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( array $meta_tags ): void;

	/**
	 * @return array
	 */
	public function getParameters(): array;

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ): void;

	/**
	 * @param string $key
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( string $key, mixed $default_value = null ): mixed;

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setParameter( string $key, mixed $value ): void;

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( string $key ): bool;

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
	 * @return MVC_Page_Content_Interface[]
	 */
	public function getContent(): array;

	/**
	 * @param MVC_Page_Content_Interface $content
	 */
	public function addContent( MVC_Page_Content_Interface $content ): void;

	/**
	 * @param int $index
	 */
	public function removeContent( int $index ): void;

	/**
	 * @param MVC_Page_Content_Interface[] $contents
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
	 * @param string $data_file_path
	 */
	public function setDataFilePath( string $data_file_path ): void;

	/**
	 * @param bool $actualized
	 * @return string
	 */
	public function getDataFilePath( bool $actualized = false ): string;

	/**
	 * @param bool $actualized
	 *
	 * @return string
	 */
	public function getDataDirPath( bool $actualized = false ): string;

	/**
	 *
	 */
	public function saveDataFile(): void;

	/**
	 * @return string
	 */
	public function getCacheContext(): string;

	/**
	 * @param string $cache_context
	 */
	public function setCacheContext( string $cache_context ): void;

}