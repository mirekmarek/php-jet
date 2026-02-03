<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_Hidden extends Form_Field
{
	protected string $_type = Form_Field::TYPE_HIDDEN;
	protected string $_validator_type = Validator::TYPE_NULL;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRING;
	
	public function render(): string
	{
		return (string)$this->input();
	}

}