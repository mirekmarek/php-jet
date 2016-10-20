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

abstract class Form_Renderer_Abstract_Label extends Form_Renderer_Abstract_Tag
{

	/**
	 * @var string
	 */
	protected $tag = 'label';

	/**
	 * @var bool
	 */
	protected $is_pair = false;

	/**
	 * @var bool
	 */
	protected $has_content = true;

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
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $for;

	/**
	 *
	 * @param Form_Field_Abstract $form_field
	 */
	public function __construct(Form_Field_Abstract $form_field)
	{
		$this->_field = $form_field;
        $this->label = $form_field->getLabel();
        $this->for = $form_field->getID();
	}

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getFor()
    {
        return $this->for;
    }

    /**
     * @param string $for
     */
    public function setFor($for)
    {
        $this->for = $for;
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

		return $this->_field->getForm()->getDefaultLabelWidth();
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
		if(
			$this->_field instanceof Form_Field_Checkbox ||
			$this->_field instanceof Form_Field_Hidden
		) {
			return '';
		}


		$tag_options = [
		    'for' => $this->for
		];

		$label = $this->label;

		if($this->_field->getIsRequired()) {
			$label = '<em class="form-required">*</em> '.$label;
		}

		return $this->generate( $tag_options, $label );
	}

}