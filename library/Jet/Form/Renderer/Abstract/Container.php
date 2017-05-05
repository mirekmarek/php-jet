<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Abstract_Container
 * @package Jet
 */
abstract class Form_Renderer_Abstract_Container extends Form_Renderer_Abstract_Tag
{

	/**
	 * @var string
	 */
	protected $tag = 'div';

	/**
	 * @var bool
	 */
	protected $is_pair = true;

	/**
	 * @var bool
	 */
	protected $has_content = false;

	/**
	 * @var Form_Field_Abstract
	 */
	protected $_field;

	/**
	 *
	 * @param Form_Field_Abstract $form_field
	 */
	public function __construct( Form_Field_Abstract $form_field )
	{
		$this->_field = $form_field;
	}

	/**
	 * @return string
	 */
	public function render()
	{

		$tag_options = [];

		return $this->generate( $tag_options );
	}


}