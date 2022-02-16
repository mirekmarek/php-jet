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
class Form_Field_Input extends Form_Field implements Form_Field_Part_RegExp_Interface
{
	use Form_Field_Part_RegExp_Trait;
	
	/**
	 * @var array
	 */
	protected array $error_messages = [
		Form_Field::ERROR_CODE_EMPTY        => '',
		Form_Field::ERROR_CODE_INVALID_FORMAT => '',
	];
	
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_INPUT;
	
}