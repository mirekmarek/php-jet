<?php 
/**
 *
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

class Form_Field_Input extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_INPUT;

	/**
	 * @var string
	 */
	protected $placeholder = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '',
		self::ERROR_CODE_INVALID_FORMAT => ''
	];


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		if($this->validation_regexp) {
			$codes[] = self::ERROR_CODE_INVALID_FORMAT;
		}

		return $codes;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder()
	{
		return $this->getTranslation($this->placeholder);
	}

	/**
	 * @param string $placeholder
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getTranslation($this->title);
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}



	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', $this->_input_type );
		$tag_data->setProperty( 'value', $this->getValue() );

		if( ($placeholder=$this->getPlaceholder()) ) {
			$tag_data->setProperty('placeholder', $placeholder);
		}

		if( ($title=$this->getTitle()) ) {
			$tag_data->setProperty('title', $title);
		}

		if($this->is_required) {
			$tag_data->setProperty('required', 'required');
		}

		if($this->validation_regexp) {
			$tag_data->setProperty('pattern', $this->validation_regexp);
		}

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}


}