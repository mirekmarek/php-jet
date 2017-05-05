<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_Hidden
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_Hidden extends Form_Renderer_Bootstrap_Field_Input
{

	/**
	 * @var string
	 */
	protected $_input_type = 'hidden';

	/**
	 * @return string
	 */
	public function render()
	{
		$this->container_disabled = true;

		return parent::render();
	}

}