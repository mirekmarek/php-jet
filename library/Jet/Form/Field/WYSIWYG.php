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
class Form_Field_WYSIWYG extends Form_Field
{
	protected string $_type = Form_Field::TYPE_WYSIWYG;
	protected string $_validator_type = Validator::TYPE_REGEXP;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRING_RAW;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => 'Please enter a value',
	];
	
}