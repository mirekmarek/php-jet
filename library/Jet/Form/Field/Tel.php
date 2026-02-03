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
class Form_Field_Tel extends Form_Field
{
	protected string $_validator_type = Validator::TYPE_TEL;
	protected string $_type = Form_Field::TYPE_TEL;
	protected string $_input_catcher_type = InputCatcher::TYPE_STRING;

	public const ERROR_CODE_INVALID_TEL_NUMBER_TYPE = Validator_Tel::ERROR_CODE_INVALID_TEL_NUMBER_TYPE;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => 'Please enter a value',
		Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
		self::ERROR_CODE_INVALID_TEL_NUMBER_TYPE => 'Invalid telephone number type',
	];
	
}