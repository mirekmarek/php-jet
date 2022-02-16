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
interface Autoloader_Cache_Backend
{
	/**
	 * @return bool
	 */
	public function isActive(): bool;

	/**
	 * @return array|null
	 */
	public function load(): array|null;

	/**
	 * @param array $map
	 */
	public function save( array $map ): void;

	/**
	 *
	 */
	public function reset(): void;

}