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
interface Mvc_Cache_Backend
{

	/**
	 * @return bool
	 */
	public function isActive() : bool;

	/**
	 *
	 */
	public function reset() : void;

	/**
	 * @return array|null
	 */
	public function loadSiteMaps(): array|null;

	/**
	 * @param array $map
	 */
	public function saveSiteMaps( array $map ): void;


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( Mvc_Site_Interface $site, Locale $locale ): array|null;

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps(  Mvc_Site_Interface $site, Locale $locale, array $map ): void;

}