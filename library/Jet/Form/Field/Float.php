<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Form_Field_Float extends Form_Field implements Form_Field_Part_NumberRangeFloat_Interface
{
	use Form_Field_Part_NumberRangeFloat_Trait;

	protected string $_type = Form_Field::TYPE_FLOAT;
	protected string $_validator_type = Validator::TYPE_FLOAT;
	protected string $_input_catcher_type = InputCatcher::TYPE_FLOAT;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => 'Please enter a value',
		Form_Field::ERROR_CODE_OUT_OF_RANGE => 'Out of range',
	];

}