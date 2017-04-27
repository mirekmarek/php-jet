<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_Field_WYSIWYG
 * @package Jet
 */
class Form_Renderer_Bootstrap_Field_WYSIWYG extends Form_Renderer_Bootstrap_Field_Abstract {

	/**
	 * @var string
	 */
	protected $tag = 'textarea';

	/**
	 * @var bool
	 */
	protected $has_content = true;

	/**
	 * @return string
	 */
	public function render()
	{
		/**
		 * @var Form_Field_WYSIWYG $fl
		 */

		$fl = $this->_field;

		$value = $this->_field->getValue();

		$tag_options = [
            'name' => $this->getTagNameValue(),
            'id' => $this->getTagId(),
		];

		if($this->_field->getIsReadonly()) {
			$tag_options['readonly'] = 'readonly';
		}

		if($this->container_disabled) {
			$result = $this->generate($tag_options, $value).JET_EOL;

		} else {
			$result = $this->render_containerStart().$this->generate($tag_options, $value).JET_EOL.$this->render_containerEnd().JET_EOL;
		}

		$result .= $fl->generateJsInitCode();

		return $result;
	}

}