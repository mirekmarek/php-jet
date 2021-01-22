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
interface Mvc_Router_Interface
{

	/**
	 *
	 *
	 * @param string $request_URL
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function resolve( string $request_URL ): void;

	/**
	 * @return bool
	 */
	public function getSetMvcState(): bool;

	/**
	 * @param bool $set_mvc_state
	 */
	public function setSetMvcState( bool $set_mvc_state ): void;

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getSite(): Mvc_Site_Interface;

	/**
	 * @return Locale
	 */
	public function getLocale(): Locale;

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getPage(): Mvc_Page_Interface;

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
	 * @return string
	 */
	public function getPath(): string;


	/**
	 * @return string
	 */
	public function getUsedPath(): string;

	/**
	 * @param string $used_path
	 */
	public function setUsedPath( string $used_path ): void;

	/**
	 * @return bool
	 */
	public function getHasUnusedPath(): bool;

	/**
	 * @return string
	 */
	public function getValidUrl(): string;

}