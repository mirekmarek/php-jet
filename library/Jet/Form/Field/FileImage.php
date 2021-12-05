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
class Form_Field_FileImage extends Form_Field_File
{
	const ERROR_CODE_FILE_IS_TOO_LARGE = 'file_is_too_large';
	const ERROR_CODE_DISALLOWED_FILE_TYPE = 'disallowed_file_type';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_FILE_IMAGE;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY                => '',
		self::ERROR_CODE_FILE_IS_TOO_LARGE    => '',
		self::ERROR_CODE_DISALLOWED_FILE_TYPE => '',
	];

	/**
	 * @var array
	 */
	protected array $allowed_mime_types = [
		'image/pjpeg',
		'image/jpeg',
		'image/jpg',
		'image/gif',
		'image/png',
	];

	/**
	 * @var null|int
	 */
	protected int|null $maximal_width = null;

	/**
	 * @var null|int
	 */
	protected int|null $maximal_height = null;

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
			$this->setError( self::ERROR_CODE_EMPTY );
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
					$this->setError( self::ERROR_CODE_FILE_IS_TOO_LARGE );
					return false;
				}

				if( !$this->validate_checkMimeType( $this->_value, $this->file_name ) ) {
					$this->setError( self::ERROR_CODE_DISALLOWED_FILE_TYPE );
					return false;
				}

				if( !$this->validate_checkDimensions( $this->_value ) ) {
					$this->setError( self::ERROR_CODE_DISALLOWED_FILE_TYPE );
					return false;
				}
			}
		}


		$validator = $this->getValidator();
		if(
			$validator &&
			!$validator( $this )
		) {
			return false;
		}

		$this->setIsValid();
		return true;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		if( $this->maximal_file_size ) {
			$codes[] = self::ERROR_CODE_FILE_IS_TOO_LARGE;
		}

		if( $this->allowed_mime_types ) {
			$codes[] = self::ERROR_CODE_DISALLOWED_FILE_TYPE;
		}

		return $codes;
	}

}