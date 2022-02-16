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
interface MVC_Base_Interface
{

	/**
	 *
	 * @return static[]
	 */
	public static function _getBases(): array;

	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function _get( string $id ): static|null;


	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function _createByData( array $data ): static;


	/**
	 * @return array
	 */
	public static function _getUrlMap(): array;


	/**
	 * @param string $id
	 *
	 */
	public function setId( string $id ): void;

	/**
	 * @return string
	 */
	public function getId(): string;

	/**
	 *
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void;


	/**
	 * @param bool $is_secret
	 */
	public function setIsSecret( bool $is_secret ): void;

	/**
	 * @return bool
	 */
	public function getIsSecret(): bool;


	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool;

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ): void;

	/**
	 * @return bool
	 */
	public function getIsDefault(): bool;

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault( bool $is_default ): void;

	/**
	 * @return bool
	 */
	public function getIsActive(): bool;

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void;

	/**
	 *
	 * @param callable $initializer
	 */
	public function setInitializer( callable $initializer ): void;

	/**
	 *
	 * @return callable|null
	 */
	public function getInitializer(): callable|null;

	/**
	 * @param string $path
	 */
	public function setBasePath( string $path ): void;

	/**
	 *
	 * @return string
	 */
	public function getBasePath(): string;

	/**
	 * @param ?Locale $locale (optional)
	 *
	 * @return string
	 */
	public function getPagesDataPath( ?Locale $locale = null ): string;

	/**
	 * @param string $path
	 */
	public function setLayoutsPath( string $path ): void;

	/**
	 * @return string
	 */
	public function getLayoutsPath(): string;

	/**
	 * @param string $path
	 */
	public function setViewsPath( string $path ): void;

	/**
	 * @return string
	 */
	public function getViewsPath(): string;


	/**
	 * Returns default locale
	 *
	 * @return Locale|null
	 */
	public function getDefaultLocale(): Locale|null;

	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	public function getHasLocale( Locale $locale ): bool;

	/**
	 * @param Locale $locale
	 *
	 * @return MVC_Base_LocalizedData_Interface
	 */
	public function getLocalizedData( Locale $locale ): MVC_Base_LocalizedData_Interface;


	/**
	 *
	 *
	 * @param bool $get_as_string (optional, default: false)
	 *
	 * @return Locale[]
	 */
	public function getLocales( bool $get_as_string = false ): array;

	/**
	 *
	 *
	 * @param bool $get_as_string (optional, default: false)
	 *
	 * @return Locale[]
	 */
	public function getActiveLocales( bool $get_as_string = false ): array;


	/**
	 * @param array $order
	 */
	public function sortLocales( array $order ): void;


	/**
	 *
	 * @param Locale $locale
	 *
	 * @return MVC_Base_LocalizedData_Interface
	 */
	public function addLocale( Locale $locale ): MVC_Base_LocalizedData_Interface;

	/**
	 * Remove locale. If the locale was default, then set as the default first possible locale
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale ): void;

	/**
	 * @param ?Locale $locale
	 *
	 * @return MVC_Page_Interface
	 */
	public function getHomepage( ?Locale $locale = null ): MVC_Page_Interface;

	/**
	 *
	 */
	public function saveDataFile(): void;

	/**
	 * @return array
	 */
	public function toArray(): array;

}