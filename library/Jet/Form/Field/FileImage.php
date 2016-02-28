<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_FileImage extends Form_Field_File {
	const ERROR_CODE_FILE_IS_TOO_LARGE = 'file_is_too_large';
	const ERROR_CODE_DISALLOWED_FILE_TYPE = 'disallowed_file_type';
//TODO: html5

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_FILE_IMAGE;

	/**
	 * @var bool
	 */
	protected $_possible_to_decorate = false;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '',
		self::ERROR_CODE_FILE_IS_TOO_LARGE => '',
		self::ERROR_CODE_DISALLOWED_FILE_TYPE => ''
	];

	/**
	 * @var array
	 */
	protected $allowed_mime_types = [
		'image/pjpeg',
		'image/jpeg',
		'image/jpg',
		'image/gif',
		'image/png'
	];

	/**
	 * @var null|int
	 */
	protected $maximal_width = null;

	/**
	 * @var null|int
	 */
	protected $maximal_height = null;

	/**
	 * @param int $maximal_width
	 * @param int $maximal_height
	 */
	public function setMaximalSize( $maximal_width, $maximal_height ) {
		$this->maximal_width = (int)$maximal_width;
		$this->maximal_height = (int)$maximal_height;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalHeight() {
		return $this->maximal_height;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalWidth() {
		return $this->maximal_width;
	}


	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {

		if(!parent::validateValue()) {
			return false;
		}

        if($this->_value) {
            if(
                $this->maximal_width &&
                $this->maximal_height
            ) {
                try {
                    $image = new Data_Image( $this->_value );
                    $image->createThumbnail( $this->_value, $this->maximal_width, $this->maximal_height );
                } catch( Data_Image_Exception $e ) {
                    $this->setValueError(self::ERROR_CODE_DISALLOWED_FILE_TYPE);

                    return false;
                }
            }
        }


		$this->_setValueIsValid();

		return true;
	}



	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		if($this->maximal_file_size) {
			$codes[] = self::ERROR_CODE_FILE_IS_TOO_LARGE;
		}

		if($this->allowed_mime_types) {
			$codes[] = self::ERROR_CODE_DISALLOWED_FILE_TYPE;
		}

		return $codes;
	}

}