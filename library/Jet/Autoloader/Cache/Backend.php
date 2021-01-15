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