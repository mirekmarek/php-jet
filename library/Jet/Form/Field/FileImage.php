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
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'field/input/file-image';


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


	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if( !parent::validate() ) {
			return false;
		}

		$check_dimensions = function( $path ) {
			if(
				$this->maximal_width &&
				$this->maximal_height
			) {
				try {
					$image = new Data_Image( $path );
					$image->createThumbnail( $path, $this->maximal_width, $this->maximal_height );
				} catch( Data_Image_Exception $e ) {
					return false;
				}
			}

			return true;
		};

		if( $this->_value ) {
			if( is_array( $this->_value ) ) {
				foreach( $this->_value as $i => $path ) {
					if( !$check_dimensions( $path ) ) {
						$this->unsetFile( $i );
					}

				}
			} else {
				if( !$check_dimensions( $this->_value ) ) {
					$this->setError( self::ERROR_CODE_DISALLOWED_FILE_TYPE );
					return false;
				}
			}
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