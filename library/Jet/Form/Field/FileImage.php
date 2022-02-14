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
class Form_Field_FileImage extends Form_Field implements Form_Field_Part_File_Interface
{
	use Form_Field_Part_File_Trait;
	
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_FILE_IMAGE;
	

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

	protected function validate_checkDimensions( string $path ) : bool
	{
		if(
			$this->maximal_width &&
			$this->maximal_height
		) {
			try {
				$image = new Data_Image( $path );
				$image->createThumbnail( $path, $this->maximal_width, $this->maximal_height );
			} /** @noinspection PhpUnusedLocalVariableInspection */ catch( Data_Image_Exception $e ) {
				return false;
			}
		}

		return true;
	}


	/**
	 * validate value
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
			if( is_array( $this->_value ) ) {
				foreach( $this->_value as $i => $path ) {
					if(
						!$this->validate_checkFileSize( $path, $this->file_name[$i] ) ||
						!$this->validate_checkMimeType( $path, $this->file_name[$i] ) ||
						!$this->validate_checkDimensions( $path )
					) {
						$this->unsetFile( $i );
					}
				}

			} else {
				if( !$this->validate_checkFileSize( $this->_value, $this->file_name ) ) {
					$this->setError( Form_Field::ERROR_CODE_FILE_IS_TOO_LARGE );
					return false;
				}

				if( !$this->validate_checkMimeType( $this->_value, $this->file_name ) ) {
					$this->setError( Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE );
					return false;
				}

				if( !$this->validate_checkDimensions( $this->_value ) ) {
					$this->setError( Form_Field::ERROR_CODE_DISALLOWED_FILE_TYPE );
					return false;
				}
			}
		}


		if(!$this->validate_validator()) {
			return false;
		}

		$this->setIsValid();
		return true;
	}
}