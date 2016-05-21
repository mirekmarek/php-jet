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
 * @pacackage Form
 */
namespace Jet;

class Form_Field_File extends Form_Field_Abstract {
	const ERROR_CODE_FILE_IS_TOO_LARGE = 'file_is_too_large';
	const ERROR_CODE_DISALLOWED_FILE_TYPE = 'disallowed_file_type';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_FILE;

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
	protected $allowed_mime_types = [];

	/**
	 * @var null|int
	 */
	protected $maximal_file_size = null;

	/**
	 * @var string
	 */
	protected $tmp_file_path;

	/**
	 * @var string
	 */
	protected $file_name;

    /**
     * @var string
     */
    protected $uploaded_file_path;

    /**
     * set form instance
     *
     * @param Form $form
     */
    public function setForm(Form $form) {
        parent::setForm($form);

        if(!$form->getEnctype()) {
            $form->setEnctype(Form::ENCTYPE_FORM_DATA);
        }
        $form->setMethod(Form::METHOD_POST);
    }


	/**
	 * @param int|null $maximal_file_size
	 */
	public function setMaximalFileSize($maximal_file_size) {
		$this->maximal_file_size = $maximal_file_size;
	}

	/**
	 * @return int|null
	 */
	public function getMaximalFileSize() {
		return $this->maximal_file_size;
	}

	/**
	 * @param array $allowed_mime_types
	 */
	public function setAllowedMimeTypes( array $allowed_mime_types ) {
		$this->allowed_mime_types = $allowed_mime_types;
	}

	/**
	 * @return array
	 */
	public function getAllowedMimeTypes() {
		return $this->allowed_mime_types;
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->file_name;
	}

	/**
	 * @return string
	 */
	public function getTmpFilePath() {
		return $this->tmp_file_path;
	}

    /**
     * @param string $uploaded_file_path
     */
    public function setUploadedFilePath($uploaded_file_path)
    {
        $this->uploaded_file_path = $uploaded_file_path;
    }

    /**
     * @return string
     */
    public function getUploadedFilePath()
    {
        if(!$this->uploaded_file_path) {
            return $this->getTmpFilePath();
        }
        return $this->uploaded_file_path;
    }




	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {

		$this->_value = null;
		$this->_has_value = isset($_FILES[$this->_name]) && !empty($_FILES[$this->_name]['tmp_name']);

		if($this->_has_value) {
			$this->_value_raw = $_FILES[$this->_name];
			$this->_value = $_FILES[$this->_name]['tmp_name'];
			$this->tmp_file_path = $_FILES[$this->_name]['tmp_name'];
			$this->file_name = $_FILES[$this->_name]['name'];
		} else {
			$this->_value_raw = null;
		}
	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {
        if(!$this->_has_value) {
            if($this->is_required) {
                $this->setValueError(self::ERROR_CODE_EMPTY);

                return false;
            }

            return true;
        }

		if($this->maximal_file_size) {
			$file_size = IO_File::getSize( $this->_value );
			if( $file_size>$this->maximal_file_size ) {
				$this->setValueError(self::ERROR_CODE_FILE_IS_TOO_LARGE);

				return false;
			}
		}

		if($this->allowed_mime_types) {
			if(!in_array(
				IO_File::getMimeType( $this->_value ),
				$this->allowed_mime_types
			)) {
				$this->setValueError(self::ERROR_CODE_DISALLOWED_FILE_TYPE);

				return false;
			}
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'file' );
		$tag_data->setProperty( 'required', 'required');
		//$tag_data->setProperty( 'value', $this->getValue() );

		if($this->allowed_mime_types) {
			$tag_data->setProperty( 'accept', implode(',', $this->allowed_mime_types) );
		}

		return '<input '.$this->_getTagPropertiesAsString($tag_data).' class="file"/>';
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