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

class Form_Renderer_Bootstrap_Field_Checkbox extends Form_Renderer_Bootstrap_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_input_type = 'checkbox';

	/**
	 * @var string
	 */
	protected $base_css_class = '';


	/**
	 * @return string
	 */
	protected function render_containerStart() {
		return '<div class="col-'.$this->getSize().'-offset-'.$this->_field->label()->getWidth().' col-'.$this->getSize().'-'.$this->getWidth().'">'
				.'<div class="checkbox">'.
					'<label>';
	}

	/**
	 * @return string
	 */
	protected function render_containerEnd() {
		return $this->_field->getLabel().'</label>'
			.'</div>'
		.'</div>';
	}


	/**
	 * @param array &$tag_options
	 */
	protected function initTagOptions( array &$tag_options ) {
		/**
		 * @var Form_Field_Checkbox $fl
		 */
		$fl = $this->_field;

		$tag_options['value'] = 1;

		if($fl->getValue()) {
			$tag_options['checked'] = 'checked';
		}

		if($fl->getIsReadonly()) {
			$tag_options['disabled'] = 'disabled';
		}


	}


}