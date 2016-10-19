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

class Form_Renderer_Bootstrap_Field_Textarea extends Form_Renderer_Bootstrap_Field_Abstract {

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

		$value = $this->_field->getValue();

		$tag_options = [
			'name' => $this->_field->getTagNameValue(),
			'id' => $this->_field->getID()
		];

		if($this->_field->getIsReadonly()) {
			$tag_options['readonly'] = 'readonly';
		}

		if($this->container_disabled) {
			$result = $this->generate($tag_options, $value).JET_EOL;

		} else {
			$result = $this->render_containerStart().$this->generate($tag_options, $value).JET_EOL.$this->render_containerEnd().JET_EOL;
		}

		return $result;
	}

}