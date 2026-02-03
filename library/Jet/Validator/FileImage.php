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
class Validator_FileImage extends Validator_File
{
	
	public const ERROR_CODE_IMAGE_IS_TOO_LARGE = 'image_is_too_large';
	
	protected static string $type = self::TYPE_FILE_IMAGE;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_INT,
		label: 'Maximal image width',
		getter: 'getMaximalWidth',
		setter: 'setMaximalWidth',
	)]
	protected int|null $maximal_width = null;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_INT,
		label: 'Maximal image height',
		getter: 'getMaximalHeight',
		setter: 'setMaximalHeight',
	)]
	protected int|null $maximal_height = null;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_BOOL,
		label: 'Automatic resize of image mode enabled',
		getter: 'getAutomaticResizeMode',
		setter: 'setAutomaticResizeMode',
	)]
	protected bool $automatic_resize_mode = false;
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_FILE_IS_TOO_LARGE => 'File is too large. Max file size is: %max_file_size%',
		self::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Disallowed file type %file_mime_type% %error_message%',
		self::ERROR_CODE_IMAGE_IS_TOO_LARGE => 'Image is too large. Max dimensions: %max_width%x%max_height%'
	];
	
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		$codes[] = static::ERROR_CODE_FILE_IS_TOO_LARGE;
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		
		if( $this->allowed_mime_types ) {
			$codes[] = static::ERROR_CODE_DISALLOWED_FILE_TYPE;
		}
		
		if(
			($this->maximal_width || $this->maximal_height) &&
			!$this->automatic_resize_mode
		) {
			$codes[] = static::ERROR_CODE_IMAGE_IS_TOO_LARGE;
		}
		
		return $codes;
		
	}
	
	
	public function getAllowedMimeTypes(): array
	{
		if(!$this->allowed_mime_types) {
			$this->allowed_mime_types = Data_Image::getSupportedMimeTypes();
		}
		
		return $this->allowed_mime_types;
	}
	
	public function setMaximalWidth( ?int $maximal_width ): void
	{
		$this->maximal_width = $maximal_width;
	}
	
	public function setMaximalHeight( ?int $maximal_height ): void
	{
		$this->maximal_height = $maximal_height;
	}
	
	public function setMaximalSize( int $maximal_width, int $maximal_height ) : void
	{
		$this->maximal_width = $maximal_width;
		$this->maximal_height = $maximal_height;
	}

	public function getMaximalHeight(): int|null
	{
		return $this->maximal_height;
	}

	public function getMaximalWidth(): int|null
	{
		return $this->maximal_width;
	}
	
	public function getAutomaticResizeMode(): bool
	{
		return $this->automatic_resize_mode;
	}
	
	public function setAutomaticResizeMode( bool $automatic_resize_mode ): void
	{
		$this->automatic_resize_mode = $automatic_resize_mode;
	}
	
	

	protected function validate_checkDimensions( string $file_path ) : bool
	{
		
		$ok = true;
		try {
			$image = new Data_Image( $file_path );
			
			if($this->automatic_resize_mode) {
				if(
					$this->maximal_width &&
					$this->maximal_height
				) {
					$image = new Data_Image( $file_path );
					$image->createThumbnail( $file_path, $this->maximal_width, $this->maximal_height );
				}
				
			} else {
				if(
					$this->maximal_width &&
					$image->getWidth()>$this->maximal_width
				) {
					$this->setError(
						static::ERROR_CODE_IMAGE_IS_TOO_LARGE,
						[
							'file_path' => $file_path,
							'width' => $image->getWidth(),
							'height' => $image->getHeight(),
							'max_width' => $this->getMaximalWidth(),
							'max_height' => $this->getMaximalHeight()
						]
					);
					
					$ok = false;
				}
				
				if(
					$this->maximal_height &&
					$image->getHeight()>$this->maximal_height
				) {
					$this->setError(
						static::ERROR_CODE_IMAGE_IS_TOO_LARGE,
						[
							'file_path' => $file_path,
							'width' => $image->getWidth(),
							'height' => $image->getHeight(),
							'max_width' => $this->getMaximalWidth(),
							'max_height' => $this->getMaximalHeight()
						]
					);
					
					$ok = false;
				}
			}
			
			
			
		} catch( Data_Image_Exception $e ) {
			
			
			$this->setError(
				static::ERROR_CODE_DISALLOWED_FILE_TYPE,
				[
					'file_path' => $file_path,
					'error_message' => $e->getMessage(),
					'file_mime_type' => ''
				]
			);
			
			return false;
		}
		
		return $ok;
	}
	
	public function validate_value( mixed $value ): bool
	{
		if(
			!$this->validate_checkFileSize($value) ||
			!$this->validate_checkMimeType( $value ) ||
			!$this->validate_checkDimensions( $value )
		) {
			return false;
		}
		
		return true;
		
	}
}