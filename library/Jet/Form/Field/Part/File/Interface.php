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
	 * @return bool
	 */
	public function getAllowMultipleUpload(): bool;
	
	/**
	 * @param bool $allow_multiple_upload
	 */
	public function setAllowMultipleUpload( bool $allow_multiple_upload ): void;
	
	
	/**
	 * @return Form_Field_File_UploadedFile[]
	 */
	public function getAllFiles(): array;
	
	
	/**
	 * @return Form_Field_File_UploadedFile[]
	 */
	public function getValidFiles(): array;
	
	/**
	 * @return Form_Field_File_UploadedFile[]
	 */
	public function getProblematicFiles() : array;
}