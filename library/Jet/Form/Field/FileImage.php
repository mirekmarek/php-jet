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
class Form_Field_FileImage extends Form_Field implements Form_Field_Part_File_Interface
{
	use Form_Field_Part_File_Trait;
	
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_FILE_IMAGE;
	
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY                => '',
		Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE    => '',
		Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE => '',
	];

	/**
	 * @var ?int
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Maximal image width',
		getter: 'getMaximalWidth',
		setter: 'setMaximalWidth',
	)]
	protected int|null $maximal_width = null;

	/**
	 * @var ?int
	 */
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_INT,
		label: 'Maximal image height',
		getter: 'getMaximalHeight',
		setter: 'setMaximalHeight',
	)]
	protected int|null $maximal_height = null;
	
	/**
	 * @return array
	 */
	public function getAllowedMimeTypes(): array
	{
		if(!$this->allowed_mime_types) {
			return [
				'image/pjpeg',
				'image/jpeg',
				'image/jpg',
				'image/gif',
				'image/png',
			];
		}
		
		return $this->allowed_mime_types;
	}
	
	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->_name = $name;
	}
	
	/**
	 * @param int|null $maximal_width
	 */
	public function setMaximalWidth( ?int $maximal_width ): void
	{
		$this->maximal_width = $maximal_width;
	}
	
	/**
	 * @param int|null $maximal_height
	 */
	public function setMaximalHeight( ?int $maximal_height ): void
	{
		$this->maximal_height = $maximal_height;
	}
	
	

	/**
	 * @param int $maximal_width
	 * @param int $maximal_height
	 */
	public function setMaximalSize( int $maximal_width, int $maximal_height )
	{
		$this->maximal_width = $maximal_width;
		$this->maximal_height = $maximal_height;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalHeight(): int|null
	{
		return $this->maximal_height;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalWidth(): int|null
	{
		return $this->maximal_width;
	}

	protected function validate_checkDimensions( Form_Field_File_UploadedFile $file ) : bool
	{
		if(
			$this->maximal_width &&
			$this->maximal_height
		) {
			try {
				$image = new Data_Image( $file->getTmpFilePath() );
				$image->createThumbnail( $file->getTmpFilePath(), $this->maximal_width, $this->maximal_height );
			} catch( Data_Image_Exception $e ) {
				$file->setError( static::ERROR_CODE_FILE_IS_TOO_LARGE, $this->getErrorMessage(
					static::ERROR_CODE_FILE_IS_TOO_LARGE,
					[
						'file_name' => $file->getFileName(),
						'file_size' => Locale::size($file->getSize()),
						'max_file_size' => Locale::size($this->maximal_file_size)
					]
				) );
				
				return false;
			}
		}

		return true;
	}
	
	
	/**
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(
			!$this->_has_value &&
			$this->is_required
		) {
			$this->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}
		
		
		if($this->_has_value) {
			
			foreach( $this->_value as $name=>$file ) {
				if(
					!$this->validate_checkFileSize( $file ) ||
					!$this->validate_checkMimeType( $file ) ||
					!$this->validate_checkDimensions( $file )
				) {
					unset($this->_value[$name]);
				}
			}
		}
		
		if(!$this->validate_validator()) {
			return  false;
		}

		
		$this->setIsValid();
		return true;
		
	}
}