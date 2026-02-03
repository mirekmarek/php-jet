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
class Form_Field_Email extends Form_Field
{
	protected string $_type = Form_Field::TYPE_EMAIL;
	protected string $_validator_type = Validator::TYPE_EMAIL;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRING;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY          => 'Please enter a value',
		Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
	];

}