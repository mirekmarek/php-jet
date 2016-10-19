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

abstract class Form_Renderer_Abstract_Form_Message extends Form_Renderer_Abstract_Tag {

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
	 * @var Form
	 */
	protected $_form;

	/**
	 * Form_Renderer_Bootstrap_Form constructor.
	 *
	 * @param Form $form
	 */
	public function __construct(Form $form)
	{
		$this->_form = $form;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		if(!$this->_form->getCommonMessage()) {
			return '';
		}

		$tag_options = [
		];

		return $this->generate($tag_options, $this->_form->getCommonMessage() );
	}
}