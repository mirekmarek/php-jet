<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface Form_Field_Part_File_Interface
{
	
	/**
	 * @return int|null
	 */
	public function getMaximalFileSize(): int|null;
	
	/**
	 * @param int|null $maximal_file_size
	 */
	public function setMaximalFileSize( int|null $maximal_file_size ): void;
	
	/**
	 * @return array
	 */
	public function getAllowedMimeTypes(): array;
	
	/**
	 * @param array $allowed_mime_types
	 */
	public function setAllowedMimeTypes( array $allowed_mime_types ): void;
	
	/**
	 * @return string|array
	 */
	public function getFileName(): string|array;
	
	/**
	 * @return bool
	 */
	public function getAllowMultipleUpload(): bool;
	
	/**
	 * @param bool $allow_multiple_upload
	 */
	public function setAllowMultipleUpload( bool $allow_multiple_upload ): void;
	
	
	/**
	 * @return array
	 */
	public function getMultipleUploadErrors(): array;
	
	/**
	 * @param string $file_name
	 * @param string $error_code
	 */
	public function addMultipleUploadError( string $file_name, string $error_code ): void;
	
	/**
	 * @return string|array
	 */
	public function getTmpFilePath(): string|array;
}