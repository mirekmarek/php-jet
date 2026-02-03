<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


trait Validator_Part_File_Trait
{
	public const ERROR_CODE_FILE_IS_TOO_LARGE = 'file_is_too_large';
	public const ERROR_CODE_DISALLOWED_FILE_TYPE = 'disallowed_file_type';
	
	/**
	 * @var array<string>
	 */
	protected array $allowed_mime_types = [];
	protected null|int $maximal_file_size = null;
	
	public function getMaximalFileSize(): int|null
	{
		return $this->maximal_file_size;
	}
	
	public function setMaximalFileSize( int|null $maximal_file_size ): void
	{
		$this->maximal_file_size = $maximal_file_size;
	}
	
	/**
	 * @return array<string>
	 */
	public function getAllowedMimeTypes(): array
	{
		return $this->allowed_mime_types;
	}
	
	/**
	 * @param array<string> $allowed_mime_types
	 */
	public function setAllowedMimeTypes( array $allowed_mime_types ): void
	{
		$this->allowed_mime_types = $allowed_mime_types;
	}
	
	
	
	
	protected function validate_checkFileSize( string $file_path ) : bool
	{
		if( $this->maximal_file_size ) {
			$file_size = IO_File::getSize( $file_path );
			
			if( $file_size > $this->maximal_file_size ) {
				
				$this->setError(
					code: static::ERROR_CODE_FILE_IS_TOO_LARGE,
					data: [
						'file_path' => $file_path,
						'file_size' => $file_size,
						'max_file_size' => $this->maximal_file_size
					]
				);
				
				return false;
			}
		}
		
		return true;
	}
	
	protected function validate_checkMimeType ( string $file_path ) : bool
	{
		$allowed_mime_types = $this->getAllowedMimeTypes();
		
		if( $allowed_mime_types ) {
			$file_mime_type = IO_File::getMimeType( $file_path );
			
			if( !in_array(
				$file_mime_type,
				$allowed_mime_types
			) ) {
				$this->setError(
					static::ERROR_CODE_DISALLOWED_FILE_TYPE,
					[
						'file_path' => $file_path,
						'file_mime_type' => $file_mime_type,
						'error_message' => '',
					]
				);
				
				return false;
			}
		}
		
		return true;
	}
	
	
	public function validate_value( mixed $value ): bool
	{
		if(
			!$this->validate_checkFileSize($value) ||
			!$this->validate_checkMimeType( $value )
		) {
			return false;
		}

		return true;
		
	}
}