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
interface MVC_Router_Interface
{

	/**
	 * @param string|null $request_URL
	 */
	public function resolve( string|null $request_URL = null ): void;

	/**
	 * @return bool
	 */
	public function getSetSystemLocale(): bool;

	/**
	 * @param bool $set_system_locale
	 */
	public function setSetSystemLocale( bool $set_system_locale ): void;

	/**
	 * @return bool
	 */
	public function getSetTranslatorLocale(): bool;

	/**
	 * @param bool $set_translator_locale
	 */
	public function setSetTranslatorLocale( bool $set_translator_locale ): void;


	/**
	 *
	 * @return ?MVC_Base_Interface
	 */
	public function getBase(): ?MVC_Base_Interface;

	/**
	 * @return ?Locale
	 */
	public function getLocale(): ?Locale;

	/**
	 *
	 * @return ?MVC_Page_Interface
	 */
	public function getPage(): ?MVC_Page_Interface;

	/**
	 *
	 */
	public function setIs404(): void;

	/**
	 *
	 * @return bool
	 */
	public function getIs404(): bool;

	/**
	 *
	 * @param string $target_URL
	 * @param int $http_code
	 */
	public function setIsRedirect( string $target_URL, int $http_code = Http_Headers::CODE_302_MOVED_TEMPORARY );

	/**
	 *
	 * @return bool
	 */
	public function getIsRedirect(): bool;

	/**
	 *
	 * @return string
	 */
	public function getRedirectTargetURL(): string;

	/**
	 *
	 * @return int
	 */
	public function getRedirectType(): int;

	/**
	 * @return bool
	 */
	public function getLoginRequired(): bool;

	/**
	 *
	 */
	public function setLoginRequired(): void;

	/**
	 * @return bool
	 */
	public function getAccessNotAllowed(): bool;

	/**
	 *
	 */
	public function setAccessNotAllowed(): void;

	/**
	 * @return string
	 */
	public function getUrlPath(): string;


	/**
	 * @return string
	 */
	public function getUsedUrlPath(): string;

	/**
	 * @param string $used_path
	 */
	public function setUsedUrlPath( string $used_path ): void;

	/**
	 * @return bool
	 */
	public function getHasUnusedUrlPath(): bool;

	/**
	 * @return string
	 */
	public function getValidUrl(): string;

}