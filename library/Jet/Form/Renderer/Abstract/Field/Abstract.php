<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

abstract class Form_Renderer_Abstract_Field_Abstract extends Form_Renderer_Abstract_Tag
{

	/**
	 * @var string
	 */
	protected $_input_type = 'text';

	/**
	 * @var string
	 */
	protected $tag = 'input';

	/**
	 * @var bool
	 */
	protected $is_pair = false;

	/**
	 * @var bool
	 */
	protected $has_content = false;

	/**
	 * @var string
	 */
	protected $type = 'input';

	/**
	 * @var Form_Field_Abstract
	 */
	protected $_field;

	/**
	 * @var int
	 */
	protected $custom_width;

	/**
	 * @var string
	 */
	protected $custom_size;

	/**
	 *
	 * @param Form_Field_Abstract $form_field
	 */
	public function __construct(Form_Field_Abstract $form_field)
	{
		$this->_field = $form_field;
	}

	/**
	 * @param int $width
	 *
	 * @return $this
	 */
	public function setWidth($width)
	{
		$this->custom_width = $width;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		if($this->custom_width) {
			return $this->custom_width;
		}

		return $this->_field->getForm()->getDefaultFieldWidth();
	}

	/**
	 * @return string
	 */
	public function getSize()
	{
		if($this->custom_size) {
			return $this->custom_size;
		}

		return $this->_field->getForm()->getDefaultSize();
	}

	/**
	 * @param string $custom_size
	 *
	 * @return $this
	 */
	public function setSize($custom_size)
	{
		$this->custom_size = $custom_size;

		return $this;
	}



	/**
	 * @return string
	 */
	public function render() {
		$tag_options = [
			'id' => $this->_field->getID(),
			'name' => $this->_field->getTagNameValue(),
			'value' => $this->_field->getValue(),
			'type' => $this->_input_type
		];

		if(($placeholder=$this->_field->getPlaceholder())) {
			$tag_options['placeholder'] = $placeholder;
		}

		if($this->_field->getIsReadonly()) {
			$tag_options['readonly'] = 'readonly';
		}

		if($this->_field->getIsRequired()) {
			$tag_options['required'] = 'required';
		}

		if( ($regexp=$this->_field->getValidationRegexp()) ) {

			if($regexp[0]=='/') {
				$regexp = substr($regexp, 1);
				$regexp = substr($regexp, 0, strrpos($regexp, '/'));
			}

			$tag_options['pattern'] = $regexp;
		}

		return $this->generate( $tag_options );
	}

}