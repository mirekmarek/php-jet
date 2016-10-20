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

abstract class Form_Renderer_Abstract_ErrorMessage extends Form_Renderer_Abstract_Tag
{

	/**
	 * @var string
	 */
	protected $tag = 'div';

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
     * @var string
     */
    protected $error_message = '';

	/**
	 *
	 * @param Form_Field_Abstract $form_field
	 */
	public function __construct(Form_Field_Abstract $form_field)
	{
		$this->_field = $form_field;
        $this->error_message = $form_field->getLastErrorMessage();
	}

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param string $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }



	/**
	 * @return string
	 */
	public function render() {
	    if(!$this->error_message) {
	        return '';
        }

		$tag_options = [
		];

		return $this->generate( $tag_options, $this->error_message );
	}

}