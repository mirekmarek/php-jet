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
interface Validator_Part_File_Interface
{
	
	public function getMaximalFileSize(): int|null;
	public function setMaximalFileSize( int|null $maximal_file_size ): void;
	
	/**
	 * @return array<string>
	 */
	public function getAllowedMimeTypes(): array;
	
	/**
	 * @param array<string> $allowed_mime_types
	 */
	public function setAllowedMimeTypes( array $allowed_mime_types ): void;
}