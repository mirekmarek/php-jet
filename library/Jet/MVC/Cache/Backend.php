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
interface MVC_Cache_Backend
{

	/**
	 * @return bool
	 */
	public function isActive(): bool;

	/**
	 *
	 */
	public function reset(): void;

	/**
	 * @return array|null
	 */
	public function loadBaseMaps(): array|null;

	/**
	 * @param array $map
	 */
	public function saveBaseMaps( array $map ): void;


	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array|null
	 */
	public function loadPageMaps( MVC_Base_Interface $base, Locale $locale ): array|null;

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array $map
	 */
	public function savePageMaps( MVC_Base_Interface $base, Locale $locale, array $map ): void;

	/**
	 * @param MVC_Page_Content_Interface $content
	 *
	 * @return string|null
	 */
	public function loadContentOutput( MVC_Page_Content_Interface $content ): string|null;

	/**
	 * @param MVC_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public function saveContentOutput( MVC_Page_Content_Interface $content, string $output ): void;


}