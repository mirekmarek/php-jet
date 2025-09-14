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
abstract class Cache_Abstract
{
	/**
	 * @return bool
	 */
	abstract public function isActive(): bool;
	
	/**
	 * @param string $key
	 * @return ?Cache_Record_Data
	 */
	abstract protected function readData( string $key ): ?Cache_Record_Data;
	
	/**
	 * @param string $key
	 * @param mixed $data
	 */
	abstract protected function writeData( string $key, mixed $data ): void;
	
	/**
	 * @param string $key
	 * @return ?Cache_Record_HTMLSnippet
	 */
	abstract protected function readHtml( string $key ): ?Cache_Record_HTMLSnippet;
	
	/**
	 * @param string $key
	 * @param string $html
	 */
	abstract protected function writeHtml( string $key, string $html ): void;
	
	/**
	 * @param string $prefix
	 */
	abstract public function resetHtmlFiles( string $prefix ): void;
	
	/**
	 * @param string $key
	 */
	abstract public function resetHtmlFile( string $key ): void;

}
