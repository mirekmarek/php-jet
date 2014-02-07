<?php
/**
 *
 *
 *
 * class representing single form field - type float
 *
 * specific options:
 *
 * specific errors:
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
	/**
	 * @var string
	 */
	protected $_type = 'FileImage';

	/**
	 * @var bool
	 */
	protected $_possible_to_decorate = false;

	/**
	 * @var array
	 */
	protected $error_messages = array(
		'input_missing' => 'input_missing',
		'empty' => 'empty',
		'file_is_too_large' => 'file_is_too_large',
		'disallowed_file_type' => 'disallowed_file_type'
	);

	/**
	 * @var array
	 */
	protected $allowed_mime_types = array(
		'image/pjpeg',
		'image/jpeg',
		'image/jpg',
		'image/gif',
		'image/png'
	);

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
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {

		$this->_value = null;
		$this->_has_value = isset($_FILES[$this->_name]);

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
		if(!parent::validateValue()) {
			return false;
		}

		if(
			$this->maximal_width &&
			$this->maximal_height
		) {
			try {
				$image = new Image( $this->_value );
				$image->createThumbnail( $this->_value, $this->maximal_width, $this->maximal_height );
			} catch( Image_Exception $e ) {
				$this->setValueError('disallowed_file_type');

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
		$tag_data->setProperty( 'value', $this->getValue() );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}


}