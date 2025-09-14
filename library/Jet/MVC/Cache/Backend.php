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
	
	public function resetOutputCache() : void;

	/**
	 * @return array<string,array<string,string|string[]>>|null
	 */
	public function loadBaseMaps(): array|null;

	/**
	 * @param array<string,array<string,string|string[]>> $map
	 */
	public function saveBaseMaps( array $map ): void;


	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @return array<string,array<string,string|string[]>>|null
	 */
	public function loadPageMaps( MVC_Base_Interface $base, Locale $locale ): array|null;

	/**
	 * @param MVC_Base_Interface $base
	 * @param Locale $locale
	 *
	 * @param array<string,array<string,string|string[]>> $map
	 */
	public function savePageMaps( MVC_Base_Interface $base, Locale $locale, array $map ): void;

	/**
	 * @param MVC_Page_Content_Interface $content
	 *
	 * @return ?Cache_Record_HTMLSnippet
	 */
	public function loadContentOutput( MVC_Page_Content_Interface $content ): ?Cache_Record_HTMLSnippet;

	/**
	 * @param MVC_Page_Content_Interface $content
	 * @param string $output
	 *
	 */
	public function saveContentOutput( MVC_Page_Content_Interface $content, string $output ): void;
	
	
	/**
	 * @param string $key
	 *
	 * @return ?Cache_Record_HTMLSnippet
	 */
	public function loadCustomOutput( string $key ): ?Cache_Record_HTMLSnippet;
	
	/**
	 * @param string $key
	 * @param string $output
	 *
	 */
	public function saveCustomOutput( string $key, string $output ): void;
	

}