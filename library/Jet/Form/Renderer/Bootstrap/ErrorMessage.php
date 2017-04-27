<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Bootstrap_ErrorMessage
 * @package Jet
 */
class Form_Renderer_Bootstrap_ErrorMessage extends Form_Renderer_Abstract_ErrorMessage {

	/**
	 * @return string
	 */
	public function render()
	{
		if(!$this->_field->getLastErrorMessage()) {
			return '';
		}

		$fl = $this->_field->field();
		$lb = $this->_field->label();

		$result = '<div class="form-group row has-error has-feedback">
				<label class="col-'.$lb->getSize().'-'.$lb->getWidth().' control-label"/></label>
				<div class="col-'.$fl->getSize().'-'.$fl->getWidth().'">
					'.$this->_field->getLastErrorMessage().'
				</div>
			</div>';

		return $result;

	}

}